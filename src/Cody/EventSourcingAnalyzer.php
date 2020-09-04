<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody;

use EventEngine\InspectioGraph\AggregateConnection;
use EventEngine\InspectioGraph\AggregateConnectionMap;
use EventEngine\InspectioGraph\Exception\RuntimeException;
use EventEngine\InspectioGraph\VertexMap;
use EventEngine\InspectioGraph\VertexType;

final class EventSourcingAnalyzer implements \EventEngine\InspectioGraph\EventSourcingAnalyzer
{
    /**
     * @var Node
     **/
    private $node;

    /**
     * @var callable
     **/
    private $filterName;

    /**
     * @var VertexMap
     */
    private $commandMap;

    /**
     * @var VertexMap
     */
    private $eventMap;

    /**
     * @var AggregateConnectionMap
     */
    private $aggregateConnectionMap;

    /**
     * @var callable
     */
    private $metadataFactory;

    public function __construct(
        Node $node,
        callable $filterName,
        ?callable $metadataFactory = null
    ) {
        $this->node = $node;
        $this->filterName = $filterName;
        $this->metadataFactory = $metadataFactory;
    }

    private function filterVerticesByType(string $type): array
    {
        $vertices = [];

        if ($this->node->type() === $type) {
            $vertices[] = $this->node;
        }

        foreach ($this->node->sources() as $source) {
            if ($source->type() === $type) {
                $vertices[] = $source;
            }
        }

        foreach ($this->node->targets() as $target) {
            if ($target->type() === $type) {
                $vertices[] = $target;
            }
        }

        return $vertices;
    }

    public function commandMap(): VertexMap
    {
        if (null === $this->commandMap) {
            $this->commandMap = VertexMap::fromVertices(...$this->vertexMapByType(VertexType::TYPE_COMMAND));
        }

        return $this->commandMap;
    }

    public function eventMap(): VertexMap
    {
        if (null === $this->eventMap) {
            $this->eventMap = VertexMap::fromVertices(...$this->vertexMapByType(VertexType::TYPE_EVENT));
        }

        return $this->eventMap;
    }

    public function aggregateMap(): AggregateConnectionMap
    {
        if (null === $this->aggregateConnectionMap) {
            $this->aggregateConnectionMap = AggregateConnectionMap::emptyMap();

            $commandMap = $this->commandMap();
            $eventMap = $this->eventMap();

            /** @var Node $vertex */
            foreach ($this->filterVerticesByType(VertexType::TYPE_AGGREGATE) as $vertex) {
                $aggregate = Vertex::fromCodyNode($vertex, $this->filterName, $this->metadataFactory);
                $name = $aggregate->name();

                if (false === $this->aggregateConnectionMap->has($name)) {
                    $this->aggregateConnectionMap = $this->aggregateConnectionMap->with(
                        $name,
                        // @phpstan-ignore-next-line
                        new AggregateConnection($aggregate)
                    );
                }

                $commandName = '';
                $command = null;

                foreach ($vertex->sources() as $source) {
                    $command = $source;
                    break;
                }

                if ($command !== null && $command->type() === VertexType::TYPE_COMMAND) {
                    $commandName = ($this->filterName)($command->name());
                }

                if ($commandName === null || false === $commandMap->has($commandName)) {
                    throw new RuntimeException('Could not find command for aggregate');
                }

                $events = \array_map(
                    function (Node $vertex) use ($eventMap) {
                        $eventName = ($this->filterName)($vertex->name());

                        if ($vertex->type() !== VertexType::TYPE_EVENT || false === $eventMap->has($eventName)) {
                            throw new RuntimeException('Could not find event for aggregate');
                        }

                        return $eventMap->vertex($eventName);
                    },
                    $vertex->targets()
                );

                $this->aggregateConnectionMap = $this->aggregateConnectionMap->with(
                    $name,
                    // @phpstan-ignore-next-line
                    $this->aggregateConnectionMap->aggregateConnection($name)->withCommandEvents(
                        $commandMap->vertex($commandName),
                        ...$events
                    )
                );
            }
        }

        return $this->aggregateConnectionMap;
    }

    private function vertexMapByType(string $type): array
    {
        return \array_map(
            function (Node $vertex) {
                return Vertex::fromCodyNode($vertex, $this->filterName, $this->metadataFactory);
            },
            $this->filterVerticesByType($type)
        );
    }
}

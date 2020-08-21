<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

use EventEngine\InspectioGraph\Exception\RuntimeException;
use Fhaculty\Graph;

final class EventSourcingAnalyzer
{
    /**
     * @var Graph\Graph
     **/
    private $graph;

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
        Graph\Graph $graph,
        callable $filterName,
        ?callable $metadataFactory = null
    ) {
        $this->graph = $graph;
        $this->filterName = $filterName;
        $this->metadataFactory = $metadataFactory;
    }

    private function filterVerticesByType(string $type): Graph\Set\Vertices
    {
        $filterAggregates = static function (Graph\Vertex $vertex) use ($type) {
            return $vertex->getAttribute('type') === $type;
        };

        return $this->graph->getVertices()->getVerticesMatch($filterAggregates);
    }

    public function commandMap(): VertexMap
    {
        if (null === $this->commandMap) {
            $this->commandMap = VertexMap::fromVertices(...$this->vertexMapByType(Vertex::TYPE_COMMAND));
        }

        return $this->commandMap;
    }

    public function eventMap(): VertexMap
    {
        if (null === $this->eventMap) {
            $this->eventMap = VertexMap::fromVertices(...$this->vertexMapByType(Vertex::TYPE_EVENT));
        }

        return $this->eventMap;
    }

    public function aggregateMap(): AggregateConnectionMap
    {
        if (null === $this->aggregateConnectionMap) {
            $this->aggregateConnectionMap = AggregateConnectionMap::emptyMap();

            $commandMap = $this->commandMap();
            $eventMap = $this->eventMap();

            /** @var Graph\Vertex $vertex */
            foreach ($this->filterVerticesByType(Vertex::TYPE_AGGREGATE) as $vertex) {
                $aggregate = Vertex::fromGraphVertex($vertex, $this->filterName, $this->metadataFactory);
                $name = $aggregate->name();

                if (false === $this->aggregateConnectionMap->has($name)) {
                    $this->aggregateConnectionMap = $this->aggregateConnectionMap->with(
                        $name,
                        // @phpstan-ignore-next-line
                        new AggregateConnection($aggregate)
                    );
                }

                $commandName = ($this->filterName)($vertex->getVerticesEdgeFrom()->getVertexFirst()->getAttribute('label'));

                if (false === $commandMap->has($commandName)) {
                    throw new RuntimeException('Could not find command for aggregate');
                }

                $events = \array_map(
                    function (Graph\Vertex $vertex) use ($eventMap) {
                        $eventName = ($this->filterName)($vertex->getAttribute('label'));

                        if (false === $eventMap->has($eventName)) {
                            throw new RuntimeException('Could not find event for aggregate');
                        }

                        return $eventMap->vertex($eventName);
                    },
                    $vertex->getVerticesEdgeTo()->getVector()
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
            function (Graph\Vertex $vertex) {
                return Vertex::fromGraphVertex($vertex, $this->filterName, $this->metadataFactory);
            },
            $this->filterVerticesByType($type)->getVector()
        );
    }
}

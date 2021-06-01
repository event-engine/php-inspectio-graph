<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use Countable;
use EventEngine\InspectioGraph\AggregateType;
use EventEngine\InspectioGraph\CommandType;
use EventEngine\InspectioGraph\DocumentType;
use EventEngine\InspectioGraph\EventType;
use EventEngine\InspectioGraph\VertexMap;
use Iterator;

final class AggregateConnectionMap implements Iterator, Countable
{
    /**
     * @var AggregateConnection[]
     */
    private $map = [];

    public static function emptyMap(): AggregateConnectionMap
    {
        return new self();
    }

    public static function fromAggregateConnections(AggregateConnection ...$aggregateConnections): AggregateConnectionMap
    {
        return new self(...$aggregateConnections);
    }

    private function __construct(AggregateConnection ...$aggregateConnections)
    {
        foreach ($aggregateConnections as $aggregateConnection) {
            $this->map[$aggregateConnection->aggregate()->id()] = $aggregateConnection;
        }
    }

    public function with(string $id, AggregateConnection $aggregateConnection): self
    {
        $instance = clone $this;

        if (isset($instance->map[$id])) {
            /** @phpstan-ignore-next-line */
            $instance->map[$id] = $instance->map[$id]->withCommands(...$aggregateConnection->commandMap()->vertices());
            /** @phpstan-ignore-next-line */
            $instance->map[$id] = $instance->map[$id]->withEvents(...$aggregateConnection->eventMap()->vertices());
            /** @phpstan-ignore-next-line */
            $instance->map[$id] = $instance->map[$id]->withDocuments(...$aggregateConnection->documentMap()->vertices());

            $commandsToEventsMap = $aggregateConnection->commandsToEventsMap();

            foreach ($commandsToEventsMap as $command) {
                $instance->map[$id] = $instance->map[$id]->withCommandEvents($command, ...$commandsToEventsMap[$command]);
            }
        } else {
            $instance->map[$id] = $aggregateConnection;
        }
        \reset($instance->map);

        return $instance;
    }

    public function without(string $id): self
    {
        $instance = clone $this;
        unset($instance->map[$id]);

        return $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->map[$id]);
    }

    public function aggregateConnection(string $id): AggregateConnection
    {
        return $this->map[$id];
    }

    public function count(): int
    {
        return \count($this->map);
    }

    public function rewind(): void
    {
        \reset($this->map);
    }

    public function key(): string
    {
        return \key($this->map);
    }

    public function next(): void
    {
        \next($this->map);
    }

    public function valid(): bool
    {
        return false !== \current($this->map);
    }

    /**
     * @return AggregateConnection
     */
    public function current()
    {
        return \current($this->map);
    }

    public function aggregateVertexMap(): VertexMap
    {
        return VertexMap::fromVertices(
            ...\array_values(
                \array_map(
                    static function (AggregateConnection $aggregateDescription) {
                        return $aggregateDescription->aggregate();
                    },
                    $this->map
                )
            )
        );
    }

    public function aggregateByCommand(CommandType $command): ?AggregateType
    {
        foreach ($this->map as $aggregateConnection) {
            if ($aggregateConnection->commandMap()->has($command->name())) {
                return $aggregateConnection->aggregate();
            }
        }

        return null;
    }

    public function aggregateByEvent(EventType $event): ?AggregateType
    {
        foreach ($this->map as $aggregateConnection) {
            if ($aggregateConnection->eventMap()->has($event->name())) {
                return $aggregateConnection->aggregate();
            }
        }

        return null;
    }

    public function aggregateByDocument(DocumentType $document): ?AggregateType
    {
        foreach ($this->map as $aggregateConnection) {
            if ($aggregateConnection->documentMap()->has($document->name())) {
                return $aggregateConnection->aggregate();
            }
        }

        return null;
    }
}

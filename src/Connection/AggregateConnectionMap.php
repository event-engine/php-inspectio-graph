<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use EventEngine\InspectioGraph\AggregateType;
use EventEngine\InspectioGraph\CommandType;
use EventEngine\InspectioGraph\EventType;
use EventEngine\InspectioGraph\VertexMap;
use Iterator;

final class AggregateConnectionMap implements Iterator, \Countable
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
            $this->map[$aggregateConnection->aggregate()->name()] = $aggregateConnection;
        }
    }

    public function with(string $name, AggregateConnection $aggregateConnection): self
    {
        $instance = clone $this;
        $instance->map[$name] = $aggregateConnection;

        return $instance;
    }

    public function without(string $name): self
    {
        $instance = clone $this;
        unset($instance->map[$name]);

        return $instance;
    }

    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }

    public function aggregateConnection(string $name): AggregateConnection
    {
        return $this->map[$name];
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
}

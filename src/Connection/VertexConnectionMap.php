<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use Countable;
use EventEngine\InspectioGraph\VertexType;
use Iterator;

final class VertexConnectionMap implements Iterator, Countable
{
    /**
     * @var array<string, array<string, VertexConnection>>
     */
    private $connection = [];

    private function __construct()
    {
    }

    public static function emptyMap(): self
    {
        return new self();
    }

    private function addEntry(VertexType $from, VertexType $to): void
    {
        $keyFrom = $from->id();
        $keyTo = $to->id();
        $keyFromTo = $keyFrom . ' -> ' . $keyTo;

        $connection = new VertexConnection($from, $to);

        if (! isset($this->connection[$keyFrom])) {
            $this->connection[$keyFrom] = [];
        }
        if (! isset($this->connection[$keyTo])) {
            $this->connection[$keyTo] = [];
        }
        if (! isset($this->connection[$keyFrom][$keyFromTo])) {
            $this->connection[$keyFrom][$keyFromTo] = $connection;
        }
        if (! isset($this->connection[$keyTo][$keyFromTo])) {
            $this->connection[$keyTo][$keyFromTo] = $connection;
        }
    }

    public static function fromConnection(VertexType $from, VertexType $to): self
    {
        $self = new self();
        $self->addEntry($from, $to);

        return $self;
    }

    public function withConnection(VertexType $from, VertexType $to): self
    {
        $self = clone $this;

        $self->addEntry($from, $to);

        return $self;
    }

    /**
     * @param VertexType $vertex
     * @return VertexConnection[]
     */
    public function connectionsByVertex(VertexType $vertex): array
    {
        return $this->connection[$vertex->id()];
    }

    /**
     * @param string $id
     * @return VertexConnection[]
     */
    public function connections(string $id): array
    {
        return $this->connection[$id];
    }

    public function hasByVertex(VertexType $vertex): bool
    {
        return isset($this->connection[$vertex->id()]);
    }

    public function has(string $id): bool
    {
        return isset($this->connection[$id]);
    }

    public function count(): int
    {
        return \count($this->connection);
    }

    public function rewind(): void
    {
        \reset($this->connection);
    }

    public function key(): string
    {
        return \key($this->connection);
    }

    public function next(): void
    {
        \next($this->connection);
    }

    public function valid(): bool
    {
        return false !== \current($this->connection);
    }

    /**
     * @return array|false
     */
    public function current()
    {
        return \current($this->connection);
    }
}

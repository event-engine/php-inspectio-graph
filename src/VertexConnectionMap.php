<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

use Countable;
use Iterator;

final class VertexConnectionMap implements Iterator, Countable, CanAccessVertexConnection
{
    /**
     * @var VertexConnection[]
     */
    private array $connections = [];

    public static function emptyMap(): VertexConnectionMap
    {
        return new self();
    }

    public static function fromConnections(VertexConnection ...$vertices): VertexConnectionMap
    {
        return new self(...$vertices);
    }

    private function __construct(VertexConnection ...$vertices)
    {
        foreach ($vertices as $vertex) {
            $this->connections[$vertex->identity()->id()] = $vertex;
        }
    }

    public function with(VertexConnection ...$vertexes): self
    {
        $instance = clone $this;

        foreach ($vertexes as $vertex) {
            $id = $vertex->identity()->id();

            if (isset($instance->connections[$id])) {
                $instance->connections[$id] = $instance->connections[$id]
                    ->withFrom(...$vertex->from()->vertexIdentities())
                    ->withTo(...$vertex->to()->vertexIdentities())
                    ->withChildren(...$vertex->children()->vertexIdentities());

                if ($parent = $vertex->parent()) {
                    $instance->connections[$id] = $instance->connections[$id]->withParent($parent);
                }
            } else {
                $instance->connections[$id] = $vertex;
            }
        }
        \reset($instance->connections);

        return $instance;
    }

    public function without(string $id): self
    {
        $instance = clone $this;
        // TODO cycle through all connections and remove identity of from / to map
        unset($instance->connections[$id]);

        return $instance;
    }

    public function filterByType(string $type): self
    {
        $instance = clone $this;
        $instance->connections = \array_filter(
            $instance->connections,
            static fn (VertexConnection $identity) => $identity->identity()->type() === $type
        );
        \reset($instance->connections);

        return $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->connections[$id]);
    }

    public function connection(string $id): VertexConnection
    {
        return $this->connections[$id];
    }

    public function count(): int
    {
        return \count($this->connections);
    }

    public function rewind(): void
    {
        \reset($this->connections);
    }

    public function key(): string
    {
        return \key($this->connections);
    }

    public function next(): void
    {
        \next($this->connections);
    }

    public function valid(): bool
    {
        return false !== \current($this->connections);
    }

    /**
     * @return VertexConnection|false|mixed
     */
    public function current()
    {
        return \current($this->connections);
    }
}

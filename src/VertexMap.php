<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

use EventEngine\InspectioGraph\Exception\RuntimeException;
use Iterator;

final class VertexMap implements Iterator, \Countable
{
    /**
     * @var Vertex[]
     */
    private $vertices = [];

    public static function emptyMap(): VertexMap
    {
        return new self();
    }

    public static function fromVertices(Vertex ...$vertices): VertexMap
    {
        return new self(...$vertices);
    }

    private function __construct(Vertex ...$vertices)
    {
        foreach ($vertices as $vertex) {
            if (isset($this->vertices[$vertex->name()])
                && $this->vertices[$vertex->name()]->type() !== $vertex->type()
            ) {
                throw new RuntimeException(
                    \sprintf('Vertex with same name "%s" and different types detected.', $vertex->name())
                );
            }

            $this->vertices[$vertex->name()] = $vertex;
        }
    }

    public function with(Vertex $vertex): self
    {
        $name = $vertex->name();

        if (isset($this->vertices[$name])
            && $this->vertices[$name]->type() !== $vertex->type()
        ) {
            throw new RuntimeException(
                \sprintf('Vertex with same name "%s" and different types detected.', $vertex->name())
            );
        }

        $instance = clone $this;

        $instance->vertices[$name] = $vertex;

        return $instance;
    }

    public function without(string $name): self
    {
        $instance = clone $this;
        unset($instance->vertices[$name]);

        return $instance;
    }

    public function has(string $name): bool
    {
        return isset($this->vertices[$name]);
    }

    public function vertex(string $name): Vertex
    {
        return $this->vertices[$name];
    }

    public function count(): int
    {
        return \count($this->vertices);
    }

    public function rewind(): void
    {
        \reset($this->vertices);
    }

    public function key(): string
    {
        return \key($this->vertices);
    }

    public function next(): void
    {
        \next($this->vertices);
    }

    public function valid(): bool
    {
        return false !== \current($this->vertices);
    }

    public function current()
    {
        return \current($this->vertices);
    }
}

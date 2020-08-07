<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

use Fhaculty\Graph;

final class Command extends Vertex
{
    public const TYPE = self::TYPE_COMMAND;

    /**
     * @var bool
     */
    private $initial = false;

    protected function init(
        Graph\Vertex $vertex,
        callable $filterName
    ): void {
        $this->initial = (bool) $vertex->getAttribute('initial');
    }

    public function initial(): bool
    {
        return $this->initial;
    }

    public function withInitial(bool $initial = true): self
    {
        $self = clone $this;
        $self->initial = $initial;

        return $self;
    }
}

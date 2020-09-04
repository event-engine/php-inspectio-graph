<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody;

interface Node
{
    public function id(): string;

    public function name(): string;

    public function type(): string;

    public function tags(): iterable;

    public function isLayer(): bool;

    public function isDefaultLayer(): bool;

    public function parent(): ?Node;

    /**
     * @return Node[]
     */
    public function children(): iterable;

    /**
     * @return Node[]
     */
    public function sources(): iterable;

    /**
     * @return Node[]
     */
    public function targets(): iterable;

    public function metadata(): ?string;
}

<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

interface VertexType
{
    public const TYPE_AGGREGATE = 'aggregate';
    public const TYPE_EVENT = 'event';
    public const TYPE_COMMAND = 'command';

    public const ATTRIBUTE_LABEL = 'label';
    public const ATTRIBUTE_TYPE = 'type';
    public const ATTRIBUTE_METADATA = 'metadata';

    public function id(): string;

    public function type(): string;

    /**
     * Raw vertex label
     *
     * @return string
     */
    public function label(): string;

    /**
     * Filtered label for name
     *
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function metadata(): string;
}

<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

final class Aggregate extends Vertex implements AggregateType
{
    protected const TYPE = self::TYPE_AGGREGATE;

    /**
     * @var Metadata\AggregateMetadata|null
     */
    protected $metadataInstance;

    public function metadataInstance(): ?Metadata\AggregateMetadata
    {
        return $this->metadataInstance;
    }
}

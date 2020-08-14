<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

final class Event extends Vertex
{
    protected const TYPE = self::TYPE_EVENT;

    /**
     * @var Metadata\EventMetadata|null
     */
    protected $metadataInstance;

    public function metadataInstance(): ?Metadata\EventMetadata
    {
        return $this->metadataInstance;
    }
}

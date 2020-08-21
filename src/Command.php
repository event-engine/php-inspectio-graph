<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

final class Command extends Vertex
{
    protected const TYPE = self::TYPE_COMMAND;

    /**
     * @var Metadata\CommandMetadata|null
     */
    protected $metadataInstance;

    public function metadataInstance(): ?Metadata\CommandMetadata
    {
        return $this->metadataInstance;
    }
}

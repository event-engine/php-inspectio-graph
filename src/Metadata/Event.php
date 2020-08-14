<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Metadata;

final class Event implements EventMetadata
{
    /**
     * @var bool
     */
    private $public = false;

    /**
     * @var string|null
     */
    private $schema;

    private function __construct()
    {
    }

    public static function fromJsonMetadata(string $json): self
    {
        $self = new self();

        if (empty($json)) {
            return $self;
        }
        $data = JsonMetadataFactory::decodeJson($json);

        $self->public = $data['public'] ?? false;

        if (! empty($data['schema'])) {
            $$self->schema = JsonMetadataFactory::encodeJson($data['schema']);
        }

        return $self;
    }

    public function public(): bool
    {
        return $this->public;
    }

    public function schema(): ?string
    {
        return $this->schema;
    }
}

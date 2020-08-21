<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Metadata;

final class Aggregate implements AggregateMetadata
{
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

        if (! empty($data['schema'])) {
            $self->schema = JsonMetadataFactory::encodeJson($data['schema']);
        }

        return $self;
    }

    public function schema(): ?string
    {
        return $this->schema;
    }
}

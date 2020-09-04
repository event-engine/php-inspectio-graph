<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\GraphMl\Metadata;

use EventEngine\InspectioGraph\Exception\RuntimeException;
use EventEngine\InspectioGraph\Metadata\Metadata;
use EventEngine\InspectioGraph\VertexType;
use Fhaculty\Graph;

final class GraphMlJsonMetadataFactory
{
    public function __invoke(Graph\Vertex $vertex, callable $filterName): Metadata
    {
        $metadata = (string) $vertex->getAttribute(VertexType::ATTRIBUTE_METADATA);

        switch ($vertex->getAttribute(VertexType::ATTRIBUTE_TYPE)) {
            case VertexType::TYPE_COMMAND:
                return Command::fromJsonMetadata($metadata);
            case VertexType::TYPE_AGGREGATE:
                return Aggregate::fromJsonMetadata($metadata);
            case VertexType::TYPE_EVENT:
                return Event::fromJsonMetadata($metadata);
            default:
                throw new RuntimeException(\sprintf('Given type "%s" is not supported', $vertex->getAttribute('type')));
        }
    }

    public static function decodeJson(string $json): array
    {
        return \json_decode($json, true, 512, \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR);
    }

    public static function encodeJson(array $json): string
    {
        $flags = \JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES | \JSON_PRESERVE_ZERO_FRACTION | \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT;

        return \json_encode($json, $flags);
    }
}

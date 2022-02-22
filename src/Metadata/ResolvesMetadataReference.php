<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Metadata;

use EventEngine\InspectioGraph\VertexConnectionMap;

interface ResolvesMetadataReference
{
    public function resolveMetadataReferences(VertexConnectionMap $vertexConnectionMap, callable $filterName): void;
}

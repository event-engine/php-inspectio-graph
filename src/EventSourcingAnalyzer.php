<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

interface EventSourcingAnalyzer extends CanAccessVertexConnection
{
    public function commandMap(): VertexConnectionMap;

    public function eventMap(): VertexConnectionMap;

    public function aggregateMap(): VertexConnectionMap;

    public function documentMap(): VertexConnectionMap;

    public function externalSystemMap(): VertexConnectionMap;

    public function hotSpotMap(): VertexConnectionMap;

    public function policyMap(): VertexConnectionMap;

    public function uiMap(): VertexConnectionMap;

    public function featureMap(): VertexConnectionMap;

    public function boundedContextMap(): VertexConnectionMap;
}

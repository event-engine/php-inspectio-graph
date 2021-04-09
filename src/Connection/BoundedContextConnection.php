<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use EventEngine\InspectioGraph\AggregateType;
use EventEngine\InspectioGraph\BoundedContextType;
use EventEngine\InspectioGraph\CommandType;
use EventEngine\InspectioGraph\DocumentType;
use EventEngine\InspectioGraph\EventType;
use EventEngine\InspectioGraph\ExternalSystemType;
use EventEngine\InspectioGraph\FeatureType;
use EventEngine\InspectioGraph\HotSpotType;
use EventEngine\InspectioGraph\PolicyType;
use EventEngine\InspectioGraph\UiType;
use EventEngine\InspectioGraph\VertexMap;

final class BoundedContextConnection
{
    /**
     * @var BoundedContextType
     **/
    private $boundedContext;

    /**
     * @var VertexMap
     */
    private $commandMap;

    /**
     * @var VertexMap
     */
    private $eventMap;

    /**
     * @var VertexMap
     */
    private $aggregateMap;

    /**
     * @var VertexMap
     */
    private $documentMap;

    /**
     * @var VertexMap
     */
    private $policyMap;

    /**
     * @var VertexMap
     */
    private $uiMap;

    /**
     * @var VertexMap
     */
    private $externalSystemMap;

    /**
     * @var VertexMap
     */
    private $featureMap;

    /**
     * @var VertexMap
     */
    private $hotSpotMap;

    public function __construct(BoundedContextType $boundedContext)
    {
        $this->boundedContext = $boundedContext;
        $this->commandMap = VertexMap::emptyMap();
        $this->eventMap = VertexMap::emptyMap();
        $this->aggregateMap = VertexMap::emptyMap();
        $this->documentMap = VertexMap::emptyMap();
        $this->policyMap = VertexMap::emptyMap();
        $this->uiMap = VertexMap::emptyMap();
        $this->externalSystemMap = VertexMap::emptyMap();
        $this->hotSpotMap = VertexMap::emptyMap();
        $this->featureMap = VertexMap::emptyMap();
    }

    public function withCommands(CommandType ...$commands): self
    {
        $self = clone $this;

        foreach ($commands as $command) {
            $self->commandMap = $self->commandMap->with($command);
        }

        return $self;
    }

    public function withEvents(EventType ...$events): self
    {
        $self = clone $this;

        foreach ($events as $event) {
            $self->eventMap = $self->eventMap->with($event);
        }

        return $self;
    }

    public function withAggregates(AggregateType ...$aggregates): self
    {
        $self = clone $this;

        foreach ($aggregates as $aggregate) {
            $self->aggregateMap = $self->aggregateMap->with($aggregate);
        }

        return $self;
    }

    public function withDocuments(DocumentType ...$documents): self
    {
        $self = clone $this;

        foreach ($documents as $document) {
            $self->documentMap = $self->documentMap->with($document);
        }

        return $self;
    }

    public function withPolicies(PolicyType ...$policies): self
    {
        $self = clone $this;

        foreach ($policies as $policy) {
            $self->policyMap = $self->policyMap->with($policy);
        }

        return $self;
    }

    public function withUis(UiType ...$uis): self
    {
        $self = clone $this;

        foreach ($uis as $ui) {
            $self->uiMap = $self->policyMap->with($ui);
        }

        return $self;
    }

    public function withExternalSystems(ExternalSystemType ...$externalSystems): self
    {
        $self = clone $this;

        foreach ($externalSystems as $externalSystem) {
            $self->externalSystemMap = $self->externalSystemMap->with($externalSystem);
        }

        return $self;
    }

    public function withHotSpots(HotSpotType ...$hotSpots): self
    {
        $self = clone $this;

        foreach ($hotSpots as $hotSpot) {
            $self->hotSpotMap = $self->hotSpotMap->with($hotSpot);
        }

        return $self;
    }

    public function withFeatures(FeatureType ...$features): self
    {
        $self = clone $this;

        foreach ($features as $feature) {
            $self->featureMap = $self->featureMap->with($feature);
        }

        return $self;
    }

    public function boundedContext(): BoundedContextType
    {
        return $this->boundedContext;
    }

    public function commandMap(): VertexMap
    {
        return $this->commandMap;
    }

    public function eventMap(): VertexMap
    {
        return $this->eventMap;
    }

    public function aggregateMap(): VertexMap
    {
        return $this->aggregateMap;
    }

    public function documentMap(): VertexMap
    {
        return $this->documentMap;
    }

    public function policyMap(): VertexMap
    {
        return $this->policyMap;
    }

    public function uiMap(): VertexMap
    {
        return $this->uiMap;
    }

    public function externalSystemMap(): VertexMap
    {
        return $this->externalSystemMap;
    }

    public function hotSpotMap(): VertexMap
    {
        return $this->hotSpotMap;
    }

    public function featureMap(): VertexMap
    {
        return $this->featureMap;
    }
}

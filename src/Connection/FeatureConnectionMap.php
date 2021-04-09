<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use EventEngine\InspectioGraph\AggregateType;
use EventEngine\InspectioGraph\CommandType;
use EventEngine\InspectioGraph\DocumentType;
use EventEngine\InspectioGraph\EventType;
use EventEngine\InspectioGraph\ExternalSystemType;
use EventEngine\InspectioGraph\FeatureType;
use EventEngine\InspectioGraph\HotSpotType;
use EventEngine\InspectioGraph\PolicyType;
use EventEngine\InspectioGraph\UiType;
use EventEngine\InspectioGraph\VertexMap;
use Iterator;

final class FeatureConnectionMap implements Iterator, \Countable
{
    /**
     * @var FeatureConnection[]
     */
    private $map = [];

    public static function emptyMap(): FeatureConnectionMap
    {
        return new self();
    }

    public static function fromFeatureConnections(FeatureConnection ...$featureConnections): FeatureConnectionMap
    {
        return new self(...$featureConnections);
    }

    private function __construct(FeatureConnection ...$featureConnections)
    {
        foreach ($featureConnections as $featureConnection) {
            $this->map[$featureConnection->feature()->name()] = $featureConnection;
        }
    }

    public function with(string $name, FeatureConnection $featureConnection): self
    {
        $instance = clone $this;
        $instance->map[$name] = $featureConnection;

        return $instance;
    }

    public function without(string $name): self
    {
        $instance = clone $this;
        unset($instance->map[$name]);

        return $instance;
    }

    public function has(string $name): bool
    {
        return isset($this->map[$name]);
    }

    public function featureConnection(string $name): FeatureConnection
    {
        return $this->map[$name];
    }

    public function count(): int
    {
        return \count($this->map);
    }

    public function rewind(): void
    {
        \reset($this->map);
    }

    public function key(): string
    {
        return \key($this->map);
    }

    public function next(): void
    {
        \next($this->map);
    }

    public function valid(): bool
    {
        return false !== \current($this->map);
    }

    /**
     * @return FeatureConnection
     */
    public function current()
    {
        return \current($this->map);
    }

    public function featureVertexMap(): VertexMap
    {
        return VertexMap::fromVertices(
            ...\array_values(
                \array_map(
                    static function (FeatureConnection $featureConnection) {
                        return $featureConnection->feature();
                    },
                    $this->map
                )
            )
        );
    }

    public function featureByCommand(CommandType $command): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->commandMap()->has($command->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByEvent(EventType $event): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->eventMap()->has($event->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByAggregate(AggregateType $aggregate): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->aggregateMap()->has($aggregate->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByPolicy(PolicyType $policy): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->policyMap()->has($policy->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByDocument(DocumentType $document): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->documentMap()->has($document->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByExternalSystem(ExternalSystemType $externalSystem): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->externalSystemMap()->has($externalSystem->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByHotSpot(HotSpotType $hotSpot): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->hotSpotMap()->has($hotSpot->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }

    public function featureByUi(UiType $ui): ?FeatureType
    {
        foreach ($this->map as $featureConnection) {
            if ($featureConnection->uiMap()->has($ui->name())) {
                return $featureConnection->feature();
            }
        }

        return null;
    }
}

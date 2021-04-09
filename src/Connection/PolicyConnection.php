<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use EventEngine\InspectioGraph\CommandType;
use EventEngine\InspectioGraph\DocumentType;
use EventEngine\InspectioGraph\EventType;
use EventEngine\InspectioGraph\ExternalSystemType;
use EventEngine\InspectioGraph\PolicyType;
use EventEngine\InspectioGraph\VertexMap;
use SplObjectStorage;

final class PolicyConnection
{
    /**
     * @var PolicyType
     **/
    private $policy;

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
    private $documentMap;

    /**
     * @var VertexMap
     */
    private $externalSystemMap;

    /**
     * @var array<string, string>
     */
    private $eventToCommandList = [];

    /**
     * @var array<string, string>
     */
    private $eventToExternalSystemList = [];

    /**
     * @var array<string, string>
     */
    private $eventToEventList = [];

    /**
     * @var array<string, string>
     */
    private $eventToDocumentList = [];

    public function __construct(PolicyType $policy)
    {
        $this->policy = $policy;
        $this->commandMap = VertexMap::emptyMap();
        $this->eventMap = VertexMap::emptyMap();
        $this->documentMap = VertexMap::emptyMap();
        $this->externalSystemMap = VertexMap::emptyMap();
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

    public function withDocuments(DocumentType ...$documents): self
    {
        $self = clone $this;

        foreach ($documents as $document) {
            $self->documentMap = $self->documentMap->with($document);
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

    public function withEventCommand(EventType $event, CommandType $command): self
    {
        $self = clone $this;

        $self->eventMap = $self->eventMap->with($event);
        $self->commandMap = $self->commandMap->with($command);
        $self->eventToCommandList[$event->name()] = $command->name();

        return $self;
    }

    public function withEventDocument(EventType $event, DocumentType $document): self
    {
        $self = clone $this;

        $self->eventMap = $self->eventMap->with($event);
        $self->documentMap = $self->documentMap->with($document);
        $self->eventToCommandList[$event->name()] = $document->name();

        return $self;
    }

    public function policy(): PolicyType
    {
        return $this->policy;
    }

    public function commandMap(): VertexMap
    {
        return $this->commandMap;
    }

    public function eventMap(): VertexMap
    {
        return $this->eventMap;
    }

    public function documentMap(): VertexMap
    {
        return $this->documentMap;
    }

    public function externalSystemMap(): VertexMap
    {
        return $this->externalSystemMap;
    }

    /**
     * Event vertex is the key and the command vertex is the value.
     *
     * @return SplObjectStorage
     */
    public function eventsToCommandsMap(): SplObjectStorage
    {
        $map = new SplObjectStorage();

        foreach ($this->eventToCommandList as $eventName => $commandName) {
            $map[$this->eventMap->vertex($eventName)] = $this->commandMap->vertex($commandName);
        }

        return $map;
    }

    /**
     * Event vertex is the key and the document vertex is the value.
     *
     * @return SplObjectStorage
     */
    public function eventsToDocumentsMap(): SplObjectStorage
    {
        $map = new SplObjectStorage();

        foreach ($this->eventToDocumentList as $eventName => $documentName) {
            $map[$this->eventMap->vertex($eventName)] = $this->documentMap->vertex($documentName);
        }

        return $map;
    }

    /**
     * Indicates if this policy acts as a process manager which triggers commands depending on events.
     *
     * @return bool
     */
    public function isProcessManager(): bool
    {
        return ! empty($this->eventToCommandList);
    }

    /**
     * Indicates if this policy acts as a read model projection which updates a document depending on events.
     *
     * @return bool
     */
    public function isReadModel(): bool
    {
        return ! empty($this->eventToDocumentList);
    }
}

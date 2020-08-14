<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\CodeGenerator;

use EventEngine\InspectioGraph\Constraint\AllowedConnectionFromGreaterThan;
use EventEngine\InspectioGraph\Constraint\AllowedConnectionToLessThan;
use EventEngine\InspectioGraph\Constraint\AllowedConnectionToType;
use EventEngine\InspectioGraph\Constraint\AllowedVertexType;
use EventEngine\InspectioGraph\Constraint\ConstraintChain;
use EventEngine\InspectioGraph\Constraint\MandatoryAttribute;
use EventEngine\InspectioGraph\Transformator;
use EventEngine\InspectioGraph\Validator;
use Graphp\GraphML\Loader;
use OpenCodeModeling\CodeGenerator;
use OpenCodeModeling\CodeGenerator\Config\Component;

final class WorkflowConfigFactory
{
    /**
     * Slot for the \Fhaculty\Graph\Graph instance
     */
    public const SLOT_GRAPH = 'inspectio_graph-event_sourcing_graph';

    /**
     * Slot for the \EventEngine\InspectioGraph\EventSourcingAnalyzer instance
     */
    public const SLOT_EVENT_SOURCING_ANALYZER = 'inspectio_graph-event_sourcing_analyzer';

    /**
     * Configures a workflow to transform a GraphML XML string to an EventSourcingAnalyzer instance which is put to the
     * slot WorkflowConfigFactory::SLOT_EVENT_SOURCING_ANALYZER.
     *
     * @param string $inputSlotGraphMlXml Input slot name for GraphML XML document
     * @param callable $filterConstName
     * @return Component
     */
    public static function graphMlXmlToEventSourcingAnalyzer(
        string $inputSlotGraphMlXml,
        callable $filterConstName
    ): Component {
        $componentDescription = [
            // Configure model validation
            Transformator\GraphMlToGraph::workflowComponentDescription(
                new Loader(),
                $inputSlotGraphMlXml,
                self::SLOT_GRAPH
            ),
            Transformator\GraphToEventSourcingAnalyzer::workflowComponentDescription(
                self::defaultValidator(),
                $filterConstName,
                self::SLOT_GRAPH,
                self::SLOT_EVENT_SOURCING_ANALYZER
            ),
        ];

        return new CodeGenerator\Config\ArrayConfig(...$componentDescription);
    }

    public static function defaultValidator(): Validator
    {
        return new Validator(
            new ConstraintChain(
                new MandatoryAttribute('type', 'label'),
                new AllowedVertexType('command', 'event', 'aggregate'),
                new AllowedConnectionToType('command', 'aggregate'),
                new AllowedConnectionToType('aggregate', 'event'),
                new AllowedConnectionToLessThan('aggregate', 1),
                new AllowedConnectionFromGreaterThan('aggregate', 1)
            )
        );
    }
}

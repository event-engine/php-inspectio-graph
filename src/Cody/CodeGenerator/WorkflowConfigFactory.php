<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody\CodeGenerator;

use EventEngine\InspectioGraph\Cody\Constraint\ConstraintChain;
use EventEngine\InspectioGraph\Cody\Transformator;
use EventEngine\InspectioGraph\Cody\Validator;
use OpenCodeModeling\CodeGenerator;
use OpenCodeModeling\CodeGenerator\Config\Component;

final class WorkflowConfigFactory
{
    /**
     * Slot for the JSON data
     */
    public const SLOT_JSON = 'inspectio_graph-event_sourcing_json_node';

    /**
     * Slot for the \EventEngine\InspectioGraph\Cody\Node instance
     */
    public const SLOT_NODE = 'inspectio_graph-event_sourcing_node';

    /**
     * Slot for the \EventEngine\InspectioGraph\EventSourcingAnalyzer instance
     */
    public const SLOT_EVENT_SOURCING_ANALYZER = 'inspectio_graph-event_sourcing_analyzer_node';

    /**
     * Configures a workflow to transform a JSON string to an EventSourcingAnalyzer instance which is put to the
     * slot WorkflowConfigFactory::SLOT_EVENT_SOURCING_ANALYZER.
     *
     * @param string $inputSlotCodyJson Input slot name for Cody JSON string
     * @param callable $filterConstName
     * @param callable|null $metadataFactory
     * @return Component
     */
    public static function codyJsonToEventSourcingAnalyzer(
        string $inputSlotCodyJson,
        callable $filterConstName,
        ?callable $metadataFactory = null
    ): Component {
        $componentDescription = [
            // Configure model validation
            Transformator\CodyJsonToNode::workflowComponentDescription(
                $inputSlotCodyJson,
                self::SLOT_NODE
            ),
            Transformator\NodeToEventSourcingAnalyzer::workflowComponentDescription(
                self::defaultValidator(),
                $filterConstName,
                $metadataFactory,
                self::SLOT_NODE,
                self::SLOT_EVENT_SOURCING_ANALYZER
            ),
        ];

        return new CodeGenerator\Config\ArrayConfig(...$componentDescription);
    }

    public static function defaultValidator(): Validator
    {
        return new Validator(
            new ConstraintChain()
        );
    }
}

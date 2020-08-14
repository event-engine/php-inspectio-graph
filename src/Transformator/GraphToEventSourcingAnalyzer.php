<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Transformator;

use EventEngine\InspectioGraph\EventSourcingAnalyzer;
use EventEngine\InspectioGraph\Transformator\Exception\RuntimeException;
use EventEngine\InspectioGraph\Validator;
use Fhaculty\Graph;
use OpenCodeModeling\CodeGenerator\Workflow;

final class GraphToEventSourcingAnalyzer
{
    /**
     * @var Validator
     **/
    private $validator;

    /**
     * @var callable
     **/
    private $filterName;

    /**
     * @var callable
     **/
    private $metadataFactory;

    public function __construct(
        Validator $validator,
        callable $filterName,
        ?callable $metadataFactory
    ) {
        $this->validator = $validator;
        $this->filterName = $filterName;
        $this->metadataFactory = $metadataFactory;
    }

    public function __invoke(Graph\Graph $graph): EventSourcingAnalyzer
    {
        if (false === $this->validator->isValid($graph)) {
            throw new RuntimeException('Graph is invalid.');
        }

        return new EventSourcingAnalyzer($graph, $this->filterName, $this->metadataFactory);
    }

    public static function workflowComponentDescription(
        Validator $validator,
        callable $filterName,
        ?callable $metadataFactory,
        string $inputGraphml,
        string $output
    ): Workflow\Description {
        $instance = new self(
            $validator,
            $filterName,
            $metadataFactory
        );

        return new Workflow\ComponentDescriptionWithSlot(
            $instance,
            $output,
            $inputGraphml
        );
    }
}

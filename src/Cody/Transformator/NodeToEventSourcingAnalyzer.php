<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody\Transformator;

use EventEngine\InspectioGraph\Cody\Constraint\Exception\RuntimeException;
use EventEngine\InspectioGraph\Cody\EventSourcingAnalyzer;
use EventEngine\InspectioGraph\Cody\Node;
use EventEngine\InspectioGraph\Cody\Validator;
use OpenCodeModeling\CodeGenerator\Workflow;

final class NodeToEventSourcingAnalyzer
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

    public function __invoke(Node $node): \EventEngine\InspectioGraph\EventSourcingAnalyzer
    {
        if (false === $this->validator->isValid($node)) {
            throw new RuntimeException('Graph is invalid.');
        }

        return new EventSourcingAnalyzer($node, $this->filterName, $this->metadataFactory);
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

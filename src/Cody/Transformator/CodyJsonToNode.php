<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody\Transformator;

use EventEngine\InspectioGraph\Cody\JsonNode;
use EventEngine\InspectioGraph\Cody\Node;
use OpenCodeModeling\CodeGenerator\Workflow;

final class CodyJsonToNode
{
    public function __invoke(string $json): Node
    {
        return JsonNode::fromJson($json);
    }

    public static function workflowComponentDescription(
        string $inputJson,
        string $output
    ): Workflow\Description {
        $instance = new self();

        return new Workflow\ComponentDescriptionWithSlot(
            $instance,
            $output,
            $inputJson
        );
    }
}

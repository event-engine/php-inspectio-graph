<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\GraphMl\Transformator;

use Fhaculty\Graph\Graph;
use Graphp\GraphML\Loader;
use OpenCodeModeling\CodeGenerator\Workflow;

final class GraphMlToGraph
{
    /**
     * @var Loader
     */
    private $loader;

    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    public function __invoke(string $graphml): Graph
    {
        return $this->loader->loadContents($graphml);
    }

    public static function workflowComponentDescription(
        Loader $loader,
        string $inputGraphml,
        string $output
    ): Workflow\Description {
        $instance = new self($loader);

        return new Workflow\ComponentDescriptionWithSlot(
            $instance,
            $output,
            $inputGraphml
        );
    }
}

<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Constraint;

use EventEngine\InspectioGraph\Constraint\Exception\ConnectionException;
use Fhaculty\Graph;

final class AllowedConnectionToType implements Constraint
{
    /**
     * @var string
     **/
    private $forType;

    /**
     * @var string[]
     **/
    private $allowedTypes;

    public function __construct(string $forType, string ...$allowedTypes)
    {
        $this->forType = $forType;
        $this->allowedTypes = $allowedTypes;
    }

    public function __invoke(Graph\Vertex $vertex): void
    {
        $type = $vertex->getAttribute('type');

        if ($type !== $this->forType) {
            return;
        }

        $verticesTo = $vertex->getVerticesEdgeTo();
        $verticesToCount = $verticesTo->count();

        if ($verticesToCount !== 0) {
            $matchCommandConnectionTo = function (Graph\Vertex $vertex) {
                return \in_array($vertex->getAttribute('type'), $this->allowedTypes, true);
            };

            if ($verticesTo->getVerticesMatch($matchCommandConnectionTo)->count() !== $verticesToCount) {
                throw ConnectionException::wrongConnection($vertex, ...$this->allowedTypes);
            }
        }
    }
}

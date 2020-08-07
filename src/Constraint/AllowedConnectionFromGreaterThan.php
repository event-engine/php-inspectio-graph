<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Constraint;

use EventEngine\InspectioGraph\Constraint\Exception\WrongConnectionCountException;
use Fhaculty\Graph;

final class AllowedConnectionFromGreaterThan implements Constraint
{
    /**
     * @var string
     **/
    private $forType;

    /**
     * @var int
     **/
    private $min;

    public function __construct(string $forType, int $min)
    {
        $this->forType = $forType;
        $this->min = $min;
    }

    public function __invoke(Graph\Vertex $vertex): void
    {
        $type = $vertex->getAttribute('type');

        if ($type !== $this->forType) {
            return;
        }

        $vertices = $this->vertices($vertex);
        $verticesCount = $vertices->count();

        if ($this->min > $verticesCount) {
            throw WrongConnectionCountException::greaterThan($vertex, $this->min);
        }
    }

    protected function vertices(Graph\Vertex $vertex): Graph\Set\Vertices
    {
        return $vertex->getVerticesEdgeTo();
    }
}

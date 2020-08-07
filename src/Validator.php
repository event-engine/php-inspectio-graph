<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

use EventEngine\InspectioGraph\Constraint\Exception\ConstraintException;
use Fhaculty\Graph\Graph as GraGraph;
use Fhaculty\Graph\Vertex as GraVertex;

class Validator
{
    /**
     * @var callable
     */
    private $constraint;

    /**
     * @var ConstraintException[]
     */
    private $errors = [];

    public function __construct(callable $constraint)
    {
        $this->constraint = $constraint;
    }

    public function isValid(GraGraph $graph): bool
    {
        $isValid = true;

        /** @var GraVertex $vertex */
        foreach ($graph->getVertices() as $vertex) {
            try {
                ($this->constraint)($vertex);
            } catch (ConstraintException $e) {
                $isValid = false;
                $this->errors[] = $e;
            }
        }

        return $isValid;
    }

    /**
     * @return ConstraintException[]
     */
    public function errors(): array
    {
        return $this->errors;
    }
}

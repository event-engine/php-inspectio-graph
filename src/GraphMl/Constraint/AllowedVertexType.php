<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\GraphMl\Constraint;

use EventEngine\InspectioGraph\GraphMl\Constraint\Exception\WrongTypeException;
use Fhaculty\Graph;

final class AllowedVertexType implements Constraint
{
    /**
     * @var string[]
     **/
    private $allowedTypes;

    public function __construct(string ...$allowedTypes)
    {
        $this->allowedTypes = $allowedTypes;
    }

    public function __invoke(Graph\Vertex $vertex): void
    {
        if (false === \in_array($vertex->getAttribute('type'), $this->allowedTypes, true)) {
            throw WrongTypeException::wrongType($vertex, ...$this->allowedTypes);
        }
    }
}

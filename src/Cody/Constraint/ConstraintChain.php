<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody\Constraint;

use EventEngine\InspectioGraph\Cody\Node;

final class ConstraintChain implements Constraint
{
    /**
     * @var Constraint[]
     **/
    private $constraints;

    public function __construct(Constraint ...$constraints)
    {
        $this->constraints = $constraints;
    }

    public function __invoke(Node $vertex): void
    {
        foreach ($this->constraints as $constraint) {
            ($constraint)($vertex);
        }
    }
}

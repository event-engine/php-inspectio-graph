<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Constraint;

use EventEngine\InspectioGraph\Constraint\Exception\MissingAttributeException;
use Fhaculty\Graph;

final class MandatoryAttribute implements Constraint
{
    /**
     * @var string[]
     **/
    private $attributes;

    public function __construct(string ...$attributes)
    {
        $this->attributes = $attributes;
    }

    public function __invoke(Graph\Vertex $vertex): void
    {
        foreach ($this->attributes as $attribute) {
            if (null === $vertex->getAttribute($attribute)) {
                throw MissingAttributeException::missingAttribute($vertex, $attribute);
            }
        }
    }
}

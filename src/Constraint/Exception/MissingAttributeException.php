<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Constraint\Exception;

use Fhaculty\Graph\Vertex;

class MissingAttributeException extends RuntimeException
{
    public static function missingAttribute(Vertex $vertex, string $attribute): MissingAttributeException
    {
        return new self(
            \sprintf(
                'Vertex of type "%s" with name "%s" has not mandatory attribute "%s".',
                $vertex->getAttribute('label'),
                $vertex->getAttribute('type'),
                $attribute
            )
        );
    }
}

<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\GraphMl\Constraint\Exception;

use Fhaculty\Graph\Vertex;

class WrongAttributeTypeException extends RuntimeException
{
    public static function wrongType(Vertex $vertex, string $attribute, string $type): WrongAttributeTypeException
    {
        return new self(
            \sprintf(
                'Attribute "%s" has not the type "%s".',
                $vertex->getAttribute($attribute),
                $type
            )
        );
    }
}

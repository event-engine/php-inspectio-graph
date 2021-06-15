<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Connection;

use EventEngine\InspectioGraph\VertexType;

final class VertexConnection
{
    /**
     * @var VertexType
     */
    private $from;

    /**
     * @var VertexType
     */
    private $to;

    public function __construct(VertexType $from, VertexType $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function from(): VertexType
    {
        return $this->from;
    }

    public function to(): VertexType
    {
        return $this->to;
    }
}

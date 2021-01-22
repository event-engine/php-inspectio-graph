<?php

declare(strict_types=1);

namespace EventEngineTest\InspectioGraph;


use EventEngine\InspectioGraph\VertexMap;
use PHPUnit\Framework\TestCase;

final class VertexMapTest extends TestCase
{
    /**
     * @test
     */
    public function it_supports_empty(): void
    {
        $vertexMap = VertexMap::emptyMap();

        $this->assertCount(0, $vertexMap);
    }
}

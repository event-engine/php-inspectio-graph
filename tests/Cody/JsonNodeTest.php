<?php

declare(strict_types=1);

namespace EventEngineTest\InspectioGraph\Cody;

use EventEngine\InspectioGraph\Cody\JsonNode;
use EventEngine\InspectioGraph\Cody\Node;
use PHPUnit\Framework\TestCase;

final class JsonNodeTest extends TestCase
{
    private const FILES_DIR = __DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR;

    /**
     * @test
     */
    public function it_can_be_created_from_json(): void
    {
        $node = JsonNode::fromJson(file_get_contents(self::FILES_DIR . 'add_building.json'));

        $this->assertSame('o67B4pXhbDvuF2BnBsBy27', $node->id());
        $this->assertSame('Building', $node->name());
        $this->assertSame( 'aggregate', $node->type());
        $this->assertFalse($node->isLayer());
        $this->assertFalse($node->isDefaultLayer());
    }

    /**
     * @test
     */
    public function it_returns_parent_node(): void
    {
        $node = JsonNode::fromJson(file_get_contents(self::FILES_DIR . 'add_building.json'));

        $parent = $node->parent();
        $this->assertBoundedContextNode($parent);

        $parent = $parent->parent();
        $this->assertBoardLayerNode($parent);
    }

    /**
     * @test
     */
    public function it_returns_sources(): void
    {
        $node = JsonNode::fromJson(file_get_contents(self::FILES_DIR . 'add_building.json'));

        foreach ($node->sources() as $source) {
            $this->assertSame('i5hHo93xQP8cvhdTh25DHq', $source->id());
            $this->assertSame('Add Building', $source->name());
            $this->assertSame('command', $source->type());
            $this->assertFalse($source->isLayer());
            $this->assertFalse($source->isDefaultLayer());

            $parent = $node->parent();
            $this->assertBoundedContextNode($parent);

            $parent = $parent->parent();
            $this->assertBoardLayerNode($parent);
        }
    }

    /**
     * @test
     */
    public function it_returns_targets(): void
    {
        $node = JsonNode::fromJson(file_get_contents(self::FILES_DIR . 'add_building.json'));

        foreach ($node->targets() as $target) {
            $this->assertSame('ctuHbHKF1pwqQfa3VZ1nfz', $target->id());
            $this->assertSame('BuildingAdded', $target->name());
            $this->assertSame('event', $target->type());
            $this->assertFalse($target->isLayer());
            $this->assertFalse($target->isDefaultLayer());

            $parent = $node->parent();
            $this->assertBoundedContextNode($parent);

            $parent = $parent->parent();
            $this->assertBoardLayerNode($parent);
        }
    }

    private function assertBoundedContextNode(Node $node): void
    {
        $this->assertInstanceOf(Node::class, $node);
        $this->assertSame('ci42SWefUq5RhLogkWzhQC', $node->id());
        $this->assertSame('Building', $node->name());
        $this->assertSame('boundedContext', $node->type());
        $this->assertFalse($node->isLayer());
        $this->assertFalse($node->isDefaultLayer());
    }

    private function assertBoardLayerNode(Node $node): void
    {
        $this->assertInstanceOf(Node::class, $node);
        $this->assertSame('7fe80d19-d317-4e9d-8296-96c598786d78', $node->id());
        $this->assertSame('Board', $node->name());
        $this->assertSame('layer', $node->type());
        $this->assertTrue($node->isLayer());
        $this->assertTrue($node->isDefaultLayer());
    }
}

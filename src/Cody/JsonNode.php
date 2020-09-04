<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph\Cody;

final class JsonNode implements Node
{
    public const DEFAULT_DEPTH = 512;
    private const DEFAULT_OPTIONS = \JSON_BIGINT_AS_STRING | \JSON_THROW_ON_ERROR;

    /**
     * @var array
     **/
    private $node;

    public static function fromJson(string $json): Node
    {
        $data = \json_decode($json, true, self::DEFAULT_DEPTH, self::DEFAULT_OPTIONS);

        if (\is_string($data)) {
            $data = \json_decode($data, true, self::DEFAULT_DEPTH, self::DEFAULT_OPTIONS);
        }

        return new self($data['node']);
    }

    private function __construct(array $data)
    {
        $this->node = $data;
    }

    public function id(): string
    {
        return $this->node['id'] ?? '';
    }

    public function name(): string
    {
        return $this->node['name'] ?? '';
    }

    public function type(): string
    {
        return $this->node['type'] ?? '';
    }

    public function tags(): iterable
    {
        return $this->node['tags'] ?? [];
    }

    public function isLayer(): bool
    {
        return $this->node['layer'] ?? false;
    }

    public function isDefaultLayer(): bool
    {
        return $this->node['defaultLayer'] ?? false;
    }

    public function parent(): ?Node
    {
        return isset($this->node['parent']) ? new self($this->node['parent']) : null;
    }

    public function children(): iterable
    {
        $nodes = [];

        if (! empty($this->node['children'])) {
            foreach ($this->node['children'] as $child) {
                $nodes[] = new self($child);
            }
        }

        return $nodes;
    }

    public function sources(): iterable
    {
        $nodes = [];

        if (! empty($this->node['sourcesList'])) {
            foreach ($this->node['sourcesList'] as $child) {
                $nodes[] = new self($child);
            }
        }

        return $nodes;
    }

    public function targets(): iterable
    {
        $nodes = [];

        if (! empty($this->node['targetsList'])) {
            foreach ($this->node['targetsList'] as $child) {
                $nodes[] = new self($child);
            }
        }

        return $nodes;
    }

    public function metadata(): ?string
    {
        return $this->node['metadata'] ?? null;
    }
}

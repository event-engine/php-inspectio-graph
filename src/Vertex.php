<?php

/**
 * @see       https://github.com/event-engine/php-inspectio-graph for the canonical source repository
 * @copyright https://github.com/event-engine/php-inspectio-graph/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-inspectio-graph/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\InspectioGraph;

use EventEngine\InspectioGraph\Exception\RuntimeException;
use Fhaculty\Graph;

abstract class Vertex
{
    public const TYPE_AGGREGATE = 'aggregate';
    public const TYPE_EVENT = 'event';
    public const TYPE_COMMAND = 'command';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $name;

    public static function fromGraphVertex(
        Graph\Vertex $vertex,
        callable $filterName
    ): Vertex {
        $label = (string) $vertex->getAttribute('label');

        switch ($vertex->getAttribute('type')) {
            case Command::TYPE:
                $class = Command::class;
                break;
            case Aggregate::TYPE:
                $class = Aggregate::class;
                break;
            case Event::TYPE:
                $class = Event::class;
                break;
            default:
                throw new RuntimeException(\sprintf('Given type "%s" is not supported', $vertex->getAttribute('type')));
        }

        /** @var Vertex $self  */
        $self = new $class(
            $vertex->getId(),
            (string) $vertex->getAttribute('type'),
            $label,
            ($filterName)($label)
        );

        $self->init($vertex, $filterName);

        return $self;
    }

    protected function __construct(string $id, string $type, string $label, string $name)
    {
        if ($type !== static::TYPE) { // @phpstan-ignore-line
            // @phpstan-ignore-next-line
            throw new RuntimeException(\sprintf('Wrong vertex type "%s" provided. Vertex type must be "%s".', $type, static::TYPE));
        }

        $this->id = $id;
        $this->type = $type;
        $this->label = $label;
        $this->name = $name;
    }

    /**
     * Can be overridden to retrieve more vertex attributes
     *
     * @param Graph\Vertex $vertex
     * @param callable $filterName
     */
    protected function init(
        Graph\Vertex $vertex,
        callable $filterName
    ): void {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function type(): string
    {
        return $this->type;
    }

    /**
     * Raw vertex label
     *
     * @return string
     */
    public function label(): string
    {
        return $this->label;
    }

    /**
     * Filtered label for name
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }
}

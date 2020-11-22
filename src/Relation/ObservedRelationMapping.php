<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Relation;

use Stratadox\HydrationMapping\Mapping;
use Stratadox\Hydrator\HydrationObserver;

final class ObservedRelationMapping implements Mapping, HydrationObserver
{
    /** @var string */
    private $name;
    /** @var null|object */
    private $referenceTo;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function inProperty(string $name): self
    {
        return new self($name);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hydrating(object $theInstance, array $theData): void
    {
        $this->referenceTo = $theInstance;
    }

    public function value(array $data, $owner = null)
    {
        if (null === $this->referenceTo) {
            throw NoRelationObservationMade::forIn($this->name);
        }
        return $this->referenceTo;
    }
}
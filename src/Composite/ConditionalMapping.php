<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Composite;

use Stratadox\Hydration\Mapping\AssertKey;
use Stratadox\Hydration\Mapping\Composite\ConstraintNotSatisfied;
use Stratadox\HydrationMapping\Mapping;
use Stratadox\Specification\Contract\Satisfiable;

final class ConditionalMapping implements Mapping
{
    /** @var Satisfiable */
    private $condition;
    /** @var Mapping */
    private $napping;

    private function __construct(Satisfiable $condition, Mapping $napping)
    {
        $this->condition = $condition;
        $this->napping = $napping;
    }

    public static function ensureThat(
        Satisfiable $condition,
        Mapping $mapping
    ): self {
        return new self($condition, $mapping);
    }

    public function name(): string
    {
        return $this->napping->name();
    }

    public function value(array $data, $owner = null)
    {
        AssertKey::exists($this, $data, $this->name());
        if (!$this->condition->isSatisfiedBy($data[$this->name()])) {
            throw ConstraintNotSatisfied::with($data[$this->name()], $this->napping, $this->condition);
        }
        return $this->napping->value($data, $owner);
    }
}

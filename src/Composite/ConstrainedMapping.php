<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Composite;

use Stratadox\HydrationMapping\Mapping;
use Stratadox\HydrationMapping\MappingFailure;
use Stratadox\Specification\Contract\Satisfiable;

final class ConstrainedMapping implements Mapping
{
    /** @var Satisfiable */
    private $condition;
    /** @var Mapping */
    private $mapping;

    public function __construct(Satisfiable $condition, Mapping $mapping)
    {
        $this->condition = $condition;
        $this->mapping = $mapping;
    }

    public function name(): string
    {
        return $this->mapping->name();
    }

    public function value(array $data, $owner = null)
    {
        return $this->verify($this->mapping->value($data, $owner));
    }

    /**
     * @param mixed $result
     * @return mixed
     * @throws MappingFailure
     */
    private function verify($result)
    {
        if (!$this->condition->isSatisfiedBy($result)) {
            throw ConstraintNotSatisfied::with($result, $this->mapping, $this->condition);
        }
        return $result;
    }
}

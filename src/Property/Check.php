<?php
declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Property;

use Stratadox\HydrationMapping\Mapping;
use Stratadox\Specification\Contract\Satisfiable;

/**
 * Checks whether the input value is accepted by the constraints.
 *
 * @author Stratadox
 */
final class Check implements Mapping
{
    private $constraint;
    private $mapping;

    private function __construct(Satisfiable $constraint, Mapping $mapping)
    {
        $this->constraint = $constraint;
        $this->mapping = $mapping;
    }

    /**
     * Creates a check for on a property mapping.
     *
     * @param Satisfiable $constraint The constraint for the property.
     * @param Mapping     $mapping    The mapping for the property.
     * @return Mapping                The checked property mapping.
     */
    public static function thatIt(
        Satisfiable $constraint,
        Mapping $mapping
    ): Mapping {
        return new Check($constraint, $mapping);
    }

    /** @inheritdoc */
    public function name(): string
    {
        return $this->mapping->name();
    }

    /** @inheritdoc */
    public function value(array $data, $owner = null)
    {
        $value = $this->mapping->value($data, $owner);
        if ($this->constraint->isSatisfiedBy($value)) {
            return $value;
        }
        throw UnsatisfiedConstraint::itIsNotConsideredValid($this, $value);
    }
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Constraint;

use Stratadox\Specification\Specification;

class ItIsNotLess extends Specification
{
    private $minimum;

    private function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    public static function than(int $minimum): self
    {
        return new self($minimum);
    }

    public function isSatisfiedBy($number): bool
    {
        return $number >= $this->minimum;
    }
}

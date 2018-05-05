<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Constraint;

use Stratadox\Specification\Specification;

class ItIsNotMore extends Specification
{
    private $maximum;

    private function __construct(int $maximum)
    {
        $this->maximum = $maximum;
    }

    public static function than(int $maximum): self
    {
        return new self($maximum);
    }

    public function isSatisfiedBy($number): bool
    {
        return $number <= $this->maximum;
    }
}

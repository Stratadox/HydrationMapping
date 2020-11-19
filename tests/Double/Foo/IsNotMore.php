<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Foo;

use Stratadox\Specification\Contract\Specifies;
use Stratadox\Specification\Specifying;
use function is_numeric;

class IsNotMore implements Specifies
{
    use Specifying;

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
        return is_numeric($number) && $number <= $this->maximum;
    }
}

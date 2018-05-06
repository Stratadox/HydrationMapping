<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Constraint;

use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\Specification\Specification;
use function strlen;

class HasLongerLastName extends Specification
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

    public function isSatisfiedBy($person): bool
    {
        return $person instanceof Person
            && strlen($person->lastName()) > $this->minimum;
    }
}

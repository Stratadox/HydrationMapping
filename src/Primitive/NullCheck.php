<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

use Stratadox\Specification\Contract\Satisfiable;

final class NullCheck implements Satisfiable
{
    public static function passes(): self
    {
        return new self();
    }

    public function isSatisfiedBy($value): bool
    {
        return null === $value;
    }
}

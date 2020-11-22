<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

use Stratadox\Specification\Contract\Satisfiable;
use function is_numeric;

final class FloatCheck implements Satisfiable
{
    public static function passes(): self
    {
        return new self();
    }

    public function isSatisfiedBy($value): bool
    {
        return is_numeric($value);
    }
}

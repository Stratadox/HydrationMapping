<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

use Stratadox\Specification\Contract\Satisfiable;
use function is_int;
use function is_string;
use function preg_match;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

final class IntegerCheck implements Satisfiable
{
    public static function passes(): self
    {
        return new self();
    }

    public function isSatisfiedBy($value): bool
    {
        return is_int($value) || (
            is_string($value) &&
            preg_match('/^[-+]?\d+$/', $value) &&
            $value >= (string) PHP_INT_MIN &&
            $value <= (string) PHP_INT_MAX
        );
    }
}

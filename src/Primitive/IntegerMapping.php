<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

final class IntegerMapping extends PrimitiveMapping
{
    public function value(array $data, $owner = null): int
    {
        return (int) $this->my($data);
    }
}

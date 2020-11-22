<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

final class FloatMapping extends PrimitiveMapping
{
    public function value(array $data, $owner = null): float
    {
        return (float) $this->my($data);
    }
}

<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

final class NullMapping extends PrimitiveMapping
{
    public function value(array $data, $owner = null)
    {
        return null;
    }
}

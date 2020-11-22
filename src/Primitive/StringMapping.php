<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

final class StringMapping extends PrimitiveMapping
{
    public function value(array $data, $owner = null): string
    {
        return (string) $this->my($data);
    }
}

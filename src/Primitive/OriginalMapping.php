<?php declare(strict_types=1);

namespace Stratadox\Hydration\Mapping\Primitive;

final class OriginalMapping extends PrimitiveMapping
{
    public function value(array $data, $owner = null)
    {
        return $this->my($data);
    }
}

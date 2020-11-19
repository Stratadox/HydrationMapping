<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Thing;

final class Thing
{
    /** @var Name */
    private $name;

    public function name(): Name
    {
        return $this->name;
    }
}

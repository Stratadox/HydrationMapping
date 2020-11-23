<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\ItemList;

final class Item
{
    /** @var string */
    private $name;

    public function name(): string
    {
        return $this->name;
    }
}

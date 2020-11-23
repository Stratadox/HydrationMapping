<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\ItemList;

final class Box
{
    /** @var Item[] */
    private $items;

    /** @return Item[] */
    public function items(): array
    {
        return $this->items;
    }
}

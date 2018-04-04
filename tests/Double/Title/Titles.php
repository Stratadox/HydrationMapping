<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Title;

use Stratadox\ImmutableCollection\ImmutableCollection;

class Titles extends ImmutableCollection
{
    public function __construct(Title ...$titles)
    {
        parent::__construct(...$titles);
    }

    public function current(): Title
    {
        return parent::current();
    }
}

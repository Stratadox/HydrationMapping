<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Title;

class Title
{
    public $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }
}

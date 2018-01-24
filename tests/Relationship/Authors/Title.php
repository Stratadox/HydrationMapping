<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Authors;

class Title
{
    public $title;

    public function __construct(string $title)
    {
        $this->title = $title;
    }

    public function get() : string
    {
        return $this->title;
    }
}

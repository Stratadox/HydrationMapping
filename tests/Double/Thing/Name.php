<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Thing;

class Name
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

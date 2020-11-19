<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Foo;

final class Bar
{
    /** @var Foo */
    private $foo;

    public function integer(): int
    {
        return $this->foo->integer();
    }

    public function string(): string
    {
        return $this->foo->string();
    }

    public function boolean(): bool
    {
        return $this->foo->boolean();
    }

    public function float(): float
    {
        return $this->foo->float();
    }

    public function null()
    {
        return $this->foo->null();
    }

    /** @return mixed */
    public function mixed()
    {
        return $this->foo->mixed();
    }
}

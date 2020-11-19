<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Foo;

final class Foo
{
    /** @var integer */
    private $integer;
    /** @var string */
    private $string;
    /** @var boolean */
    private $boolean;
    /** @var float */
    private $float;
    /** @var null */
    private $null;
    /** @var mixed */
    private $mixed;

    public function integer(): int
    {
        return $this->integer;
    }

    public function string(): string
    {
        return $this->string;
    }

    public function boolean(): bool
    {
        return $this->boolean;
    }

    public function float(): float
    {
        return $this->float;
    }

    public function null()
    {
        return $this->null;
    }

    /** @return mixed */
    public function mixed()
    {
        return $this->mixed;
    }
}

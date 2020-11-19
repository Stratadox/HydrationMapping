<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Foo;

final class Nullable
{
    /** @var integer|null */
    private $integer;
    /** @var string|null */
    private $string;
    /** @var boolean|null */
    private $boolean;
    /** @var float|null */
    private $float;

    public function integer(): ?int
    {
        return $this->integer;
    }

    public function string(): ?string
    {
        return $this->string;
    }

    public function boolean(): ?bool
    {
        return $this->boolean;
    }

    public function float(): ?float
    {
        return $this->float;
    }
}

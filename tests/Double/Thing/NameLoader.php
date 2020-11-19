<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Thing;

use Stratadox\Proxy\ProxyLoader;

final class NameLoader implements ProxyLoader
{
    /** @var string */
    private $name;
    /** @var bool */
    private $didLoad = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function loadingAs(string $name): self
    {
        return new self($name);
    }

    public function loadTheInstance(array $data): object
    {
        $this->didLoad = true;
        return new Name($this->name);
    }

    public function didLoad(): bool
    {
        return $this->didLoad;
    }
}

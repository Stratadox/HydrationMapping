<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Thing;

use Stratadox\Proxy\Proxy;
use Stratadox\Proxy\Proxying;

final class NameProxy extends Name implements Proxy
{
    use Proxying;

    public function __toString(): string
    {
        return $this->_load()->__toString();
    }
}

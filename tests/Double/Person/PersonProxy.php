<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Person;

use Stratadox\Proxy\Proxy;
use Stratadox\Proxy\Proxying;

class PersonProxy extends Person implements Proxy
{
    use Proxying;

    public function firstName(): string
    {
        return $this->_load()->firstName();
    }

    public function lastName(): string
    {
        return $this->_load()->lastName();
    }
}

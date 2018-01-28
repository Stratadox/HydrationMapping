<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Person;

use Stratadox\Hydration\Proxy;

class PersonProxy extends Person implements Proxy
{
    private $proxyFor;
    private $owner;
    private $property;
    private $position;

    public function __construct($owner, string $property, ?int $position)
    {
        parent::__construct('', '');
        $this->owner = $owner;
        $this->property = $property;
        $this->position = $position;
    }

    public function firstName() : string
    {
        return $this->__load()->firstName();
    }

    public function lastName() : string
    {
        return $this->__load()->lastName();
    }

    public function __load() : Person
    {
        if (!isset($this->proxyFor)) {
            $this->proxyFor = new Person('Lazy loading', 'Is out of scope');
        }
        return $this->proxyFor;
    }

    public function property() : string
    {
        return $this->property;
    }

    public function position() : ?int
    {
        return $this->position;
    }

    public function owner()
    {
        return $this->owner;
    }
}

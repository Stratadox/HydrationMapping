<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Authors;

use Stratadox\Hydration\Proxy;

class AuthorProxy extends Author implements Proxy
{
    private $proxyFor;
    private $owner;
    private $property;
    private $position;

    public function __construct($owner, string $property, int $position)
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

    public function __load() : Author
    {
        if (!isset($this->proxyFor)) {
            $this->proxyFor = new Author('Lazy loading', 'Is out of scope');
        }
        return $this->proxyFor;
    }

    public function property() : string
    {
        return $this->property;
    }

    public function position()
    {
        return $this->position;
    }

    public function owner()
    {
        return $this->owner;
    }
}

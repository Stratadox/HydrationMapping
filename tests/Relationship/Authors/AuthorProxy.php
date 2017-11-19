<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Authors;

class AuthorProxy extends Author
{
    private $proxyFor;
    private $property;
    private $position;

    public function __construct($owner, $property, $position)
    {
        parent::__construct('', '');
        $this->property = $property;
        $this->position = $position;
    }

    public function firstName() : string
    {
        return $this->load()->firstName();
    }

    public function lastName() : string
    {
        return $this->load()->lastName();
    }

    private function load() : Author
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
}

<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Authors;

class AuthorProxy extends Author
{
    private $proxyFor;

    public function __construct()
    {
        parent::__construct('', '');
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
}

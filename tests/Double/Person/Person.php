<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Person;

class Person
{
    public $firstName;
    public $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Person;

use Stratadox\ImmutableCollection\ImmutableCollection;

class Persons extends ImmutableCollection
{
    public function __construct(Person ...$people)
    {
        parent::__construct(...$people);
    }

    public function current(): Person
    {
        return parent::current();
    }
}

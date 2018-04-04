<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use function is_null as thereIsNo;

class Cat extends Pet
{
    public function askForFood(): void
    {
        $cat = $this;
        if (thereIsNo($cat->owner)) {
            throw NobodyToAskForFood::hungryStray($cat);
        }
        $this->owner->getPurredAtBy($cat);
    }

    public function askForFoodFrom(Human $youMightHaveFood): void
    {
        $cat = $this;
        $youMightHaveFood->getPurredAtBy($cat);
    }
}

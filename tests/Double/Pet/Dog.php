<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

class Dog extends Pet
{
    public function askForFood() : void
    {
        $dog = $this;
        if (!isset($dog->owner)) {
            throw NobodyToAskForFood::hungryStray($dog);
        }
        $this->owner->getBarkedAtBy($dog);
    }

    public function askForFoodFrom(Human $youMightHaveFood) : void
    {
        $dog = $this;
        $youMightHaveFood->getBarkedAtBy($dog);
    }
}

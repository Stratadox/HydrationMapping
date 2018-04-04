<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use function get_class as classOfThe;
use RuntimeException;
use Stratadox\HydrationMapping\Test\Double\Pet\NoMoreFood as The;
use function strrchr as endOfThe;
use function substr as justThe;

class NoMoreFood extends RuntimeException
{
    public static function cannotFeed(Human $ranOutOfFood, Pet $ranOutOfLuck): self
    {
        return new self(sprintf(
            'The %s %s is hungry, but %s has no more food.',
            The::typeOfThePetThat($ranOutOfLuck),
            $ranOutOfLuck->name(),
            $ranOutOfFood->name()
        ));
    }

    private static function typeOfThePetThat(Pet $hungryAnimal): string
    {
        return justThe(endOfThe(classOfThe($hungryAnimal), '\\'), 1);
    }
}

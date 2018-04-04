<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use function get_class as classOfThePoorThingIt;
use RuntimeException;
use function strrchr as endOfThe;
use function substr as justThe;

class NobodyToAskForFood extends RuntimeException
{
    public static function hungryStray(Pet $isHungry): self
    {
        return new self(sprintf(
            'The %s has the munchies, but no owner to nag.',
            justThe(endOfThe(classOfThePoorThingIt($isHungry), '\\'), 1)
        ));
    }
}

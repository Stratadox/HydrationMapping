<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use ReflectionClass;
use RuntimeException;

class NobodyToAskForFood extends RuntimeException
{
    public static function hungryStray(Pet $isHungry) : self
    {
        return new self(sprintf(
            'The %s has the munchies, but no owner to nag.',
            (new ReflectionClass($isHungry))->getShortName()
        ));
    }
}

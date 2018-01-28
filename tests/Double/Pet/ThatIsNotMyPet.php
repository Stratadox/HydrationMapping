<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use ReflectionClass;
use RuntimeException;

class ThatIsNotMyPet extends RuntimeException
{
    public static function notFeeding(Human $hasNoFoodForThat, Pet $itIsNotMine) : self
    {
        return new self(sprintf(
            'The %s %s is hungry, but %s does not want to feed it.',
            (new ReflectionClass($itIsNotMine))->getShortName(),
            $itIsNotMine->name(),
            $hasNoFoodForThat->name()
        ));
    }

    public static function cannotAbandon(Human $neverHadIt, Pet $notMine) : self
    {
        return new self(sprintf(
            "The %s %s can't be abandoned by %s because %s didn't own it.",
            (new ReflectionClass($notMine))->getShortName(),
            $notMine->name(),
            $neverHadIt->name(),
            $neverHadIt->name()
        ));
    }
}

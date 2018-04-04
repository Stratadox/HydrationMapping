<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use ReflectionClass;
use RuntimeException;

class NoMoreFood extends RuntimeException
{
    public static function cannotFeed(Human $ranOutOfFood, Pet $ranOutOfLuck): self
    {
        return new self(sprintf(
            'The %s %s is hungry, but %s has no more food.',
            (new ReflectionClass($ranOutOfLuck))->getShortName(),
            $ranOutOfLuck->name(),
            $ranOutOfFood->name()
        ));
    }
}

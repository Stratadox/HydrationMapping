<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use function get_class as classOfWhateverItIs;
use RuntimeException;
use function strrchr as endOfThe;
use function substr as justThe;

class ThatIsNotMyPet extends RuntimeException
{
    public static function notFeeding(Human $hasNoFoodForThat, Pet $itIsNotMine): self
    {
        return new self(sprintf(
            'The %s %s is hungry, but %s does not want to feed it.',
            justThe(endOfThe(classOfWhateverItIs($itIsNotMine), '\\'), 1),
            $itIsNotMine->name(),
            $hasNoFoodForThat->name()
        ));
    }

    public static function cannotAbandon(Human $neverHadIt, Pet $notMine): self
    {
        return new self(sprintf(
            "The %s %s can't be abandoned by %s because %s didn't own it.",
            justThe(endOfThe(classOfWhateverItIs($notMine), '\\'), 1),
            $notMine->name(),
            $neverHadIt->name(),
            $neverHadIt->name()
        ));
    }
}

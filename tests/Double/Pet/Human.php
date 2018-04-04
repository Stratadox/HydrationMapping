<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

use function in_array;

class Human
{
    private $name;
    private $pets;
    private $food;
    private $hasBeenBarkedAt = false;
    private $hasBeenPurredAt = false;

    public function __construct(string $name, Pet ...$pets)
    {
        $this->name = $name;
        $this->pets = $pets;
        $this->food = 0;
    }

    public function getFood(int $howMuch): void
    {
        $this->food += $howMuch;
    }

    public function hasPetFood(): bool
    {
        return $this->food > 0;
    }

    public function hasNoMorePetFood(): bool
    {
        return !$this->hasPetFood();
    }

    /**
     * Adopts a new pet.
     *
     * @param Pet $animal
     * @param string|null $itsNewName
     * @throws ThatIsNotMyPet
     */
    public function getANew(Pet $animal, string $itsNewName = null): void
    {
        $animal->getTakenCareOfBy($this);
        $animal->nameIt($itsNewName ?: $animal->name());
    }

    /**
     * Does something abandon
     *
     * @param Pet $animal
     * @throws ThatIsNotMyPet
     */
    public function abandon(Pet $animal): void
    {
        if ($this->isNotMy($animal)) {
            throw ThatIsNotMyPet::cannotAbandon($this, $animal);
        }
        $animal->getAbandonedBy($this);
        foreach ($this->pets as $i => $pet) {
            if ($pet === $animal) {
                unset($this->pets[$i]);
            }
        }
    }

    /** @return Pet[] */
    public function pets(): array
    {
        return $this->pets;
    }

    public function pet(int $index): Pet
    {
        return $this->pets[$index];
    }

    /**
     * Notices the sound of a barking dog.
     *
     * @param Pet $barkingPet
     * @throws NoMoreFood
     * @throws ThatIsNotMyPet
     */
    public function getBarkedAtBy(Pet $barkingPet): void
    {
        $this->hasBeenBarkedAt = true;
        if ($barkingPet->isHungry()) {
            $this->feedThe($barkingPet);
        }
    }

    /**
     * Notices the purring of a nearby cat.
     *
     * @param Pet $purringPet
     * @throws NoMoreFood
     * @throws ThatIsNotMyPet
     */
    public function getPurredAtBy(Pet $purringPet): void
    {
        $this->hasBeenPurredAt = true;
        if ($purringPet->isHungry()) {
            $this->feedThe($purringPet);
        }
    }

    /**
     * Feeds the pet.
     *
     * @param Pet $hungryPet
     * @throws NoMoreFood
     * @throws ThatIsNotMyPet
     */
    public function feedThe(Pet $hungryPet): void
    {
        if ($this->isNotMy($hungryPet)) {
            throw ThatIsNotMyPet::notFeeding($this, $hungryPet);
        }
        if ($this->hasNoMorePetFood()) {
            throw NoMoreFood::cannotFeed($this, $hungryPet);
        }
        $hungryPet->feedIt();
        $this->food--;
    }

    public function hasHungryPets(): bool
    {
        foreach ($this->pets as $pet) {
            if ($pet->isHungry()) {
                return true;
            }
        }
        return false;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function nameOfPet(int $index): string
    {
        return $this->pets[$index]->name();
    }

    public function petIsHungry(int $index): bool
    {
        return $this->pets[$index]->isHungry();
    }

    private function isMy(Pet $toTakeCareOf): bool
    {
        return in_array($toTakeCareOf, $this->pets, true);
    }

    private function isNotMy(Pet $toTakeCareOf): bool
    {
        return !$this->isMy($toTakeCareOf);
    }

    public function hasBeenBarkedAt(): bool
    {
        return $this->hasBeenBarkedAt;
    }

    public function hasBeenPurredAt(): bool
    {
        return $this->hasBeenPurredAt;
    }
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double\Pet;

abstract class Pet
{
    private const HAS_NO_NAME = '(unnamed pet)';

    protected $hungry;
    protected $owner;
    protected $name;

    public function __construct(bool $hungry, ?Human $owner, ?string $name)
    {
        $this->hungry = $hungry;
        $this->owner = $owner;
        $this->name = $name;
    }

    /**
     * Acknowledges the new caretaker.
     *
     * @param Human $petOwner
     * @throws ThatIsNotMyPet
     */
    public function getTakenCareOfBy(Human $petOwner): void
    {
        if (isset($this->owner)) {
            $this->owner->abandon($this);
        }
        $this->owner = $petOwner;
    }

    public function getAbandonedBy(Human $petOwner): void
    {
        $this->owner = $petOwner;
    }

    public function owner(): ?Human
    {
        return $this->owner;
    }

    public function nameIt(?string $name): void
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name ?: Pet::HAS_NO_NAME;
    }

    public function isHungry(): bool
    {
        return $this->hungry;
    }

    public function feedIt(): void
    {
        $this->hungry = false;
    }

    public function becomeHungry(): void
    {
        $this->hungry = true;
    }

    /**
     * Draws the attention of this pet's owner.
     *
     * @throws NoMoreFood
     * @throws NobodyToAskForFood
     * @throws ThatIsNotMyPet
     */
    abstract public function askForFood(): void;

    /**
     * Draws the attention of a human.
     *
     * @param Human $youMightHaveFood
     * @throws NoMoreFood
     * @throws ThatIsNotMyPet
     */
    abstract public function askForFoodFrom(Human $youMightHaveFood): void;
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Feature;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\Deserializer;
use Stratadox\HydrationMapping\Test\Double\Pet\Cat;
use Stratadox\HydrationMapping\Test\Double\Pet\Dog;
use Stratadox\HydrationMapping\Test\Double\Pet\Human;
use Stratadox\HydrationMapping\Test\Double\Pet\NoMoreFood;
use Stratadox\HydrationMapping\Test\Double\Pet\ThatIsNotMyPet;
use Stratadox\HydrationMapping\Test\Feature\Data\PetsWithOwners;

class Loading_pets_and_their_owners_from_various_structures extends TestCase
{
    use PetsWithOwners;

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function the_humans_have_names(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);
        $bob = $this->instantiate($petOwner, 2, $fromData);
        $charlie = $this->instantiate($petOwner, 3, $fromData);

        self::assertSame('Alice', $alice->name());
        self::assertSame('Bob', $bob->name());
        self::assertSame('Charlie', $charlie->name());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function charlie_has_no_pet_food(Deserializer $petOwner, array $fromData)
    {
        $charlie = $this->instantiate($petOwner, 3, $fromData);

        self::assertFalse($charlie->hasPetFood());
        self::assertTrue($charlie->hasNoMorePetFood());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function charlie_has_no_pets(Deserializer $petOwner, array $fromData)
    {
        $charlie = $this->instantiate($petOwner, 3, $fromData);

        self::assertCount(0, $charlie->pets());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function alice_and_bob_have_pets(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);
        $bob = $this->instantiate($petOwner, 2, $fromData);

        self::assertCount(1, $alice->pets());
        self::assertCount(2, $bob->pets());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function alice_has_pet_food(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);

        self::assertTrue($alice->hasPetFood());
        self::assertFalse($alice->hasNoMorePetFood());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function alice_has_a_cat_named_Foo(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);

        self::assertInstanceOf(Cat::class, $alice->pet(0));
        self::assertSame('Foo', $alice->nameOfPet(0));
        self::assertFalse($alice->petIsHungry(0));
        self::assertFalse($alice->hasHungryPets());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function foo_is_the_cat_of_alice(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);
        $foo = $alice->pet(0);

        self::assertSame($alice, $foo->owner());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function bob_has_a_hungry_cat_named_Bar(Deserializer $petOwner, array $fromData)
    {
        $bob = $this->instantiate($petOwner, 2, $fromData);

        self::assertInstanceOf(Cat::class, $bob->pet(0));
        self::assertSame('Bar', $bob->nameOfPet(0));
        self::assertTrue($bob->petIsHungry(0));
        self::assertTrue($bob->hasHungryPets());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function bob_also_has_a_dog_named_Baz(Deserializer $petOwner, array $fromData)
    {
        $bob = $this->instantiate($petOwner, 2, $fromData);

        self::assertInstanceOf(Dog::class, $bob->pet(1));
        self::assertSame('Baz', $bob->nameOfPet(1));
        self::assertFalse($bob->petIsHungry(1));
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function bob_has_pet_food(Deserializer $petOwner, array $fromData)
    {
        $bob = $this->instantiate($petOwner, 2, $fromData);

        self::assertTrue($bob->hasPetFood());
        self::assertFalse($bob->hasNoMorePetFood());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function bob_feeds_his_cat(Deserializer $petOwner, array $fromData)
    {
        $bob = $this->instantiate($petOwner, 2, $fromData);
        $bar = $bob->pet(0);

        $bar->askForFood();

        self::assertFalse($bob->hasPetFood());
        self::assertTrue($bob->hasNoMorePetFood());
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function bob_tries_to_feed_his_cat_twice_but_is_short_on_food(Deserializer $petOwner, array $fromData)
    {
        $bob = $this->instantiate($petOwner, 2, $fromData);
        $bar = $bob->pet(0);

        $bar->askForFood();
        $bar->becomeHungry();

        $this->expectException(NoMoreFood::class);
        $this->expectExceptionMessage(
            'The Cat Bar is hungry, but Bob has no more food.'
        );

        $bar->askForFood();
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function alice_does_not_feed_bobs_cat(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);
        $bob = $this->instantiate($petOwner, 2, $fromData);

        $bar = $bob->pet(0);

        $this->expectException(ThatIsNotMyPet::class);
        $this->expectExceptionMessage(
            'The Cat Bar is hungry, but Alice does not want to feed it.'
        );

        $bar->askForFoodFrom($alice);
    }

    /**
     * @test
     * @dataProvider petsWithOwners
     */
    function alice_takes_care_of_bobs_cat(Deserializer $petOwner, array $fromData)
    {
        $alice = $this->instantiate($petOwner, 1, $fromData);
        $bob = $this->instantiate($petOwner, 2, $fromData);

        $cat = $bob->pet(0);

        $alice->getANew($cat);

        $this->expectException(ThatIsNotMyPet::class);
        $this->expectExceptionMessage(
            "The Cat Bar can't be abandoned by Bob because Bob didn't own it."
        );

        $bob->abandon($cat);
    }

    private function instantiate(
        Deserializer $petOwner,
        int $number,
        array $dataSet
    ): Human {
        return $petOwner->from($dataSet[$number - 1]);
    }
}

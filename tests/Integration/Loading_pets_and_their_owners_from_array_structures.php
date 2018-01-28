<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Integration;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Hydrator\ArrayHydrator;
use Stratadox\Hydration\Hydrator\MappedHydrator;
use Stratadox\Hydration\Hydrator\OneOfTheseHydrators;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CustomTruths;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapping\Test\Double\Pet\Cat;
use Stratadox\HydrationMapping\Test\Double\Pet\Dog;
use Stratadox\HydrationMapping\Test\Double\Pet\Human;
use Stratadox\HydrationMapping\Test\Double\Pet\NoMoreFood;
use Stratadox\HydrationMapping\Test\Double\Pet\ThatIsNotMyPet;

/**
 * @coversNothing
 */
class Loading_pets_and_their_owners_from_array_structures extends TestCase
{
    /** @var Human[] */
    private $petOwners;

    protected function setUp()
    {
        $hydrator = $this->mappedHydrator();

        $this->petOwners = [];
        foreach ($this->dataOnPetOwners() as $petOwnerData) {
            $this->petOwners[] = $hydrator->fromArray($petOwnerData);
        }
    }

    /** @return array[] */
    private function dataOnPetOwners() : array
    {
        return [
            [
                'name' => 'Alice',
                'food' => '3',
                'pets' => [
                    [
                        'species' => 'cat',
                        'hungry' => 'no',
                        'name' => 'Foo',
                    ],
                ],
            ],
            [
                'name' => 'Bob',
                'food' => '1',
                'pets' => [
                    [
                        'species' => 'cat',
                        'hungry' => 'yeah',
                        'name' => 'Bar',
                    ],
                    [
                        'species' => 'dog',
                        'hungry' => 'nah',
                        'name' => 'Baz',
                    ],
                ],
            ],
            [
                'name' => 'Charlie',
                'food' => '0',
                'pets' => [],
            ],
        ];
    }

    private function mappedHydrator() : Hydrates
    {
        $backReference = HasBackReference::inProperty('owner');
        $petMappings = Properties::map(
            CustomTruths::forThe(BooleanValue::inProperty('hungry'),
                ['yes', 'yeah'],
                ['no', 'nah']
            ),
            $backReference,
            StringValue::inProperty('name')
        );
        $hydrator = MappedHydrator::forThe(Human::class, Properties::map(
            StringValue::inProperty('name'),
            IntegerValue::inProperty('food'),
            HasManyNested::inProperty('pets',
                ArrayHydrator::create(),
                OneOfTheseHydrators::decideBasedOnThe('species', [
                    'cat' => MappedHydrator::forThe(Cat::class, $petMappings),
                    'dog' => MappedHydrator::forThe(Dog::class, $petMappings),
                ])
            )
        ));
        $backReference->setSource($hydrator);
        return $hydrator;
    }

    /** @scenario */
    function the_humans_have_names()
    {
        $alice = $this->petOwners[0];
        $bob = $this->petOwners[1];
        $charlie = $this->petOwners[2];

        self::assertSame('Alice', $alice->name());
        self::assertSame('Bob', $bob->name());
        self::assertSame('Charlie', $charlie->name());
    }

    /** @scenario */
    function charlie_has_no_pet_food()
    {
        $charlie = $this->petOwners[2];

        self::assertFalse($charlie->hasPetFood());
        self::assertTrue($charlie->hasNoMorePetFood());
    }

    /** @scenario */
    function charlie_has_no_pets()
    {
        $charlie = $this->petOwners[2];

        self::assertCount(0, $charlie->pets());
    }

    /** @scenario */
    function alice_and_bob_have_pets()
    {
        $alice = $this->petOwners[0];
        $bob = $this->petOwners[1];

        self::assertCount(1, $alice->pets());
        self::assertCount(2, $bob->pets());
    }

    /** @scenario */
    function alice_has_pet_food()
    {
        $alice = $this->petOwners[0];

        self::assertTrue($alice->hasPetFood());
        self::assertFalse($alice->hasNoMorePetFood());
    }

    /** @scenario */
    function alice_has_a_cat_named_Foo()
    {
        $alice = $this->petOwners[0];

        self::assertInstanceOf(Cat::class, $alice->pet(0));
        self::assertSame('Foo', $alice->nameOfPet(0));
        self::assertFalse($alice->petIsHungry(0));
        self::assertFalse($alice->hasHungryPets());
    }

    /** @scenario */
    function foo_is_the_cat_of_alice()
    {
        $alice = $this->petOwners[0];
        $foo = $alice->pet(0);

        self::assertSame($alice, $foo->owner());
    }

    /** @scenario */
    function bob_has_a_hungry_cat_named_Bar()
    {
        $bob = $this->petOwners[1];

        self::assertInstanceOf(Cat::class, $bob->pet(0));
        self::assertSame('Bar', $bob->nameOfPet(0));
        self::assertTrue($bob->petIsHungry(0));
        self::assertTrue($bob->hasHungryPets());
    }

    /** @scenario */
    function bob_also_has_a_dog_named_Baz()
    {
        $bob = $this->petOwners[1];

        self::assertInstanceOf(Dog::class, $bob->pet(1));
        self::assertSame('Baz', $bob->nameOfPet(1));
        self::assertFalse($bob->petIsHungry(1));
    }

    /** @scenario */
    function bob_has_pet_food()
    {
        $bob = $this->petOwners[1];

        self::assertTrue($bob->hasPetFood());
        self::assertFalse($bob->hasNoMorePetFood());
    }

    /** @scenario */
    function bob_feeds_his_cat()
    {
        $bob = $this->petOwners[1];
        $bar = $bob->pet(0);

        $bar->askForFood();

        self::assertFalse($bob->hasPetFood());
        self::assertTrue($bob->hasNoMorePetFood());
    }

    /** @scenario */
    function bob_tries_to_feed_his_cat_twice_but_is_short_on_food()
    {
        $bob = $this->petOwners[1];
        $bar = $bob->pet(0);

        $bar->askForFood();
        $bar->becomeHungry();

        $this->expectException(NoMoreFood::class);
        $this->expectExceptionMessage(
            'The Cat Bar is hungry, but Bob has no more food.'
        );

        $bar->askForFood();
    }

    /** @scenario */
    function alice_does_not_feed_bobs_cat()
    {
        $alice = $this->petOwners[0];
        $bob = $this->petOwners[1];

        $bar = $bob->pet(0);

        $this->expectException(ThatIsNotMyPet::class);
        $this->expectExceptionMessage(
            'The Cat Bar is hungry, but Alice does not want to feed it.'
        );

        $bar->askForFoodFrom($alice);
    }

    /** @scenario */
    function alice_takes_care_of_bobs_cat()
    {
        $alice = $this->petOwners[0];
        $bob = $this->petOwners[1];

        $cat = $bob->pet(0);

        $alice->getANew($cat);

        $this->expectException(ThatIsNotMyPet::class);
        $this->expectExceptionMessage(
            "The Cat Bar can't be abandoned by Bob because Bob didn't own it."
        );

        $bob->abandon($cat);
    }
}

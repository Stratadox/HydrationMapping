<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Integration;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\ArrayDeserializer;
use Stratadox\Deserializer\Condition\HaveTheDiscriminatorValue;
use Stratadox\Deserializer\ForDataSets;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Deserializer\OneOfThese;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Pet\Cat;
use Stratadox\HydrationMapping\Test\Double\Pet\Dog;
use Stratadox\HydrationMapping\Test\Double\Pet\Human;
use Stratadox\HydrationMapping\Test\Double\Pet\NoMoreFood;
use Stratadox\HydrationMapping\Test\Double\Pet\ThatIsNotMyPet;
use Stratadox\Hydrator\Hydrates;
use Stratadox\Hydrator\Mapping;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Hydrator\ObserveBefore;
use Stratadox\Instantiator\Instantiator;

/**
 * @coversNothing
 */
class Loading_pets_and_their_owners_from_array_structures extends TestCase
{
    /** @var Human[] */
    private $petOwners;

    protected function setUp()
    {
        $deserializer = ObjectDeserializer::using(
            Instantiator::forThe(Human::class),
            $this->mappedHydrator()
        );

        $this->petOwners = [];
        foreach ($this->dataOnPetOwners() as $petOwnerData) {
            $this->petOwners[] = $deserializer->from($petOwnerData);
        }
    }

    /** @return array[] */
    private function dataOnPetOwners(): array
    {
        return [
            [
                'name' => 'Alice',
                'food' => '3',
                'pets' => [
                    [
                        'species' => 'cat',
                        'hungry'  => 'no',
                        'name'    => 'Foo',
                    ],
                ],
            ],
            [
                'name' => 'Bob',
                'food' => '1',
                'pets' => [
                    [
                        'species' => 'cat',
                        'hungry'  => 'yeah',
                        'name'    => 'Bar',
                    ],
                    [
                        'species' => 'dog',
                        'hungry'  => 'nah',
                        'name'    => 'Baz',
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

    private function mappedHydrator(): Hydrates
    {
        $backReference = HasBackReference::inProperty('owner');
        $petMappings = Mapping::for(ObjectHydrator::default(), Properties::map(
            BooleanValue::inProperty('hungry', [
                'yes',
                'yeah'
            ], [
                'no',
                'nah'
            ]),
            $backReference,
            StringValue::inProperty('name')
        ));
        $hydrator = ObserveBefore::hydrating(
            Mapping::for(ObjectHydrator::default(), Properties::map(
                StringValue::inProperty('name'),
                IntegerValue::inProperty('food'),
                HasManyNested::inProperty('pets',
                    ArrayDeserializer::make(),
                    OneOfThese::deserializers(
                        ForDataSets::that(
                            HaveTheDiscriminatorValue::of('species', 'cat'),
                            ObjectDeserializer::using(
                                Instantiator::forThe(Cat::class),
                                $petMappings
                            )
                        ),
                        ForDataSets::that(
                            HaveTheDiscriminatorValue::of('species', 'dog'),
                            ObjectDeserializer::using(
                                Instantiator::forThe(Dog::class),
                                $petMappings
                            )
                        )
                    )
                )
            )),
            $backReference
        );
        return $hydrator;
    }

    /** @test */
    function the_humans_have_names()
    {
        [$alice, $bob, $charlie] = $this->petOwners;

        self::assertSame('Alice', $alice->name());
        self::assertSame('Bob', $bob->name());
        self::assertSame('Charlie', $charlie->name());
    }

    /** @test */
    function charlie_has_no_pet_food()
    {
        $charlie = $this->petOwners[2];

        self::assertFalse($charlie->hasPetFood());
        self::assertTrue($charlie->hasNoMorePetFood());
    }

    /** @test */
    function charlie_has_no_pets()
    {
        $charlie = $this->petOwners[2];

        self::assertCount(0, $charlie->pets());
    }

    /** @test */
    function alice_and_bob_have_pets()
    {
        [$alice, $bob] = $this->petOwners;

        self::assertCount(1, $alice->pets());
        self::assertCount(2, $bob->pets());
    }

    /** @test */
    function alice_has_pet_food()
    {
        $alice = $this->petOwners[0];

        self::assertTrue($alice->hasPetFood());
        self::assertFalse($alice->hasNoMorePetFood());
    }

    /** @test */
    function alice_has_a_cat_named_Foo()
    {
        $alice = $this->petOwners[0];

        self::assertInstanceOf(Cat::class, $alice->pet(0));
        self::assertSame('Foo', $alice->nameOfPet(0));
        self::assertFalse($alice->petIsHungry(0));
        self::assertFalse($alice->hasHungryPets());
    }

    /** @test */
    function foo_is_the_cat_of_alice()
    {
        $alice = $this->petOwners[0];
        $foo = $alice->pet(0);

        self::assertSame($alice, $foo->owner());
    }

    /** @test */
    function bob_has_a_hungry_cat_named_Bar()
    {
        $bob = $this->petOwners[1];

        self::assertInstanceOf(Cat::class, $bob->pet(0));
        self::assertSame('Bar', $bob->nameOfPet(0));
        self::assertTrue($bob->petIsHungry(0));
        self::assertTrue($bob->hasHungryPets());
    }

    /** @test */
    function bob_also_has_a_dog_named_Baz()
    {
        $bob = $this->petOwners[1];

        self::assertInstanceOf(Dog::class, $bob->pet(1));
        self::assertSame('Baz', $bob->nameOfPet(1));
        self::assertFalse($bob->petIsHungry(1));
    }

    /** @test */
    function bob_has_pet_food()
    {
        $bob = $this->petOwners[1];

        self::assertTrue($bob->hasPetFood());
        self::assertFalse($bob->hasNoMorePetFood());
    }

    /** @test */
    function bob_feeds_his_cat()
    {
        $bob = $this->petOwners[1];
        $bar = $bob->pet(0);

        $bar->askForFood();

        self::assertFalse($bob->hasPetFood());
        self::assertTrue($bob->hasNoMorePetFood());
    }

    /** @test */
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

    /** @test */
    function alice_does_not_feed_bobs_cat()
    {
        [$alice, $bob] = $this->petOwners;

        $bar = $bob->pet(0);

        $this->expectException(ThatIsNotMyPet::class);
        $this->expectExceptionMessage(
            'The Cat Bar is hungry, but Alice does not want to feed it.'
        );

        $bar->askForFoodFrom($alice);
    }

    /** @test */
    function alice_takes_care_of_bobs_cat()
    {
        [$alice, $bob] = $this->petOwners;

        $cat = $bob->pet(0);

        $alice->getANew($cat);

        $this->expectException(ThatIsNotMyPet::class);
        $this->expectExceptionMessage(
            "The Cat Bar can't be abandoned by Bob because Bob didn't own it."
        );

        $bob->abandon($cat);
    }
}

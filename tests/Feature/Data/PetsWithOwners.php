<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Feature\Data;

use Stratadox\Deserializer\ArrayDeserializer;
use Stratadox\Deserializer\Condition\HaveTheDiscriminatorValue;
use Stratadox\Deserializer\Deserializer;
use Stratadox\Deserializer\ForDataSets;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Deserializer\OneOfThese;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Pet\Cat;
use Stratadox\HydrationMapping\Test\Double\Pet\Dog;
use Stratadox\HydrationMapping\Test\Double\Pet\Human;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Hydrator\ObserveBefore;
use Stratadox\Instantiator\ObjectInstantiator;

trait PetsWithOwners
{
    public function petsWithOwners(): array
    {
        return [
            'Array of nested pets' => [
                $this->nestedPetsArrayDeserializer(),
                [
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
                ]
            ]
        ];
    }

    private function nestedPetsArrayDeserializer(): Deserializer
    {
        $referBackToTheOwner = HasBackReference::inProperty('owner');
        $petMappings = MappedHydrator::using(
            ObjectHydrator::default(),
            BooleanValue::inProperty('hungry', [
                'yes',
                'yeah'
            ], [
                'no',
                'nah'
            ]),
            $referBackToTheOwner,
            StringValue::inProperty('name')
        );

        return ObjectDeserializer::using(
            ObjectInstantiator::forThe(Human::class),
            ObserveBefore::hydrating(
                MappedHydrator::using(
                    ObjectHydrator::default(),
                    StringValue::inProperty('name'),
                    IntegerValue::inProperty('food'),
                    HasManyNested::inProperty(
                        'pets',
                        ArrayDeserializer::make(),
                        OneOfThese::deserializers(
                            ForDataSets::that(
                                HaveTheDiscriminatorValue::of('species', 'cat'),
                                ObjectDeserializer::using(
                                    ObjectInstantiator::forThe(Cat::class),
                                    $petMappings
                                )
                            ),
                            ForDataSets::that(
                                HaveTheDiscriminatorValue::of('species', 'dog'),
                                ObjectDeserializer::using(
                                    ObjectInstantiator::forThe(Dog::class),
                                    $petMappings
                                )
                            )
                        )
                    )
                ),
                $referBackToTheOwner
            )
        );
    }
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\Person\Persons;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\CollectionMappingFailed
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\KeyRequiring
 */
class HasManyNested_maps_nested_collections extends TestCase
{
    use MockHydrator;

    /** @test */
    function mapping_a_nested_array_of_names_to_a_collection_of_Authors()
    {
        /** @var string[][][] $inSourceData */
        $inSourceData = [
            'authors' => [
                ['firstName' => 'Jules',     'lastName' => 'Verne'      ],
                ['firstName' => 'George',    'lastName' => 'Orwell'     ],
                ['firstName' => 'Dante',     'lastName' => 'Alighieri'  ],
                ['firstName' => 'William',   'lastName' => 'Shakespeare'],
                ['firstName' => 'Sun',       'lastName' => 'Tzu'        ],
                ['firstName' => 'Charles',   'lastName' => 'Dickens'    ],
                ['firstName' => 'Mark',      'lastName' => 'Twain'      ],
                ['firstName' => 'Alexandre', 'lastName' => 'Dumas'      ],
                ['firstName' => 'Franz',     'lastName' => 'Kafka'      ],
                ['firstName' => 'Isaac',     'lastName' => 'Asimov'     ],
            ]
        ];

        $authorsMapping = HasManyNested::inProperty('authors',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertInstanceOf(Persons::class, $authors);
        $this->assertCount(10, $authors);
        foreach ($inSourceData['authors'] as $who => $authorData) {
            $author = $authors[$who];
            $this->assertInstanceOf(Person::class, $author);
            $this->assertSame($authorData['firstName'], $author->firstName());
            $this->assertSame($authorData['lastName'], $author->lastName());
        }
    }

    /** @test */
    function the_source_key_can_differ_from_the_property_name()
    {
        /** @var string[][][] $inSourceData */
        $inSourceData = [
            'these' => [
                ['firstName' => 'Jules',     'lastName' => 'Verne'      ],
                ['firstName' => 'George',    'lastName' => 'Orwell'     ],
                ['firstName' => 'Dante',     'lastName' => 'Alighieri'  ],
            ]
        ];

        $authorsMapping = HasManyNested::inPropertyWithDifferentKey('authors',
            'these',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        $this->assertSame('authors', $authorsMapping->name());
    }

    /** @test */
    function throwing_an_informative_exception_when_the_source_is_missing()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Missing the key `foo` for property `foo` the input data: []; ' .
            'Mapper: ' . HasManyNested::class
        );

        $mapping->value([]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->mockCollectionHydratorForThe(Persons::class),
            $this->mockExceptionThrowingHydrator('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyNested items of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => [[]]]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_collection_cannot_be_mapped()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->mockExceptionThrowingHydrator('Original message here.'),
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyNested collection of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => []]);
    }
}

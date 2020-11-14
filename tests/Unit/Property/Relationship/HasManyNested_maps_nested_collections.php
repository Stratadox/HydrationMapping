<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\MissingTheKey;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\HydrationMapping\MappingFailure;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\Person\Persons;
use Stratadox\HydrationMapping\Test\Double\Deserializers;

class HasManyNested_maps_nested_collections extends TestCase
{
    use Deserializers;

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
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->deserializerForThe(Person::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        self::assertInstanceOf(Persons::class, $authors);
        self::assertCount(10, $authors);
        foreach ($inSourceData['authors'] as $who => $authorData) {
            $author = $authors[$who];
            self::assertInstanceOf(Person::class, $author);
            self::assertSame($authorData['firstName'], $author->firstName());
            self::assertSame($authorData['lastName'], $author->lastName());
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
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->deserializerForThe(Person::class)
        );

        /** @var Persons|Person[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        self::assertCount(3, $authors);
        self::assertSame('authors', $authorsMapping->name());
    }

    /** @test */
    function throwing_an_exception_when_the_source_is_missing()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->deserializerForThe(Person::class)
        );

        $this->expectException(MissingTheKey::class);

        $mapping->value([]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->immutableCollectionDeserializerFor(Persons::class),
            $this->exceptionThrowingDeserializer('Original message here.')
        );

        $this->expectException(MappingFailure::class);
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
            $this->exceptionThrowingCollectionDeserializer('Original message here.'),
            $this->deserializerForThe(Person::class)
        );

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasManyNested collection of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => []]);
    }
}

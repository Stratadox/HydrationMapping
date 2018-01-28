<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\UnmappableInput;
use Stratadox\HydrationMapping\Test\Double\Author\Author;
use Stratadox\HydrationMapping\Test\Double\Author\Authors;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\CollectionMappingFailed
 */
class HasManyNested_maps_nested_collections extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function mapping_a_nested_array_of_names_to_a_collection_of_Authors()
    {
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
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        /** @var Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertInstanceOf(Authors::class, $authors);
        $this->assertCount(10, $authors);
        foreach ($inSourceData['authors'] as $who => $authorData) {
            $author = $authors[$who];
            $this->assertInstanceOf(Author::class, $author);
            $this->assertSame($authorData['firstName'], $author->firstName());
            $this->assertSame($authorData['lastName'], $author->lastName());
        }
    }

    /** @scenario */
    function the_source_key_can_differ_from_the_property_name()
    {
        $inSourceData = [
            'these' => [
                ['firstName' => 'Jules',     'lastName' => 'Verne'      ],
                ['firstName' => 'George',    'lastName' => 'Orwell'     ],
                ['firstName' => 'Dante',     'lastName' => 'Alighieri'  ],
            ]
        ];

        $authorsMapping = HasManyNested::inPropertyWithDifferentKey('authors',
            'these',
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        /** @var Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        $this->assertSame('authors', $authorsMapping->name());
    }

    /** @scenario */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->mockCollectionHydratorForThe(Authors::class),
            $this->mockExceptionThrowingHydrator('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionMessage(
            'Failed to map the HasManyNested items of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => [[]]]);
    }

    /** @scenario */
    function throwing_an_informative_exception_when_the_collection_cannot_be_mapped()
    {
        $mapping = HasManyNested::inProperty('foo',
            $this->mockExceptionThrowingHydrator('Original message here.'),
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionMessage(
            'Failed to map the HasManyNested collection of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => []]);
    }
}

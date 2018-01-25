<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\HydrationMapping\Test\Doubles\Author\Author;
use Stratadox\HydrationMapping\Test\Doubles\Author\Authors;
use Stratadox\HydrationMapping\Test\Doubles\MockHydrator;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
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
}

<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Unit\Mapping\Relationship;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Test\Authors\Author;
use Stratadox\Hydration\Test\Authors\Authors;

class I_want_to_map_collections extends TestCase
{
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
            $this->mockHydratorForThe(Authors::class),
            $this->mockHydratorForThe(Author::class)
        );

        /** @var Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertInstanceOf(Authors::class, $authors);
        $this->assertCount(10, $authors);
        foreach ($inSourceData['authors'] as $who => $author) {
            $this->assertInstanceOf(Author::class, $authors[$who]);
            $this->assertSame($author['firstName'], $authors[$who]->firstName());
            $this->assertSame($author['lastName'], $authors[$who]->lastName());
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

        $authorsMapping = HasManyNested::propertyWithDifferentKey('authors',
            'these',
            $this->mockHydratorForThe(Authors::class),
            $this->mockHydratorForThe(Author::class)
        );

        /** @var Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertCount(3, $authors);
        foreach ($inSourceData['these'] as $who => $author) {
            $this->assertInstanceOf(Author::class, $authors[$who]);
        }
    }

    /** @scenario */
    function property_mapping_objects_know_which_property_they_map_to()
    {
        $authorsMapping = HasManyNested::inProperty('authors',
            $this->mockHydratorForThe(Authors::class),
            $this->mockHydratorForThe(Author::class)
        );
        $this->assertSame('authors', $authorsMapping->name());
    }

    /**
     * Extremely simple mock hydrator. It essentially just calls the constructor,
     * which is sufficient for our testing purposes.
     *
     * @param string $class
     * @return Hydrates|Mock
     */
    private function mockHydratorForThe(string $class) : Mock
    {
        $hydrator = $this->createMock(Hydrates::class);

        $hydrator->expects($this->any())
            ->method('fromArray')
            ->willReturnCallback(
                function (array $data) use ($class)
                {
                    return new $class(...array_values($data));
                }
            );

        return $hydrator;
    }
}

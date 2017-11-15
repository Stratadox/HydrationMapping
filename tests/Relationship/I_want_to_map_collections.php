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
            'Authors' => [
                ['FirstName' => 'Jules',     'LastName' => 'Verne'      ],
                ['FirstName' => 'George',    'LastName' => 'Orwell'     ],
                ['FirstName' => 'Dante',     'LastName' => 'Alighieri'  ],
                ['FirstName' => 'William',   'LastName' => 'Shakespeare'],
                ['FirstName' => 'Sun',       'LastName' => 'Tzu'        ],
                ['FirstName' => 'Charles',   'LastName' => 'Dickens'    ],
                ['FirstName' => 'Mark',      'LastName' => 'Twain'      ],
                ['FirstName' => 'Alexandre', 'LastName' => 'Dumas'      ],
                ['FirstName' => 'Franz',     'LastName' => 'Kafka'      ],
                ['FirstName' => 'Isaac',     'LastName' => 'Asimov'     ],
            ]
        ];

        $authorsMapping = HasManyNested::inProperty('Authors',
            $this->mockHydratorForThe(Authors::class),
            $this->mockHydratorForThe(Author::class)
        );

        /** @var Authors|Author[] $authors */
        $authors = $authorsMapping->value($inSourceData);

        $this->assertInstanceOf(Authors::class, $authors);
        $this->assertCount(10, $authors);
        foreach ($inSourceData['Authors'] as $who => $author) {
            $this->assertInstanceOf(Author::class, $authors[$who]);
            $this->assertSame($author['FirstName'], $authors[$who]->firstName());
            $this->assertSame($author['LastName'], $authors[$who]->lastName());
        }
    }

    /** @scenario */
    function property_mapping_objects_know_which_property_they_map_to()
    {
        $authorsMapping = HasManyNested::inProperty('Authors',
            $this->mockHydratorForThe(Authors::class),
            $this->mockHydratorForThe(Author::class)
        );
        $this->assertSame('Authors', $authorsMapping->name());
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

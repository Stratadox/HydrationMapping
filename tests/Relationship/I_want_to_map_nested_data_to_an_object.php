<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Test\Authors\Author;
use Stratadox\Hydration\Test\Relationship\MockHydrator;

class I_want_to_map_nested_data_to_an_object extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function mapping_a_nested_array_to_a_HasOne_relationship()
    {
        $inProfileData = [
            'author' => [
                'firstName' => 'Jules',
                'lastName' => 'Verne'
            ]
        ];

        $authorMapping = HasOneNested::inProperty('author',
            $this->mockHydratorForThe(Author::class)
        );

        $author = $authorMapping->value($inProfileData);

        $this->assertInstanceOf(Author::class, $author);
    }
}

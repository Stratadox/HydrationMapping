<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Test\Authors\Author;
use Stratadox\Hydration\Test\Relationship\MockHydrator;

class I_want_to_map_nested_objects extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function mapping_a_nested_array_to_a_HasOne_relationship()
    {
        $inAuthorData = [
            'author' => [
                'firstName' => 'Jules',
                'lastName' => 'Verne'
            ]
        ];

        $authorMapping = HasOneNested::inProperty('author',
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        /** @var Author $author */
        $author = $authorMapping->value($inAuthorData);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertSame('Jules', $author->firstName());
        $this->assertSame('Verne', $author->lastName());
    }

    /** @scenario */
    function the_source_key_can_differ_from_the_property_name()
    {
        $inAuthorData = [
            'person' => [
                'firstName' => 'Jules',
                'lastName' => 'Verne'
            ]
        ];

        $authorMapping = HasOneNested::inPropertyWithDifferentKey('author',
            'person',
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        $author = $authorMapping->value($inAuthorData);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertSame('author', $authorMapping->name());
    }
}

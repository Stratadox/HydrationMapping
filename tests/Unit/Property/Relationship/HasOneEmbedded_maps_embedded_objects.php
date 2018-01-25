<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\HydrationMapping\Test\Double\Author\Author;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;

/** @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded */
class HasOneEmbedded_maps_embedded_objects extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function mapping_part_of_a_flat_array_to_a_relationship()
    {
        $bookInformation = [
            'firstName' => 'Elle',
            'lastName' => 'Garner',
            'title' => 'Fruit Infused Water: 50 Quick & Easy Recipes for ' .
                'Delicious & Healthy Hydration'
        ];

        $authorMapping = HasOneEmbedded::inProperty('author',
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        /** @var Author $author */
        $author = $authorMapping->value($bookInformation);

        $this->assertInstanceOf(Author::class, $author);
    }

    /** @scenario */
    function property_mappers_know_which_property_they_map_to()
    {
        $authorMapping = HasOneEmbedded::inProperty('author',
            $this->mockPublicSetterHydratorForThe(Author::class)
        );

        $this->assertSame('author', $authorMapping->name());
    }
}

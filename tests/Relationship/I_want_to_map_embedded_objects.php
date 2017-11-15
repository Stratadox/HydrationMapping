<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Test\Authors\Author;
use Stratadox\Hydration\Test\Relationship\MockHydrator;

class I_want_to_map_embedded_objects extends TestCase
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
            $this->mockPublicSetterHydratorForThe(Author::class),
            ['firstName', 'lastName']
        );

        /** @var Author $author */
        $author = $authorMapping->value($bookInformation);

        $this->assertInstanceOf(Author::class, $author);
        $this->assertObjectNotHasAttribute('title', $author);
    }
}

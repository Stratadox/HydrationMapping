<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Test\Authors\Author;
use Stratadox\Hydration\Test\Authors\Authors;
use Stratadox\Hydration\Test\Relationship\MockHydrator;

class I_want_to_combine_property_mappings_into_an_object_mapping extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function class_mappings_know_which_class_they_map()
    {
        $mapped = Mapping::ofThe(Author::class);

        $this->assertSame(Author::class, $mapped->className());
    }

    /** @scenario */
    function class_mappings_contain_property_mappings()
    {
        $propertyMapping = HasManyNested::inProperty('authors',
            $this->mockHydratorForThe(Authors::class),
            $this->mockHydratorForThe(Author::class)
        );
        $mapped = Mapping::ofThe(Authors::class,
            $propertyMapping
        );

        $this->assertSame(Author::class, $mapped->className());
        $this->assertSame([$propertyMapping], $mapped->properties());
    }
}
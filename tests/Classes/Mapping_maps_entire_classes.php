<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\Hydration\Test\Classes\Foo\Foo;
use Stratadox\Hydration\Test\Relationship\MockHydrator;

/** @covers \Stratadox\Hydration\Mapping\Mapping */
class Mapping_maps_entire_classes extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function class_mappings_know_which_class_they_map()
    {
        $mapped = Mapping::ofThe(Foo::class);

        $this->assertSame(Foo::class, $mapped->className());
    }

    /** @scenario */
    function class_mappings_contain_property_mappings()
    {
        $propertyMapping = HasOneNested::inProperty('foo',
            $this->mockCollectionHydratorForThe(Foo::class)
        );
        $mapped = Mapping::ofThe(Foo::class,
            $propertyMapping
        );

        $this->assertSame([$propertyMapping], $mapped->properties());
    }
}
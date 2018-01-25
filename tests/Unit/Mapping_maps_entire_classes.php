<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Mapping;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;

/** @covers \Stratadox\Hydration\Mapping\Mapping */
class Mapping_maps_entire_classes extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function class_mappings_know_which_class_they_map()
    {
        $mapped = Mapping::ofThe(self::class);

        $this->assertSame(self::class, $mapped->className());
    }

    /** @scenario */
    function class_mappings_contain_property_mappings()
    {
        $propertyMapping = HasOneNested::inProperty('foo',
            $this->mockCollectionHydratorForThe(self::class)
        );
        $mapped = Mapping::ofThe(self::class,
            $propertyMapping
        );

        $this->assertSame([$propertyMapping], $mapped->properties());
    }
}
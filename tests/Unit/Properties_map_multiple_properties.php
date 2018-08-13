<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\MockDeserializer;

/**
 * @covers \Stratadox\Hydration\Mapping\Properties
 */
class Properties_map_multiple_properties extends TestCase
{
    use MockDeserializer;

    /** @test */
    function class_mappings_contain_property_mappings()
    {
        $fooProperty = StringValue::inProperty('foo');
        $barProperty = IntegerValue::inProperty('bar');

        $propertyMappings = Properties::map(
            $fooProperty,
            $barProperty
        );

        $this->assertContains($fooProperty, $propertyMappings);
        $this->assertContains($barProperty, $propertyMappings);

        $this->assertSame($fooProperty, $propertyMappings[0]);
        $this->assertSame($barProperty, $propertyMappings[1]);
    }
}

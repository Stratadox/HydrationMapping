<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Type;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\MappingFailure;

class Scalars_can_map_keys_to_properties extends TestCase
{
    /** @test */
    function mapping_a_property_with_a_different_array_key()
    {
        $map = StringValue::inPropertyWithDifferentKey('property', 'key');

        self::assertEquals('property', $map->name());
        self::assertEquals('value', $map->value(['key' => 'value']));
    }

    /** @test */
    function throwing_an_exception_if_the_key_does_not_exist()
    {
        $map = StringValue::inProperty('missing');

        $this->expectException(MappingFailure::class);
        $map->value(['foo' => 'bar']);
    }
}

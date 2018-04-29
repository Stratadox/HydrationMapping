<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\OriginalValue
 */
class OriginalValue_leaves_the_input_value_intact extends TestCase
{
    /** @test */
    function allowing_null_input()
    {
        $source = ['any' => null];

        $map = OriginalValue::inProperty('any');

        $this->assertNull($map->value($source));
    }

    /** @test */
    function allowing_string_input()
    {
        $source = ['any' => 'foo'];

        $map = OriginalValue::inProperty('any');

        $this->assertSame('foo', $map->value($source));
    }

    /** @test */
    function allowing_object_input()
    {
        $source = ['any' => $this];

        $map = OriginalValue::inProperty('any');

        $this->assertSame($this, $map->value($source));
    }

    /** @test */
    function original_value_mapping_knows_which_property_to_map_to()
    {
        $map = OriginalValue::inProperty('any');
        $this->assertSame('any', $map->name());
    }
}

<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Type;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Type\OriginalValue;

class OriginalValue_leaves_the_input_value_intact extends TestCase
{
    /** @test */
    function allowing_null_input()
    {
        $source = ['any' => null];

        $map = OriginalValue::inProperty('any');

        self::assertNull($map->value($source));
    }

    /** @test */
    function allowing_string_input()
    {
        $source = ['any' => 'foo'];

        $map = OriginalValue::inProperty('any');

        self::assertSame('foo', $map->value($source));
    }

    /** @test */
    function allowing_object_input()
    {
        $source = ['any' => $this];

        $map = OriginalValue::inProperty('any');

        self::assertSame($this, $map->value($source));
    }

    /** @test */
    function original_value_mapping_knows_which_property_to_map_to()
    {
        $map = OriginalValue::inProperty('any');
        self::assertSame('any', $map->name());
    }
}

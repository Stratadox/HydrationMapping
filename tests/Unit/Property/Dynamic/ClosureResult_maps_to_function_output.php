<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Dynamic;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Dynamic\ClosureResult
 */
class ClosureResult_maps_to_function_output extends TestCase
{
    /** @test */
    function return_the_function_result()
    {
        $source = [
            'value1' => 'foo',
            'value2' => 'bar',
        ];

        $map = ClosureResult::inProperty('concatenate', function (array $data) {
            return $data['value1'] . $data['value2'];
        });

        $output = $map->value($source);

        $this->assertSame('foobar', $output);
    }

    /** @test */
    function property_mappers_know_which_property_they_map_to()
    {
        $map = ClosureResult::inProperty('concatenate', function (array $data) {
            return $data['value1'] . $data['value2'];
        });

        $this->assertSame('concatenate', $map->name());
    }
}

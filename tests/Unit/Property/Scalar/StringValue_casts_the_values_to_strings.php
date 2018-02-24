<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\StringValue
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 */
class StringValue_casts_the_values_to_strings extends TestCase
{
    /** @test */
    function integer_values_become_numeric_strings()
    {
        $source = ['string' => 123];

        $map = StringValue::inProperty('string');

        $this->assertSame('123', $map->value($source));
    }

    /** @test */
    function boolean_true_converts_to_1()
    {
        $source = ['string' => true];

        $map = StringValue::inProperty('string');

        $this->assertSame('1', $map->value($source));
    }

    /** @test */
    function boolean_false_converts_to_empty_string()
    {
        $source = ['string' => false];

        $map = StringValue::inProperty('string');

        $this->assertSame('', $map->value($source));
    }

    /** @test */
    function float_values_convert_to_some_approximation()
    {
        $source = ['string' => 10.0002597343609724924];

        $map = StringValue::inProperty('string');

        $this->assertSame('10.000259734361', $map->value($source));
    }
}

<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 */
class BooleanValue_casts_appropriate_values_to_booleans extends TestCase
{
    /**
     * @test
     * @dataProvider trueValues
     * @param mixed $true Value that should be considered true
     */
    function true_values_becomes_true_booleans($true)
    {
        $source = ['boolean' => $true];

        $map = BooleanValue::inProperty('boolean');

        $this->assertSame(true, $map->value($source));
    }

    /**
     * @test
     * @dataProvider falseValues
     * @param mixed $false Value that should be considered false
     */
    function false_values_becomes_false_booleans($false)
    {
        $source = ['boolean' => $false];

        $map = BooleanValue::inProperty('boolean');

        $this->assertSame(false, $map->value($source));
    }

    /**
     * @test
     * @dataProvider unacceptableValues
     * @param mixed $itsNotBoolean Value that should throw an exception
     */
    function unacceptable_input_throws_an_exception($itsNotBoolean)
    {
        $source = ['boolean' => $itsNotBoolean];

        $map = BooleanValue::inProperty('boolean');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `'.$itsNotBoolean.'` to property `boolean`: it is ' .
            'not clean for conversion to boolean.'
        );
        $map->value($source);
    }

    public function trueValues() : array
    {
        return [
            'true' => [true],
            '1' => [1],
            '"1"' => ['1'],
            '0.1' => [0.1],
            '"7.2"' => ['7.2'],
        ];
    }

    public function falseValues() : array
    {
        return [
            'false' => [false],
            '0' => [0],
            '"0"' => ['0'],
            '-3.9' => [-3.9],
        ];
    }

    public function unacceptableValues() : array
    {
        return [
            '"true"' => ['true'],
            '"false"' => ['false'],
            '"maybe"' => ['maybe'],
        ];
    }
}

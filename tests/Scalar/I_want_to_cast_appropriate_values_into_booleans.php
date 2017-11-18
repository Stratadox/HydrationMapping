<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class I_want_to_cast_appropriate_values_into_booleans extends TestCase
{
    /**
     * @scenario
     * @dataProvider trueValues
     */
    function seemingly_truthful_input_becomes_true($true)
    {
        $source = ['boolean' => $true];

        $map = BooleanValue::inProperty('boolean');

        $this->assertSame(true, $map->value($source));
    }

    /**
     * @scenario
     * @dataProvider falseValues
     */
    function seemingly_false_input_becomes_false($false)
    {
        $source = ['boolean' => $false];

        $map = BooleanValue::inProperty('boolean');

        $this->assertSame(false, $map->value($source));
    }

    /**
     * @scenario
     * @dataProvider unacceptableValues
     */
    function unacceptable_input_throws_an_exception($itsNotBoolean)
    {
        $source = ['boolean' => $itsNotBoolean];

        $map = BooleanValue::inProperty('boolean');

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    public function trueValues()
    {
        return [
            'true' => [true],
            '1' => [1],
            '"1"' => ['1'],
            '0.1' => [0.1],
            '"7.2"' => ['7.2'],
        ];
    }

    public function falseValues()
    {
        return [
            'false' => [false],
            '0' => [0],
            '"0"' => ['0'],
            '-3.9' => [-3.9],
        ];
    }

    public function unacceptableValues()
    {
        return [
            '"true"' => ['true'],
            '"false"' => ['false'],
            '"maybe"' => ['maybe'],
        ];
    }
}

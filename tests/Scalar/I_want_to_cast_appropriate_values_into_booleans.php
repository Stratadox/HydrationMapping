<?php

namespace Stratadox\Hydration\Test\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\UnmappableInput;

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
            'One' => [1],
            'Positive integer' => [100],
            'Positive float' => [0.1],
            'Positive numeric string' => ['3'],
            '"true"' => ['true'],
            '"True"' => ['True'],
            '"TRUE"' => ['TRUE'],
            '"y"' => ['y'],
            '"Y"' => ['Y'],
            '"yes"' => ['yes'],
            '"Yes"' => ['Yes'],
            '"YES"' => ['YES'],
            'true' => [true],
        ];
    }

    public function falseValues()
    {
        return [
            'Zero' => [0],
            'Zero as string' => ['0'],
            'Negative integer' => [-100],
            'Negative float' => [-0.1],
            'Negative numeric string' => ['-3.5'],
            '"false"' => ['false'],
            '"False"' => ['False'],
            '"FALSE"' => ['FALSE'],
            '"n"' => ['n'],
            '"N"' => ['N'],
            '"no"' => ['no'],
            '"No"' => ['No'],
            '"NO"' => ['NO'],
            'false' => [false],
        ];
    }

    public function unacceptableValues()
    {
        return [
            '"Yolo"' => ['Yolo'],
            '"somewhat true"' => ['somewhat true'],
            '"false?"' => ['false?'],
            '"probably"' => ['probably'],
            '"true, maybe"' => ['true, maybe'],
        ];
    }
}

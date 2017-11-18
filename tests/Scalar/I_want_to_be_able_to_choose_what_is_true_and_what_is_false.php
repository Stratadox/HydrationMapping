<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CustomTruths;
use Stratadox\Hydration\UnmappableInput;

/**
 * Don't we all?
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\CustomTruths
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 * @covers \Stratadox\Hydration\Mapping\Property\FromSingleKey
 */
class I_want_to_be_able_to_choose_what_is_true_and_what_is_false extends TestCase
{
    /**
     * @param mixed $true
     * @param array $truths
     * @param array $falsehoods
     * @scenario
     * @dataProvider trueValues
     */
    function this_is_now_truth($true, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $true];

        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->assertSame(true, $map->value($source));
    }

    /**
     * @param mixed $itsFakeBelieveMe
     * @param array $truths
     * @param array $falsehoods
     * @scenario
     * @dataProvider falseValues
     */
    function fake_news($itsFakeBelieveMe, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $itsFakeBelieveMe];

        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->assertSame(false, $map->value($source));
    }

    /**
     * @param mixed $itsNotBoolean
     * @param array $truths
     * @param array $falsehoods
     * @scenario
     * @dataProvider unacceptableValues
     */
    function unacceptable_input_throws_an_exception($itsNotBoolean, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $itsNotBoolean];

        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    /** @scenario */
    function custom_truth_mapping_knows_which_property_to_map_to()
    {
        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'), [], []);
        $this->assertSame('boolean', $map->name());
    }

    public function trueValues()
    {
        $truths = ['true', 'TRUE', 'y', 'yes'];
        $falsehoods = ['false', 'FALSE', 'n', 'no'];
        return [
            '"true"' => ['true', $truths, $falsehoods],
            '"TRUE"' => ['TRUE', $truths, $falsehoods],
            '"y"' => ['y', $truths, $falsehoods],
            '"yes"' => ['yes', $truths, $falsehoods],
            '"1"' => ['1', $truths, $falsehoods],
        ];
    }

    public function falseValues()
    {
        $truths = ['what', 'is', 'truth', 'anyway', '?'];
        $falsehoods = ['CNN', 'Russia', 'nah'];
        return [
            'CNN' => ['CNN', $truths, $falsehoods],
            'Russia' => ['Russia', $truths, $falsehoods],
            'nah' => ['nah', $truths, $falsehoods],
            '"0"' => ['0', $truths, $falsehoods],
        ];
    }

    public function unacceptableValues()
    {
        $truths = ['TRUE', 'ok!', 'yeah', 'why not?'];
        $falsehoods = ['FALSE', 'no way', 'nope'];
        return [
            '"true"' => ['true', $truths, $falsehoods],
            '"false"' => ['false', $truths, $falsehoods],
            '"maybe"' => ['maybe', $truths, $falsehoods],
        ];
    }
}

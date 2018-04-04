<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Scalar;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Scalar\CustomTruths;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\CustomTruths
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Scalar\Scalar
 */
class CustomTruths_defines_what_is_true_and_what_is_false extends TestCase
{
    /**
     * @test
     * @dataProvider trueValues
     * @param mixed $true
     * @param array $truths
     * @param array $falsehoods
     */
    function this_is_now_truth($true, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $true];

        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->assertTrue($map->value($source));
    }

    /**
     * @test
     * @dataProvider falseValues
     * @param mixed $itsFakeBelieveMe
     * @param array $truths
     * @param array $falsehoods
     */
    function fake_news($itsFakeBelieveMe, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $itsFakeBelieveMe];

        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->assertFalse($map->value($source));
    }

    /**
     * @test
     * @dataProvider unacceptableValues
     * @param mixed $itsNotBoolean
     * @param array $truths
     * @param array $falsehoods
     */
    function unacceptable_input_throws_an_exception($itsNotBoolean, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $itsNotBoolean];

        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `'.$itsNotBoolean.'` to property `boolean`: it is ' .
            'not clean for conversion to boolean.'
        );
        $map->value($source);
    }

    /** @test */
    function custom_truth_mapping_knows_which_property_to_map_to()
    {
        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'), [], []);
        $this->assertSame('boolean', $map->name());
    }

    /** @test */
    function comparing_truths_uses_strict_comparison()
    {
        $truths = [0, '1'];
        $falsehoods = [1, '0', 7];
        $map = CustomTruths::forThe(BooleanValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->assertFalse($map->value(['boolean' => 1]));
        $this->assertTrue($map->value(['boolean' => 0]));
        $this->assertFalse($map->value(['boolean' => '0']));
        $this->assertTrue($map->value(['boolean' => '1']));

        $this->assertTrue($map->value(['boolean' => '7']));
        $this->assertFalse($map->value(['boolean' => 7]));
    }

    public function trueValues() : array
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

    public function falseValues() : array
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

    public function unacceptableValues() : array
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

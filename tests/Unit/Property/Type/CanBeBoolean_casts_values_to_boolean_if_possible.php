<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Type;

use const PHP_INT_MAX;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\Hydration\Mapping\Property\Type\CanBeBoolean;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Type\CanBeBoolean
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Type\Scalar
 */
class CanBeBoolean_casts_values_to_boolean_if_possible extends TestCase
{
    /** @test */
    function converting_string_zero_to_boolean_false()
    {
        $source = ['mixed' => '0'];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertFalse($map->value($source));
    }

    /** @test */
    function converting_string_one_to_boolean_true()
    {
        $source = ['mixed' => '1'];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertTrue($map->value($source));
    }

    /** @test */
    function leaving_boolean_false_as_boolean_false()
    {
        $source = ['mixed' => false];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertFalse($map->value($source));
    }

    /** @test */
    function leaving_boolean_true_as_boolean_true()
    {
        $source = ['mixed' => true];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertTrue($map->value($source));
    }

    /** @test */
    function converting_integer_zero_to_boolean_false()
    {
        $source = ['mixed' => 0];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertFalse($map->value($source));
    }

    /** @test */
    function converting_integer_one_to_boolean_true()
    {
        $source = ['mixed' => 1];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertTrue($map->value($source));
    }

    /** @test */
    function converting_two_to_integer()
    {
        $source = ['mixed' => '2'];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'));

        $this->assertSame(2, $map->value($source));
    }

    /**
     * @test
     * @dataProvider trueValues
     */
    function converting_custom_truth_values_to_boolean_true_with(
        $true,
        array $truths,
        array $falsehoods
    ) {
        $source = ['mixed' => $true];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'),
            $truths,
            $falsehoods
        );

        $this->assertTrue($map->value($source));
    }

    /**
     * @test
     * @dataProvider falseValues
     */
    function converting_custom_falsehoods_to_boolean_false_with(
        $itsFakeBelieveMe,
        array $truths,
        array $falsehoods
    ) {
        $source = ['mixed' => $itsFakeBelieveMe];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'),
            $truths,
            $falsehoods
        );

        $this->assertFalse($map->value($source));
    }

    /**
     * @test
     * @dataProvider unacceptableValues
     */
    function unacceptable_input_throws_an_exception(
        $itsNotBoolean,
        array $truths,
        array $falsehoods
    ) {
        $source = ['mixed' => $itsNotBoolean];

        $map = CanBeBoolean::or(IntegerValue::inProperty('mixed'),
            $truths,
            $falsehoods
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `' . $itsNotBoolean . '` to property `mixed`: ' .
            'it is not clean for conversion to integer. It could not be ' .
            'mapped to boolean either.'
        );
        $map->value($source);
    }

    /** @test */
    function custom_truth_mapping_knows_which_property_to_map_to()
    {
        $map = CanBeBoolean::or(BooleanValue::inProperty('boolean'), [], []);
        $this->assertSame('boolean', $map->name());
    }

    /** @test */
    function custom_truth_mapping_can_itself_also_be_wrapped()
    {
        $map = CanBeBoolean::or(
            BooleanValue::inPropertyWithDifferentKey('boolean', 'bool'),
            ['yes'],
            ['no']
        );
        $this->assertSame('bool', $map->key());
    }

    /** @test */
    function comparing_truths_uses_strict_comparison()
    {
        $truths = [0, '1'];
        $falsehoods = [1, '0', 7];
        $map = CanBeBoolean::or(IntegerValue::inProperty('boolean'),
            $truths,
            $falsehoods
        );

        $this->assertFalse($map->value(['boolean' => 1]));
        $this->assertTrue($map->value(['boolean' => 0]));
        $this->assertFalse($map->value(['boolean' => '0']));
        $this->assertTrue($map->value(['boolean' => '1']));

        $this->assertNotFalse($map->value(['boolean' => '7']));
        $this->assertFalse($map->value(['boolean' => 7]));
    }

    public function trueValues(): array
    {
        $truths = ['true', 'TRUE', 'y', 'yes'];
        $falsehoods = ['false', 'FALSE', 'n', 'no'];
        return [
            '"true"' => ['true', $truths, $falsehoods],
            '"TRUE"' => ['TRUE', $truths, $falsehoods],
            '"y"'    => ['y', $truths, $falsehoods],
            '"yes"'  => ['yes', $truths, $falsehoods],
        ];
    }

    public function falseValues(): array
    {
        $truths = ['what', 'is', 'truth', 'anyway', '?'];
        $falsehoods = ['CNN', 'Russia', 'nah'];
        return [
            'CNN'    => ['CNN', $truths, $falsehoods],
            'Russia' => ['Russia', $truths, $falsehoods],
            'nah'    => ['nah', $truths, $falsehoods],
        ];
    }

    public function integerValues(): array
    {
        return [
            'String 0'    => ['0', ['true'], ['false']],
            'String 1'    => ['1', ['true'], ['false']],
            'String 2'    => ['2', ['true'], ['false']],
            'Integer 3'   => [3, ['true'], ['false']],
            'String -100' => ['-100', ['true'], ['false']],
            'PHP_INT_MIN' => [(string) PHP_INT_MIN, ['true'], ['false']],
            'PHP_INT_MAX' => [(string) PHP_INT_MAX, ['true'], ['false']],
        ];
    }

    public function unacceptableValues(): array
    {
        $truths = ['TRUE', 'ok!', 'yeah', 'why not?'];
        $falsehoods = ['FALSE', 'no way', 'nope'];
        return [
            '"true"'  => ['true', $truths, $falsehoods],
            '"false"' => ['false', $truths, $falsehoods],
            '"maybe"' => ['maybe', $truths, $falsehoods],
        ];
    }
}

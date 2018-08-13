<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Type;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Type\BooleanValue;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Type\BooleanValue
 * @covers \Stratadox\Hydration\Mapping\Property\UnmappableProperty
 * @covers \Stratadox\Hydration\Mapping\Property\Type\ScalarValue
 */
class BooleanValue_casts_appropriate_values_to_booleans extends TestCase
{
    /**
     * @test
     * @dataProvider trueValues
     */
    function true_values_becomes_boolean_true($true)
    {
        $source = ['boolean' => $true];

        $map = BooleanValue::inProperty('boolean');

        $this->assertTrue($map->value($source));
    }

    /**
     * @test
     * @dataProvider falseValues
     */
    function false_values_becomes_boolean_false($false)
    {
        $source = ['boolean' => $false];

        $map = BooleanValue::inProperty('boolean');

        $this->assertFalse($map->value($source));
    }

    /**
     * @test
     * @dataProvider customTrueValues
     */
    function custom_true_values_become_boolean_true(
        $true,
        $truths,
        $falsehoods
    ) {
        $source = ['boolean' => $true];

        $map = BooleanValue::inProperty('boolean', $truths, $falsehoods);

        $this->assertTrue($map->value($source));
    }

    /**
     * @test
     * @dataProvider customFalseValues
     */
    function custom_false_values_become_boolean_false(
        $false,
        $truths,
        $falsehoods
    ) {
        $source = ['boolean' => $false];

        $map = BooleanValue::inProperty('boolean', $truths, $falsehoods);

        $this->assertFalse($map->value($source));
    }

    /**
     * @test
     * @dataProvider customTrueValues
     */
    function custom_true_values_become_boolean_true_from_different_key(
        $true,
        $truths,
        $falsehoods
    ) {
        $source = ['boolean' => $true];

        $map = BooleanValue::inPropertyWithDifferentKey(
            'bool',
            'boolean',
            $truths,
            $falsehoods
        );

        $this->assertTrue($map->value($source));
        $this->assertSame('bool', $map->name());
    }

    /**
     * @test
     * @dataProvider customFalseValues
     */
    function custom_false_values_become_boolean_false_from_different_key(
        $false,
        $truths,
        $falsehoods
    ) {
        $source = ['boolean' => $false];

        $map = BooleanValue::inPropertyWithDifferentKey(
            'bool',
            'boolean',
            $truths,
            $falsehoods
        );

        $this->assertFalse($map->value($source));
        $this->assertSame('bool', $map->name());
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
            'Cannot assign `' . $itsNotBoolean . '` to property `boolean`: ' .
            'it is not clean for conversion to boolean.'
        );
        $map->value($source);
    }

    public function trueValues(): array
    {
        return [
            'true'      => [true],
            'String 1'  => ['1'],
            'Integer 1' => [1],
        ];
    }

    public function falseValues(): array
    {
        return [
            'false'     => [false],
            'String 0'  => ['0'],
            'Integer 0' => [0],
        ];
    }

    public function customTrueValues(): array
    {
        return [
            'String true' => ['true', ['true'], ['false']],
            'String yes'  => ['yes', ['yes'], ['no']],
        ];
    }

    public function customFalseValues(): array
    {
        return [
            'String false' => ['false', ['true'], ['false']],
            'String no'  => ['no', ['yes'], ['no']],
        ];
    }

    public function unacceptableValues(): array
    {
        return [
            'String true'  => ['true'],
            'String false' => ['false'],
            'String maybe' => ['maybe'],
        ];
    }
}

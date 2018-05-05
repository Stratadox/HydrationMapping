<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\HydrationMapping\Test\Double\Constraint\ItIsNotLess;
use Stratadox\HydrationMapping\Test\Double\Constraint\ItIsNotMore;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Check
 * @covers \Stratadox\Hydration\Mapping\Property\UnsatisfiedConstraint
 */
class Check_whether_the_data_is_valid extends TestCase
{
    /** @test */
    function allowing_valid_data_to_be_mapped()
    {
        $map = Check::that(
            ItIsNotLess::than(5)->and(ItIsNotMore::than(10)),
            IntegerValue::inProperty('foo')
        );

        $this->assertSame(6, $map->value(['foo' => '6']));
    }

    /** @test */
    function barring_invalid_data_from_being_mapped()
    {
        $map = Check::that(
            ItIsNotLess::than(5)->and(ItIsNotMore::than(10)),
            IntegerValue::inProperty('foo')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `4` to property `foo`: ' .
            'The value did not satisfy the specifications.'
        );

        $map->value(['foo' => '4']);
    }

    /** @test */
    function knowing_which_property_to_map_to()
    {
        $map = Check::that(ItIsNotMore::than(10), FloatValue::inProperty('foo'));

        $this->assertSame('foo', $map->name());
    }
}

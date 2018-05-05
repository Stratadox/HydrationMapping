<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Check;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Scalar\FloatValue;
use Stratadox\Hydration\Mapping\Property\Scalar\IntegerValue;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapping\Test\Double\Constraint\FirstNameIsLonger;
use Stratadox\HydrationMapping\Test\Double\Constraint\ItIsNotLess;
use Stratadox\HydrationMapping\Test\Double\Constraint\ItIsNotMore;
use Stratadox\HydrationMapping\Test\Double\Constraint\LastNameIsLonger;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Check
 * @covers \Stratadox\Hydration\Mapping\Property\UnsatisfiedConstraint
 */
class Check_whether_the_data_is_valid extends TestCase
{
    use MockHydrator;

    /**
     * @test
     * @dataProvider validData
     */
    function allowing_valid_data_to_be_mapped_with($input)
    {
        $map = Check::that(
            ItIsNotLess::than(5)->and(ItIsNotMore::than(10)),
            IntegerValue::inProperty('foo')
        );

        $this->assertSame((int) $input, $map->value(['foo' => $input]));
    }

    /**
     * @test
     * @dataProvider invalidScalars
     */
    function banning_invalid_data_from_being_mapped_with($input)
    {
        $map = Check::that(
            ItIsNotLess::than(5)->and(ItIsNotMore::than(10)),
            StringValue::inProperty('foo')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign `' . $input . '` to property `foo`: ' .
            'The value did not satisfy the specifications.'
        );

        $map->value(['foo' => $input]);
    }

    /**
     * @test
     * @dataProvider illegalPersonNames
     */
    function banning_illegal_person_names_from_being_mapped_with($firstName, $lastName)
    {
        $map = Check::that(
            FirstNameIsLonger::than(1)->and(LastNameIsLonger::than(2)),
            HasOneEmbedded::inProperty('person',
                $this->mockPublicSetterHydratorForThe(Person::class)
            )
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Cannot assign the `' . Person::class . '` to property `person`: ' .
            'The value did not satisfy the specifications.'
        );

        $map->value([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
    }

    /** @test */
    function knowing_which_property_to_map_to()
    {
        $map = Check::that(ItIsNotMore::than(10), FloatValue::inProperty('foo'));

        $this->assertSame('foo', $map->name());
    }

    public function validData(): array
    {
        return [
            'foo is 5'  => ['5'],
            'foo is 6'  => ['6'],
            'foo is 7'  => ['7'],
            'foo is 8'  => ['8'],
            'foo is 9'  => ['9'],
            'foo is 10' => ['10'],
        ];
    }

    public function invalidScalars(): array
    {
        return [
            'foo is 0'  => ['0'],
            'foo is 1'  => ['1'],
            'foo is 2'  => ['2'],
            'foo is 3'  => ['3'],
            'foo is 4'  => ['4'],
            'foo is 11' => ['11'],
            'foo is 12' => ['12'],
            'foo is bar' => ['bar'],
        ];
    }

    public function illegalPersonNames(): array
    {
        return [
            'Mr X' => ['Mr', 'X'],
            'F U' => ['F', 'U'],
        ];
    }
}

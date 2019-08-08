<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property;

use function bcadd;
use function bcsub;
use const PHP_INT_MAX;
use const PHP_INT_MIN;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Defaults;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\Hydration\Mapping\Property\Type\IntegerValue;
use Stratadox\HydrationMapping\Test\Double\Deserializers;
use Stratadox\HydrationMapping\Test\Double\Title\Title;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Defaults
 */
class Defaults_when_an_element_could_not_be_mapped extends TestCase
{
    use Deserializers;

    /**
     * @test
     * @dataProvider nonIntegers
     */
    function converting_a_non_integer_to_minus_one_with($nonInteger)
    {
        $source = ['integer' => $nonInteger];

        $mapping = Defaults::to(-1, IntegerValue::inProperty('integer'));

        $this->assertSame(-1, $mapping->value($source));
    }

    /** @test */
    function converting_a_missing_field_to_minus_one()
    {
        $mapping = Defaults::to(-1, IntegerValue::inProperty('integer'));

        $this->assertSame(-1, $mapping->value([]));
    }

    /**
     * @test
     * @dataProvider integers
     */
    function using_the_original_mapping_if_that_works_out_with($integer)
    {
        $source = ['integer' => $integer];

        $mapping = Defaults::to(-1, IntegerValue::inProperty('integer'));

        $this->assertSame((int) $integer, $mapping->value($source));
    }

    /** @test */
    function using_a_default_object_if_the_data_could_not_be_mapped()
    {
        $mapping = Defaults::to(
            new Title('Unknown Title'),
            HasOneEmbedded::inProperty(
                'title',
                $this->exceptionThrowingDeserializer()
            )
        );

        $this->assertEquals(new Title('Unknown Title'), $mapping->value([]));
    }

    /** @test */
    function retrieving_which_property_to_map_to()
    {
        $mapping = Defaults::to(-1, IntegerValue::inProperty('integer'));

        $this->assertSame('integer', $mapping->name());
    }

    public function nonIntegers(): array
    {
        return [
            'String NaN'              => ['NaN'],
            'String 1.5'              => ['1.5'],
            'String 1.0'              => ['1.0'],
            'Float 1.5'               => [1.5],
            'Float 0.99'              => [0.99],
            'String foo'              => ['foo'],
            'String bar'              => ['bar'],
            'String PHP_INT_MAX + 1'  => [bcadd((string) PHP_INT_MAX, '1')],
            'String PHP_INT_MIN - 1'  => [bcsub((string) PHP_INT_MIN, '1')],
        ];
    }

    public function integers(): array
    {
        return [
            'String 1'            => ['1'],
            'String 0'            => ['0'],
            'String -1'           => ['-1'],
            'Integer 1'           => [1],
            'Integer 0'           => [0],
            'Integer -1'          => [-1],
            'String PHP_INT_MAX'  => [(string) PHP_INT_MAX],
            'String PHP_INT_MIN'  => [(string) PHP_INT_MIN],
            'Integer PHP_INT_MAX' => [PHP_INT_MAX],
            'Integer PHP_INT_MIN' => [PHP_INT_MIN],
        ];
    }
}

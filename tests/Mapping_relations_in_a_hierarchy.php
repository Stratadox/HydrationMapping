<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\ArrayDeserializer;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;
use Stratadox\Hydration\Mapping\Property\Relationship\HasManyNested;
use Stratadox\Hydration\Mapping\Property\Type\StringValue;
use Stratadox\HydrationMapping\Test\Double\Employee\Employee;
use Stratadox\HydrationMapping\Test\Double\Employee\Employer;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Hydrator\ObserveBefore;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Mapping relations in a hierarchy
 */
class Mapping_relations_in_a_hierarchy extends TestCase
{
    /** @test */
    function mapping_a_relationship_to_the_parent_element_in_a_hierarchy()
    {
        $reference = HasBackReference::inProperty('employer');
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Employer::class),
            ObserveBefore::hydrating(
                MappedHydrator::using(
                    ObjectHydrator::default(),
                    StringValue::inProperty('name'),
                    HasManyNested::inProperty(
                        'employees',
                        ArrayDeserializer::make(),
                        ObjectDeserializer::using(
                            ObjectInstantiator::forThe(Employee::class),
                            MappedHydrator::using(
                                ObjectHydrator::default(),
                                StringValue::inProperty('name'),
                                $reference
                            )
                        )
                    )
                ),
                $reference
            )
        );

        /** @var Employer $employer */
        $employer = $deserialize->from([
            'name' => 'Foobar inc.',
            'employees' => [
                ['name' => 'Foo'],
                ['name' => 'Bar'],
                ['name' => 'Baz'],
            ]
        ]);

        self::assertCount(3, $employer->employees());
        foreach ($employer->employees() as $employee) {
            self::assertSame($employer, $employee->employer());
        }
    }

    /** @test */
    function not_mapping_a_reference_to_a_parent_without_parents()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Employee::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                StringValue::inProperty('name'),
                HasBackReference::inProperty('employer')
            )
        );

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessage('employer');

        $deserialize->from(['name' => 'Foo']);
    }
}

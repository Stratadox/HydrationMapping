<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\ClosureMapping;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ReflectiveHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Mapping using custom functions
 */
class Mapping_using_custom_functions extends TestCase
{
    /** @test */
    function using_the_result_of_a_closure()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(stdClass::class),
            MappedHydrator::using(
                ReflectiveHydrator::default(),
                ClosureMapping::inProperty('hello', function (array $data) {
                    return $data['message'] . '!!! OMG!';
                })
            )
        );

        $foo = $deserialize->from(['message' => 'Hello World']);
        self::assertEquals('Hello World!!! OMG!', $foo->hello);
    }
}

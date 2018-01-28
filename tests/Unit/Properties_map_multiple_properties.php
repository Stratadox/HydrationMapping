<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Properties;
use Stratadox\Hydration\Mapping\Property\Scalar\StringValue;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;

/**
 * @covers \Stratadox\Hydration\Mapping\Properties
 */
class Properties_map_multiple_properties extends TestCase
{
    use MockHydrator;

    /** @scenario */
    function class_mappings_contain_property_mappings()
    {
        $propertyMapping = StringValue::inProperty('foo');
        $mapped = Properties::map(
            $propertyMapping
        );

        $setter = function (string $name, $value) {
            Assert::assertSame('foo', $name);
            Assert::assertSame('bar', $value);
        };

        $mapped->writeData($this, $setter, ['foo' => 'bar']);
    }
}
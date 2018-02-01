<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use StdClass;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\NoReferrerFound
 */
class HasBackReference_maps_bidirectional_relationships extends TestCase
{
    /** @scenario */
    function mapping_a_bidirectional_association()
    {
        $object = new StdClass;

        $mapping = HasBackReference::inProperty('foo');
        $mapping->hydrating($object);

        $this->assertSame(
            $object,
            $mapping->value([])
        );
    }

    /** @scenario */
    function mapping_to_a_property()
    {
        $mapping = HasBackReference::inProperty('foo');

        $this->assertSame(
            'foo',
            $mapping->name()
        );
    }

    /** @scenario */
    function throwing_an_exception_if_there_is_no_referrer()
    {
        $mapping = HasBackReference::inProperty('foo');

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionMessage(
            'Failed to reference back to the `foo` relationship: no referrer found.'
        );

        $mapping->value([]);
    }
}

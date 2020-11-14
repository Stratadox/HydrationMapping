<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use stdClass;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;
use Stratadox\HydrationMapping\MappingFailure;

class HasBackReference_maps_bidirectional_relationships extends TestCase
{
    /** @test */
    function remembering_the_observed_object()
    {
        $object = new stdClass;

        $mapping = HasBackReference::inProperty('foo');
        $mapping->hydrating($object, []);

        self::assertSame(
            $object,
            $mapping->value([])
        );
    }

    /** @test */
    function mapping_to_a_property()
    {
        $mapping = HasBackReference::inProperty('foo');

        self::assertSame(
            'foo',
            $mapping->name()
        );
    }

    /** @test */
    function throwing_an_exception_if_there_is_no_referrer()
    {
        $mapping = HasBackReference::inProperty('foo');

        $this->expectException(MappingFailure::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to reference back to the `foo` relationship: no referrer found.'
        );

        $mapping->value([]);
    }
}

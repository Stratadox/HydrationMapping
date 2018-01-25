<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference
 */
class HasBackReference_maps_back_to_the_owner extends TestCase
{
    /** @scenario */
    function mapping_a_bidirectional_association()
    {
        $mapping = HasBackReference::inProperty('foo');

        $this->assertSame(
            $this,
            $mapping->value([], $this)
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
}

<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StdClass;
use Stratadox\Hydration\Hydrates;
use Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasBackReference
 */
class HasBackReference_maps_bidirectional_relationships extends TestCase
{
    /** @scenario */
    function mapping_a_bidirectional_association()
    {
        $owner = new StdClass;

        /** @var MockObject|Hydrates $source */
        $source = $this->createMock(Hydrates::class);
        $source->expects($this->once())
            ->method('currentInstance')
            ->willReturn($owner);

        $mapping = HasBackReference::inProperty('foo');
        $mapping->setSource($source);

        $this->assertSame(
            $owner,
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

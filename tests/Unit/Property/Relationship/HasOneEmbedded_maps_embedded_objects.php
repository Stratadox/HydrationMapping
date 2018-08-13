<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\MockDeserializer;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasOneEmbedded
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\ObjectMappingFailed
 */
class HasOneEmbedded_maps_embedded_objects extends TestCase
{
    use MockDeserializer;

    /** @test */
    function mapping_part_of_a_flat_array_to_a_relationship()
    {
        $bookInformation = [
            'firstName' => 'Elle',
            'lastName'  => 'Garner',
            'title'     => 'Fruit Infused Water: 50 Quick & Easy Recipes for ' .
                'Delicious & Healthy Hydration'
        ];

        $authorMapping = HasOneEmbedded::inProperty('author',
            $this->deserializerForThe(Person::class)
        );

        /** @var Person $author */
        $author = $authorMapping->value($bookInformation);

        $this->assertInstanceOf(Person::class, $author);
    }

    /** @test */
    function property_mappers_know_which_property_they_map_to()
    {
        $authorMapping = HasOneEmbedded::inProperty('author',
            $this->deserializerForThe(Person::class)
        );

        $this->assertSame('author', $authorMapping->name());
    }

    /** @test */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasOneEmbedded::inProperty('foo',
            $this->exceptionThrowingDeserializer('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasOneEmbedded relation of the `foo` property: Original message here.'
        );

        $mapping->value(['bar']);
    }
}

<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Unit\Property\Relationship;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested;
use Stratadox\HydrationMapping\Test\Double\Person\Person;
use Stratadox\HydrationMapping\Test\Double\MockHydrator;
use Stratadox\HydrationMapping\UnmappableInput;

/**
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\HasOneNested
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\ObjectMappingFailed
 * @covers \Stratadox\Hydration\Mapping\Property\Relationship\KeyRequiring
 */
class HasOneNested_maps_nested_objects extends TestCase
{
    use MockHydrator;

    /** @test */
    function mapping_a_nested_array_to_a_HasOne_relationship()
    {
        $inAuthorData = [
            'author' => [
                'firstName' => 'Jules',
                'lastName' => 'Verne'
            ]
        ];

        $authorMapping = HasOneNested::inProperty('author',
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        /** @var Person $author */
        $author = $authorMapping->value($inAuthorData);

        $this->assertInstanceOf(Person::class, $author);
        $this->assertSame('Jules', $author->firstName());
        $this->assertSame('Verne', $author->lastName());
    }

    /** @test */
    function the_source_key_can_differ_from_the_property_name()
    {
        $inAuthorData = [
            'person' => [
                'firstName' => 'Jules',
                'lastName' => 'Verne'
            ]
        ];

        $authorMapping = HasOneNested::inPropertyWithDifferentKey('author',
            'person',
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        $author = $authorMapping->value($inAuthorData);

        $this->assertInstanceOf(Person::class, $author);
        $this->assertSame('author', $authorMapping->name());
    }

    /** @test */
    function throwing_an_informative_exception_when_the_source_is_missing()
    {
        $mapping = HasOneNested::inProperty('foo',
            $this->mockPublicSetterHydratorForThe(Person::class)
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Missing the key `foo` for property `foo` the input data: []; ' .
            'Mapper: ' .HasOneNested::class
        );

        $mapping->value([]);
    }

    /** @test */
    function throwing_an_informative_exception_when_the_items_cannot_be_mapped()
    {
        $mapping = HasOneNested::inProperty('foo',
            $this->mockExceptionThrowingHydrator('Original message here.')
        );

        $this->expectException(UnmappableInput::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage(
            'Failed to map the HasOneNested relation of the `foo` property: Original message here.'
        );

        $mapping->value(['foo' => []]);
    }
}

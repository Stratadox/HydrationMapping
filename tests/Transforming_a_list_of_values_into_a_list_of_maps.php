<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\ArrayDeserializer;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Relation\RelationCollectionMapping;
use Stratadox\Hydration\Mapping\Simple\Type\StringValue;
use Stratadox\Hydration\Mapping\Transform\MapTransform;
use Stratadox\Hydration\Mapping\Transform\NestingTransform;
use Stratadox\HydrationMapping\Test\Double\ItemList\Box;
use Stratadox\HydrationMapping\Test\Double\ItemList\Item;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Transforming a list of values into a list of maps
 */
class Transforming_a_list_of_values_into_a_list_of_maps extends TestCase
{
    /** @test */
    function mapping_a_list_of_names_to_a_box_with_items()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Box::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                MapTransform::withKey(
                    'name',
                    RelationCollectionMapping::inProperty(
                        'items',
                        ArrayDeserializer::make(),
                        ObjectDeserializer::using(
                            ObjectInstantiator::forThe(Item::class),
                            MappedHydrator::using(
                                ObjectHydrator::default(),
                                StringValue::inProperty('name')
                            )
                        )
                    )
                )
            )
        );

        /** @var Box $box */
        $box = $deserialize->from(['Lamp', 'Spoon', 'Flute']);

        self::assertCount(3, $box->items());
        self::assertSame('Lamp', $box->items()[0]->name());
        self::assertSame('Spoon', $box->items()[1]->name());
        self::assertSame('Flute', $box->items()[2]->name());
    }

    /** @test */
    function mapping_a_map_with_list_of_names_to_a_box_with_items()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Box::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                NestingTransform::inKey('items',
                    MapTransform::withKey('name',
                        RelationCollectionMapping::inProperty(
                            'items',
                            ArrayDeserializer::make(),
                            ObjectDeserializer::using(
                                ObjectInstantiator::forThe(Item::class),
                                MappedHydrator::using(
                                    ObjectHydrator::default(),
                                    StringValue::inProperty('name')
                                )
                            )
                        )
                    )
                )
            )
        );

        /** @var Box $box */
        $box = $deserialize->from(['items' => ['A', 'B', 'C']]);

        self::assertCount(3, $box->items());
        self::assertSame('A', $box->items()[0]->name());
        self::assertSame('B', $box->items()[1]->name());
        self::assertSame('C', $box->items()[2]->name());
    }
}

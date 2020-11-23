<?php declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test;

use PHPUnit\Framework\TestCase;
use Stratadox\Deserializer\ArrayDeserializer;
use Stratadox\Deserializer\DeserializationFailure;
use Stratadox\Deserializer\ObjectDeserializer;
use Stratadox\Hydration\Mapping\Relation\RelationCollectionMapping;
use Stratadox\Hydration\Mapping\Simple\Type\StringValue;
use Stratadox\Hydration\Mapping\Transform\JsonTransform;
use Stratadox\HydrationMapping\Test\Double\ItemList\Box;
use Stratadox\HydrationMapping\Test\Double\ItemList\Item;
use Stratadox\Hydrator\MappedHydrator;
use Stratadox\Hydrator\ObjectHydrator;
use Stratadox\Instantiator\ObjectInstantiator;

/**
 * @testdox Transforming json strings into usable data
 */
class Transforming_json_strings_into_usable_data extends TestCase
{
    /** @test */
    function transforming_a_json_string_to_a_box_with_items()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Box::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                JsonTransform::fromKey(
                    'json',
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
        $box = $deserialize->from(['json' => '[{"name":"Lamp"},{"name":"Spoon"},{"name":"Flute"}]']);

        self::assertCount(3, $box->items());
        self::assertSame('Lamp', $box->items()[0]->name());
        self::assertSame('Spoon', $box->items()[1]->name());
        self::assertSame('Flute', $box->items()[2]->name());
    }

    /** @test */
    function not_transforming_malformed_json_strings()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Box::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                JsonTransform::fromKey(
                    'json',
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

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessageRegExp('/(items).*(syntax error)/i');

        $deserialize->from(['json' => '[{"name":"Lamp"},']);
    }

    /** @test */
    function not_transforming_json_scalars()
    {
        $deserialize = ObjectDeserializer::using(
            ObjectInstantiator::forThe(Box::class),
            MappedHydrator::using(
                ObjectHydrator::default(),
                JsonTransform::fromKey(
                    'json',
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

        $this->expectException(DeserializationFailure::class);
        $this->expectExceptionMessageRegExp('/string/i');

        $deserialize->from(['json' => '"foo"']);
    }
}

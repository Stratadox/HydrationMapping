<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use function assert;
use Exception;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\DeserializesCollections;
use Stratadox\Deserializer\DeserializesObjects;
use Stratadox\Deserializer\ObjectDeserializer;

trait Deserializers
{
    /**
     * Makes a simple collection deserializer.
     *
     * @param string $class
     * @return DeserializesCollections
     */
    protected function collectionDeserializerForThe(string $class): DeserializesCollections
    {
        return CollectionDeserializer::forThe($class);
    }

    /**
     * Makes a simple object deserializer.
     *
     * @param string $class
     * @return DeserializesObjects
     */
    protected function deserializerForThe(string $class): DeserializesObjects
    {
        return ObjectDeserializer::forThe($class);
    }

    /**
     * Mocks a simple deserializer which will throw an @see UnmappableInput exception.
     *
     * @param string $message
     * @return DeserializesObjects
     */
    protected function exceptionThrowingDeserializer(string $message = ''): DeserializesObjects
    {
        $deserializer = $this->createMock(DeserializesObjects::class);

        $deserializer->expects($this->any())
            ->method('from')
            ->willReturnCallback(
                function () use ($message) {
                    throw new Exception($message);
                }
            );

        assert($deserializer instanceof DeserializesObjects);

        return $deserializer;
    }

    /**
     * Mocks a simple deserializer which will throw an @see UnmappableInput exception.
     *
     * @param string $message
     * @return DeserializesCollections
     */
    protected function exceptionThrowingCollectionDeserializer(string $message = ''): DeserializesCollections
    {
        $deserializer = $this->createMock(DeserializesCollections::class);

        $deserializer->expects($this->any())
            ->method('from')
            ->willReturnCallback(
                function () use ($message) {
                    throw new Exception($message);
                }
            );

        assert($deserializer instanceof DeserializesCollections);

        return $deserializer;
    }

    abstract public static function any(): AnyInvokedCount;

    abstract protected function createMock($originalClassName): MockObject;
}

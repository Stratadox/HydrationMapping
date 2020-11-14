<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use Stratadox\Deserializer\Deserializer;
use function assert;
use Exception;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use Stratadox\Deserializer\CollectionDeserializer;
use Stratadox\Deserializer\ObjectDeserializer;

trait Deserializers
{
    /**
     * Makes a simple collection deserializer.
     *
     * @param string $class
     * @return Deserializer
     */
    protected function immutableCollectionDeserializerFor(string $class): Deserializer
    {
        return CollectionDeserializer::forImmutable($class);
    }

    /**
     * Makes a simple object deserializer.
     *
     * @param string $class
     * @return Deserializer
     */
    protected function deserializerForThe(string $class): Deserializer
    {
        return ObjectDeserializer::forThe($class);
    }

    /**
     * Mocks a simple deserializer which will throw an @see UnmappableInput exception.
     *
     * @param string $message
     * @return Deserializer
     */
    protected function exceptionThrowingDeserializer(string $message = ''): Deserializer
    {
        $deserializer = $this->createMock(Deserializer::class);

        $deserializer
            ->method('from')
            ->willReturnCallback(
                function () use ($message) {
                    throw new Exception($message);
                }
            );

        assert($deserializer instanceof Deserializer);

        return $deserializer;
    }

    /**
     * Mocks a simple deserializer which will throw an @see UnmappableInput exception.
     *
     * @param string $message
     * @return Deserializer
     */
    protected function exceptionThrowingCollectionDeserializer(string $message = ''): Deserializer
    {
        $deserializer = $this->createMock(Deserializer::class);

        $deserializer
            ->method('from')
            ->willReturnCallback(
                function () use ($message) {
                    throw new Exception($message);
                }
            );

        assert($deserializer instanceof Deserializer);

        return $deserializer;
    }

    abstract public static function any(): AnyInvokedCount;

    abstract protected function createMock($originalClassName): MockObject;
}

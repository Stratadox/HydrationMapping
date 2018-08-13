<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use function assert;
use Exception;
use PHPUnit\Framework\MockObject\Exception as FailedToMock;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Stratadox\Deserializer\DeserializesCollections;
use Stratadox\Deserializer\DeserializesObjects;

trait MockDeserializer
{
    /**
     * Mocks a simple collection deserializer. It simply calls the constructor.
     *
     * @param string $class
     * @return DeserializesCollections|MockObject
     * @throws FailedToMock
     */
    protected function collectionDeserializerForThe(string $class): DeserializesCollections
    {
        $deserializer = $this->createMock(DeserializesCollections::class);

        $deserializer->expects($this->any())
            ->method('from')
            ->willReturnCallback(
                function (array $data) use ($class) {
                    return new $class(...array_values($data));
                }
            );

        assert($deserializer instanceof DeserializesCollections);

        return $deserializer;
    }

    /**
     * Mocks a simple dumb deserializer. It essentially just sets public properties.
     *
     * @param string $class
     * @return DeserializesObjects|MockObject
     * @throws FailedToMock
     */
    protected function deserializerForThe(string $class): DeserializesObjects
    {
        $deserializer = $this->createMock(DeserializesObjects::class);

        $deserializer->expects($this->any())
            ->method('from')
            ->willReturnCallback(
                function (array $data) use ($class) {
                    $inst = (new ReflectionClass($class))->newInstanceWithoutConstructor();
                    foreach ($data as $key => $value) {
                        $inst->$key = $value;
                    }
                    return $inst;
                }
            );

        assert($deserializer instanceof DeserializesObjects);

        return $deserializer;
    }

    /**
     * Mocks a simple deserializer which will throw an @see UnmappableInput exception.
     *
     * @param string $message
     * @return DeserializesObjects|MockObject
     * @throws FailedToMock
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
     * @return DeserializesCollections|MockObject
     * @throws FailedToMock
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

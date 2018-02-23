<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use Exception;
use LogicException;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Stratadox\Hydrator\Hydrates;

trait MockHydrator
{
    /**
     * Mocks a simple collection hydrator. It essentially just calls the constructor.
     *
     * @param string $class
     * @return Hydrates|MockObject
     */
    protected function mockCollectionHydratorForThe(string $class): Hydrates
    {
        $hydrator = $this->createMock(Hydrates::class);

        $hydrator->expects($this->any())
            ->method('fromArray')
            ->willReturnCallback(
                function (array $data) use ($class)
                {
                    return new $class(...array_values($data));
                }
            );

        if (!$hydrator instanceof Hydrates) {
            throw new LogicException;
        }

        return $hydrator;
    }

    /**
     * Mocks a simple dumb hydrator. It essentially just sets public properties.
     *
     * @param string $class
     * @return Hydrates|MockObject
     */
    protected function mockPublicSetterHydratorForThe(string $class): Hydrates
    {
        $hydrator = $this->createMock(Hydrates::class);

        $hydrator->expects($this->any())
            ->method('fromArray')
            ->willReturnCallback(
                function (array $data) use ($class)
                {
                    $inst = (new ReflectionClass($class))->newInstanceWithoutConstructor();
                    foreach ($data as $key => $value) {
                        $inst->$key = $value;
                    }
                    return $inst;
                }
            );

        if (!$hydrator instanceof Hydrates) {
            throw new LogicException;
        }

        return $hydrator;
    }

    /**
     * Mocks a simple hydrator which will throw an @see UnmappableInput exception.
     *
     * @param string $message
     * @return Hydrates|MockObject
     */
    protected function mockExceptionThrowingHydrator(string $message = ''): Hydrates
    {
        $hydrator = $this->createMock(Hydrates::class);

        $hydrator->expects($this->any())
            ->method('fromArray')
            ->willReturnCallback(
                function () use ($message)
                {
                    throw new Exception($message);
                }
            );

        if (!$hydrator instanceof Hydrates) {
            throw new LogicException;
        }

        return $hydrator;
    }

    abstract public static function any(): AnyInvokedCount;
    abstract protected function createMock($originalClassName): MockObject;
}

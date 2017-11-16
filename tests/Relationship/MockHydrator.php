<?php

declare(strict_types=1);

namespace Stratadox\Hydration\Test\Relationship;

use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount as AnyInvokedCount;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Stratadox\Hydration\Hydrates;

trait MockHydrator
{
    /**
     * Mocks a simple collection hydrator. It essentially just calls the constructor.
     *
     * @param string $class
     * @return Hydrates|MockObject
     */
    protected function mockCollectionHydratorForThe(string $class) : MockObject
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

        return $hydrator;
    }

    /**
     * Mocks a simple dumb hydrator. It essentially just sets public properties.
     *
     * @param string $class
     * @return Hydrates|MockObject
     */
    protected function mockPublicSetterHydratorForThe(string $class) : MockObject
    {
        $hydrator = $this->createMock(Hydrates::class);

        $hydrator->expects($this->any())
            ->method('fromArray')
            ->willReturnCallback(
                function (array $data) use ($class)
                {
                    $inst = (new \ReflectionClass($class))->newInstanceWithoutConstructor();
                    foreach ($data as $key => $value) {
                        $inst->$key = $value;
                    }
                    return $inst;
                }
            );

        return $hydrator;
    }

    /**
     * @return AnyInvokedCount
     */
    abstract public static function any();

    /**
     * @param string $originalClassName
     * @return MockObject
     */
    abstract protected function createMock($originalClassName);
}

<?php

namespace Stratadox\Hydration\Test\Relationship;

use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount as AnyInvokedCount;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Stratadox\Hydration\Hydrates;

trait MockHydrator
{
    /**
     * Extremely simple mock hydrator. It essentially just calls the constructor,
     * which is sufficient for our testing purposes.
     *
     * @param string $class
     * @return Hydrates|MockObject
     */
    protected function mockHydratorForThe(string $class) : MockObject
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
     * @return AnyInvokedCount
     */
    abstract public static function any();

    /**
     * @param string $originalClassName
     * @return MockObject
     */
    abstract protected function createMock($originalClassName);
}

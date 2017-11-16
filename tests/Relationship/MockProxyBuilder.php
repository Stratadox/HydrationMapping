<?php

namespace Stratadox\Hydration\Test\Relationship;

use PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount as AnyInvokedCount;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Stratadox\Hydration\ProducesProxies;

trait MockProxyBuilder
{
    /**
     * Mocks a simple proxy builder. It essentially just calls the constructor.
     *
     * @param string $class
     * @return ProducesProxies|MockObject
     */
    protected function mockProxyBuilderFor(string $class) : MockObject
    {
        $proxyBuilder = $this->createMock(ProducesProxies::class);

        $proxyBuilder->expects($this->any())
            ->method('createFor')
            ->willReturnCallback(
                function () use ($class)
                {
                    return new $class();
                }
            );

        return $proxyBuilder;
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

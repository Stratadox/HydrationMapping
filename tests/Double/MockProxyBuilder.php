<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use Exception;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use Stratadox\Proxy\ProducesProxies;

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
                function ($owner, $name, $position = null) use ($class)
                {
                    return new $class($owner, $name, $position);
                }
            );

        return $proxyBuilder;
    }

    /**
     * Mocks a simple proxy builder. It essentially just calls the constructor.
     *
     * @param string $message
     * @return ProducesProxies|MockObject
     */
    protected function mockExceptionThrowingProxyBuilder(string $message = '') : MockObject
    {
        $proxyBuilder = $this->createMock(ProducesProxies::class);

        $proxyBuilder->expects($this->any())
            ->method('createFor')
            ->willReturnCallback(function () use ($message) {
                throw new Exception($message);
            });

        return $proxyBuilder;
    }

    abstract public static function any() : AnyInvokedCount;
    abstract protected function createMock($originalClassName) : MockObject;
}

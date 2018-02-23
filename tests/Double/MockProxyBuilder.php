<?php

declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use Exception;
use LogicException;
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
    protected function mockProxyBuilderFor(string $class): ProducesProxies
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

        if (!$proxyBuilder instanceof ProducesProxies) {
            throw new LogicException;
        }

        return $proxyBuilder;
    }

    /**
     * Mocks a simple proxy builder. It essentially just calls the constructor.
     *
     * @param string $message
     * @return ProducesProxies|MockObject
     */
    protected function mockExceptionThrowingProxyBuilder(string $message = ''): ProducesProxies
    {
        $proxyBuilder = $this->createMock(ProducesProxies::class);

        $proxyBuilder->expects($this->any())
            ->method('createFor')
            ->willReturnCallback(function () use ($message) {
                throw new Exception($message);
            });

        if (!$proxyBuilder instanceof ProducesProxies) {
            throw new LogicException;
        }

        return $proxyBuilder;
    }

    abstract public static function any(): AnyInvokedCount;
    abstract protected function createMock($originalClassName): MockObject;
}

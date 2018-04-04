<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use function assert;
use Exception;
use PHPUnit\Framework\MockObject\Exception as FailedToMock;
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
     * @throws FailedToMock
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

        assert($proxyBuilder instanceof ProducesProxies);

        return $proxyBuilder;
    }

    /**
     * Mocks a simple proxy builder. It essentially just calls the constructor.
     *
     * @param string $message
     * @return ProducesProxies|MockObject
     * @throws FailedToMock
     */
    protected function mockExceptionThrowingProxyBuilder(string $message = ''): ProducesProxies
    {
        $proxyBuilder = $this->createMock(ProducesProxies::class);

        $proxyBuilder->expects($this->any())
            ->method('createFor')
            ->willReturnCallback(function () use ($message) {
                throw new Exception($message);
            });

        assert($proxyBuilder instanceof ProducesProxies);

        return $proxyBuilder;
    }

    abstract public static function any(): AnyInvokedCount;
    abstract protected function createMock($originalClassName): MockObject;
}

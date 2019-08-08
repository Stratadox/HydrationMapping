<?php
declare(strict_types=1);

namespace Stratadox\HydrationMapping\Test\Double;

use function assert;
use Exception;
use PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount;
use PHPUnit\Framework\MockObject\MockObject;
use Stratadox\HydrationMapping\Test\Double\Person\PersonProxyLoader;
use Stratadox\Proxy\BasicProxyFactory;
use Stratadox\Proxy\ProxyFactory;

trait ProxyFactories
{
    private $loader;

    /**
     * Mocks a simple proxy builder. It essentially just calls the constructor.
     *
     * @param string $class
     * @return ProxyFactory
     */
    protected function proxyFactoryFor(string $class): ProxyFactory
    {
        return BasicProxyFactory::for($class, $this->loader ?? new PersonProxyLoader());
    }

    /**
     * Mocks a simple proxy builder. It essentially just calls the constructor.
     *
     * @param string $message
     * @return ProxyFactory
     */
    protected function exceptionThrowingProxyFactory(string $message = ''): ProxyFactory
    {
        $proxyBuilder = $this->createMock(ProxyFactory::class);

        $proxyBuilder->expects($this->any())
            ->method('create')
            ->willReturnCallback(function () use ($message) {
                throw new Exception($message);
            });

        assert($proxyBuilder instanceof ProxyFactory);

        return $proxyBuilder;
    }

    abstract public static function any(): AnyInvokedCount;

    abstract protected function createMock($originalClassName): MockObject;
}

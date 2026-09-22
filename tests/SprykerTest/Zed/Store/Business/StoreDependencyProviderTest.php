<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Business;

use Codeception\Test\Unit;
use ReflectionMethod;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Store\Business\Model\Exception\CurrentStoreNotAvailableException;
use Spryker\Zed\Store\StoreDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Business
 * @group StoreDependencyProviderTest
 * Add your own group annotations below this line
 */
class StoreDependencyProviderTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    public function testResolveCurrentStoreThrowsClearExceptionWhenStoreApplicationServiceIsMissing(): void
    {
        // Arrange
        $storeDependencyProvider = new StoreDependencyProvider();
        $containerMock = $this->getMockBuilder(Container::class)->getMock();
        $containerMock->method('hasApplicationService')->with('store')->willReturn(false);

        // Assert
        $this->expectException(CurrentStoreNotAvailableException::class);
        $this->expectExceptionMessage('Backend Gateway');

        // Act
        $this->callResolveCurrentStore($storeDependencyProvider, $containerMock);
    }

    public function testResolveCurrentStoreReturnsResolvedStoreWhenStoreApplicationServiceIsPresent(): void
    {
        // Arrange
        $storeDependencyProvider = new StoreDependencyProvider();
        $containerMock = $this->getMockBuilder(Container::class)->getMock();
        $containerMock->method('hasApplicationService')->with('store')->willReturn(true);
        $containerMock->method('getApplicationService')->with('store')->willReturn(static::STORE_NAME);

        // Act
        $result = $this->callResolveCurrentStore($storeDependencyProvider, $containerMock);

        // Assert
        $this->assertSame(static::STORE_NAME, $result);
    }

    protected function callResolveCurrentStore(StoreDependencyProvider $storeDependencyProvider, Container $container): string
    {
        $reflectionMethod = new ReflectionMethod($storeDependencyProvider, 'getCurrentStoreFromApplicationService');
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod->invoke($storeDependencyProvider, $container);
    }
}

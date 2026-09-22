<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Store\Reader;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\Reader\StoreReader;
use Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Store
 * @group Reader
 * @group StoreReaderTest
 * Add your own group annotations below this line
 */
class StoreReaderTest extends Unit
{
    /**
     * A store that was never published to Storage (e.g. `isVisibleToCustomer=false`) has no
     * expander plugin able to resolve it — the transfer comes back with only the name that was
     * set before the expander loop ran, i.e. `idStore` stays null. That must surface as a
     * controlled "not found" error, not silently continue with an incomplete store.
     */
    public function testGetStoreByNameThrowsNotFoundHttpExceptionWhenNoExpanderPluginResolvesTheStore(): void
    {
        // Arrange
        $storeReader = new StoreReader([$this->createNoopExpanderPluginMock()]);

        // Assert
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Store not found.');

        // Act
        $storeReader->getStoreByName('UNPUBLISHED-STORE');
    }

    public function testGetStoreByNameReturnsTheStoreWhenAnExpanderPluginResolvesIt(): void
    {
        // Arrange
        $storeReader = new StoreReader([$this->createResolvingExpanderPluginMock(123)]);

        // Act
        $storeTransfer = $storeReader->getStoreByName('RESOLVABLE-STORE');

        // Assert
        $this->assertSame(123, $storeTransfer->getIdStore());
        $this->assertSame('RESOLVABLE-STORE', $storeTransfer->getName());
    }

    protected function createNoopExpanderPluginMock(): StoreExpanderPluginInterface
    {
        $storeExpanderPluginMock = $this->createMock(StoreExpanderPluginInterface::class);
        $storeExpanderPluginMock->method('expand')
            ->willReturnArgument(0);

        return $storeExpanderPluginMock;
    }

    protected function createResolvingExpanderPluginMock(int $idStore): StoreExpanderPluginInterface
    {
        $storeExpanderPluginMock = $this->createMock(StoreExpanderPluginInterface::class);
        $storeExpanderPluginMock->method('expand')
            ->willReturnCallback(
                fn (StoreTransfer $storeTransfer): StoreTransfer => $storeTransfer->setIdStore($idStore),
            );

        return $storeExpanderPluginMock;
    }
}

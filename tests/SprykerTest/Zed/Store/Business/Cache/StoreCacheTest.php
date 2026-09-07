<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Business\Cache;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\Cache\StoreCache;
use Spryker\Zed\Store\Business\Exception\StoreCacheNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Business
 * @group Cache
 * @group StoreCacheTest
 * Add your own group annotations below this line
 */
class StoreCacheTest extends Unit
{
    protected const string STORE_NAME = 'STORE_CACHE_TEST';

    protected const int ID_STORE = 9901;

    protected const string DEFAULT_LOCALE_ISO_CODE = 'en_US';

    protected const string OVERWRITTEN_LOCALE_ISO_CODE = 'de_DE';

    protected const string DEFAULT_CURRENCY_ISO_CODE = 'EUR';

    protected StoreCache $storeCache;

    protected function _before(): void
    {
        $this->storeCache = new StoreCache();
        $this->storeCache->clearCache();
    }

    public function testGivenCachedStoreWhenGivenTransferIsChangedAfterwardsThenCachedStoreKeepsItsValues(): void
    {
        // Arrange
        $storeTransfer = $this->createStoreTransfer();
        $this->storeCache->cacheStore($storeTransfer);

        // Act
        $storeTransfer->setDefaultLocaleIsoCode(static::OVERWRITTEN_LOCALE_ISO_CODE);

        // Assert
        $this->assertSame(
            static::DEFAULT_LOCALE_ISO_CODE,
            $this->storeCache->getStoreByStoreId(static::ID_STORE)->getDefaultLocaleIsoCode(),
        );
        $this->assertSame(
            static::DEFAULT_LOCALE_ISO_CODE,
            $this->storeCache->getStoreByStoreName(static::STORE_NAME)->getDefaultLocaleIsoCode(),
        );
    }

    public function testGivenCachedStoreWhenTheReturnedTransferIsChangedThenCachedStoreKeepsItsValues(): void
    {
        // Arrange
        $this->storeCache->cacheStore($this->createStoreTransfer());

        // Act
        $this->storeCache->getStoreByStoreId(static::ID_STORE)
            ->setDefaultLocaleIsoCode(static::OVERWRITTEN_LOCALE_ISO_CODE);
        $this->storeCache->getStoreByStoreName(static::STORE_NAME)
            ->setDefaultLocaleIsoCode(static::OVERWRITTEN_LOCALE_ISO_CODE);

        // Assert
        $this->assertSame(
            static::DEFAULT_LOCALE_ISO_CODE,
            $this->storeCache->getStoreByStoreId(static::ID_STORE)->getDefaultLocaleIsoCode(),
        );
        $this->assertSame(
            static::DEFAULT_LOCALE_ISO_CODE,
            $this->storeCache->getStoreByStoreName(static::STORE_NAME)->getDefaultLocaleIsoCode(),
        );
    }

    public function testGivenStoreTransferWhenItIsCachedThenCachedStoreHasTheSameValues(): void
    {
        // Arrange
        $storeTransfer = $this->createStoreTransfer();

        // Act
        $this->storeCache->cacheStore($storeTransfer);

        // Assert
        $this->assertSame(
            $storeTransfer->toArray(),
            $this->storeCache->getStoreByStoreId(static::ID_STORE)->toArray(),
        );
        $this->assertSame(
            $storeTransfer->toArray(),
            $this->storeCache->getStoreByStoreName(static::STORE_NAME)->toArray(),
        );
    }

    public function testGivenCachedStoreWhenCacheIsClearedThenTheStoreIsNoLongerCached(): void
    {
        // Arrange
        $this->storeCache->cacheStore($this->createStoreTransfer());

        // Act
        $this->storeCache->clearCache();

        // Assert
        $this->assertFalse($this->storeCache->hasStoreByStoreId(static::ID_STORE));
        $this->assertFalse($this->storeCache->hasStoreByStoreName(static::STORE_NAME));

        $this->expectException(StoreCacheNotFoundException::class);
        $this->storeCache->getStoreByStoreId(static::ID_STORE);
    }

    protected function createStoreTransfer(): StoreTransfer
    {
        return (new StoreTransfer())
            ->setIdStore(static::ID_STORE)
            ->setName(static::STORE_NAME)
            ->setDefaultLocaleIsoCode(static::DEFAULT_LOCALE_ISO_CODE)
            ->setDefaultCurrencyIsoCode(static::DEFAULT_CURRENCY_ISO_CODE)
            ->setAvailableLocaleIsoCodes([
                static::DEFAULT_LOCALE_ISO_CODE => static::DEFAULT_LOCALE_ISO_CODE,
                static::OVERWRITTEN_LOCALE_ISO_CODE => static::OVERWRITTEN_LOCALE_ISO_CODE,
            ]);
    }
}

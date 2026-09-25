<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Store\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use ReflectionProperty;
use Spryker\Zed\Store\Business\Cache\StoreCache;
use Spryker\Zed\Store\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\Store\Business\Model\StoreReader;
use Spryker\Zed\Store\Business\StoreBusinessFactory;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class StoreDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var string
     */
    protected const DEFAULT_STORE = 'DE';

    /**
     * @var string
     */
    protected const DEFAULT_STORE_AT = 'AT';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $this->clearStoreCache();
    }

    /**
     * @param array $storeOverride
     * @param bool $expandStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function haveStore(array $storeOverride = [], bool $expandStore = true)
    {
        if ($storeOverride === []) {
            $storeOverride = [StoreTransfer::NAME => static::DEFAULT_STORE];
        }

        $storeTransfer = (new StoreBuilder($storeOverride))->build();
        $storeEntity = $this->getStorePropelQuery()
            ->filterByName($storeTransfer->getName())
            ->findOneOrCreate();
        if (method_exists($storeEntity, 'setIsVisibleToCustomer')) {
            // StoreBuilder fills unset bool properties with a random Faker value (not null), so only
            // an EXPLICIT override may control this flag; otherwise the random roll would corrupt the
            // shared, persistent DE/AT fixture rows that other tests assume are visible.
            $isVisibleToCustomer = array_key_exists(StoreTransfer::IS_VISIBLE_TO_CUSTOMER, $storeOverride)
                ? $storeOverride[StoreTransfer::IS_VISIBLE_TO_CUSTOMER]
                : true;
            $storeEntity->setIsVisibleToCustomer($isVisibleToCustomer);
        }

        $storeEntity->save();

        $storeTransfer->setIdStore($storeEntity->getIdStore());

        if ($this->isDynamicStoreEnabled() && $expandStore) {
            $storeTransfer = $this->createStoreExpander()->expandStore($storeTransfer);

            (new StoreCache())->cacheStore($storeTransfer);
        }

        $this->getDataCleanupHelper()->addCleanup(function () use ($storeTransfer): void {
            $this->clearStoreCache();
        });

        return $storeTransfer;
    }

    public function getAllowedStore(): StoreTransfer
    {
        $storeTransfers = $this->getAllStores();

        if ($storeTransfers) {
            return $storeTransfers[0];
        }

        return new StoreTransfer();
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores(): array
    {
        return $this->getStoreFacade()->getAllStores();
    }

    public function getCurrentStore(): StoreTransfer
    {
        return $this->getStoreFacade()->getCurrentStore();
    }

    public function getCurrentStoreName(): string
    {
        return $this->getCurrentStore()->getNameOrFail();
    }

    public function getCurrentCurrencyCode(): string
    {
        return $this->getCurrentStore()->getDefaultCurrencyIsoCodeOrFail();
    }

    public function isDynamicStoreEnabled(): bool
    {
        return (bool)getenv('SPRYKER_DYNAMIC_STORE_MODE');
    }

    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getLocator()->store()->facade();
    }

    protected function createStoreExpander(): StoreExpanderInterface
    {
        return (new StoreBusinessFactory())->createStoreExpander();
    }

    protected function clearStoreCache(): void
    {
        $reflectionProperty = new ReflectionProperty(StoreCache::class, 'storeTransfersCacheByStoreId');
        $reflectionProperty->setValue(null, []);

        $reflectionProperty = new ReflectionProperty(StoreCache::class, 'storeTransferCacheByStoreName');
        ($reflectionProperty)->setValue(null, []);

        $reflectionProperty = new ReflectionProperty(StoreReader::class, 'memoryCache');
        ($reflectionProperty)->setValue(null, []);
    }

    public function countStores(): int
    {
        return $this->getStorePropelQuery()
            ->filterByFkLocale()
            ->count();
    }

    public function storeWithNameExists(string $name): bool
    {
        return $this->getStorePropelQuery()
            ->filterByName($name)
            ->exists();
    }

    protected function getStorePropelQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}

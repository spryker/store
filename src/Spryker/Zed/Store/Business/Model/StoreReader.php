<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Store\Business\Cache\StoreCacheInterface;
use Spryker\Zed\Store\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;
use Spryker\Zed\Store\Business\Reader\StoreReferenceReaderInterface;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;

class StoreReader implements StoreReaderInterface
{
    protected const string CACHE_KEY_ALL_STORES = 'all_stores';

    /**
     * @var array<string, mixed>
     */
    protected static array $memoryCache = [];

    /**
     * @var \Spryker\Zed\Store\Persistence\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Spryker\Zed\Store\Business\Cache\StoreCacheInterface
     */
    protected $storeCache;

    /**
     * @var \Spryker\Zed\Store\Business\Expander\StoreExpanderInterface
     */
    protected $storeExpander;

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @var bool
     */
    protected $isDynamicMultiStoreEnabled;

    /**
     * @var \Spryker\Zed\Store\Business\Reader\StoreReferenceReaderInterface
     */
    protected $storeReferenceReader;

    public function __construct(
        StoreRepositoryInterface $storeRepository,
        StoreCacheInterface $storeCache,
        StoreReferenceReaderInterface $storeReferenceReader,
        StoreExpanderInterface $storeExpander,
        bool $isDynamicMultiStoreEnabled
    ) {
        $this->storeRepository = $storeRepository;
        $this->storeCache = $storeCache;
        $this->storeExpander = $storeExpander;
        $this->isDynamicMultiStoreEnabled = $isDynamicMultiStoreEnabled;
        $this->storeReferenceReader = $storeReferenceReader;
    }

    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores()
    {
        if (isset(static::$memoryCache[static::CACHE_KEY_ALL_STORES])) {
            return static::$memoryCache[static::CACHE_KEY_ALL_STORES];
        }

        if (!$this->isDynamicMultiStoreEnabled) {
            $stores = $this->getStoreTransfersByStoreNames($this->getAllStoreNames());
            static::$memoryCache[static::CACHE_KEY_ALL_STORES] = $stores;

            return $stores;
        }

        $storeTransfers = $this->getStoreTransfersByStoreNames(
            array_filter($this->storeRepository->getStoreNamesByCriteria(new StoreCriteriaTransfer())),
        );

        $this->cacheStoreTransfers($storeTransfers);

        static::$memoryCache[static::CACHE_KEY_ALL_STORES] = $storeTransfers;

        return $storeTransfers;
    }

    /**
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore)
    {
        if ($this->storeCache->hasStoreByStoreId($idStore)) {
            return $this->storeCache->getStoreByStoreId($idStore);
        }

        $storeTransfer = $this->storeRepository
            ->findStoreById($idStore);

        if (!$storeTransfer) {
            throw new StoreNotFoundException(
                sprintf('Store with id "%s" not found!', $idStore),
            );
        }

        $storeTransfer = $this->storeExpander->expandStore($storeTransfer);
        $storeTransfer = $this->storeReferenceReader->extendStoreByStoreReference($storeTransfer);

        $this->storeCache->cacheStore($storeTransfer);

        return $storeTransfer;
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName)
    {
        if ($this->storeCache->hasStoreByStoreName($storeName)) {
            return $this->storeCache->getStoreByStoreName($storeName);
        }

        $storeTransfer = $this->storeRepository
            ->findStoreByName($storeName);

        if (!$storeTransfer) {
            throw new StoreNotFoundException(
                sprintf('Store with name "%s" not found!', $storeName),
            );
        }

        $storeTransfer = $this->storeExpander->expandStore($storeTransfer);
        $storeTransfer = $this->storeReferenceReader->extendStoreByStoreReference($storeTransfer);

        $this->storeCache->cacheStore($storeTransfer);

        return $storeTransfer;
    }

    public function findStoreByName(string $storeName): ?StoreTransfer
    {
        if ($this->storeCache->hasStoreByStoreName($storeName)) {
            return $this->storeCache->getStoreByStoreName($storeName);
        }

        if (!$this->storeRepository->storeExists($storeName)) {
            return null;
        }

        return $this->getStoreByName($storeName);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Store\Business\Model\StoreReader::getAllStores()} instead.
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer)
    {
        $stores = [];
        foreach ($storeTransfer->getStoresWithSharedPersistence() as $storeName) {
            $stores[] = $this->getStoreByName($storeName);
        }

        return $stores;
    }

    /**
     * @param array<string> $storeNames
     * @param bool $withExpanders
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoreTransfersByStoreNames(array $storeNames, bool $withExpanders = true): array
    {
        $storeNames = array_unique($storeNames);
        $unresolvedStoreNames = $this->getNotCachedStoreNames($storeNames);
        $resolvedStoreTransfers = $this->getStoresByStoreNamesFromCache(array_diff($storeNames, $unresolvedStoreNames));

        if ($unresolvedStoreNames) {
            $storeTransfers = $this->storeRepository->getStoreTransfersByStoreNames($unresolvedStoreNames);

            if ($withExpanders !== false) {
                $storeTransfers = $this->storeExpander->expandStores($storeTransfers);
            }

            $resolvedStoreTransfers = array_merge($resolvedStoreTransfers, $storeTransfers);
            $resolvedStoreTransfers = array_map(function (StoreTransfer $storeTransfer) {
                return $this->storeReferenceReader->extendStoreByStoreReference($storeTransfer);
            }, $resolvedStoreTransfers);

            if ($withExpanders !== false) {
                $this->cacheStoreTransfers($storeTransfers);
            }
        }

        return $resolvedStoreTransfers;
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $currentStoreTransfer
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoresAvailableForCurrentPersistence(StoreTransfer $currentStoreTransfer): array
    {
        if ($this->isDynamicMultiStoreEnabled) {
            return $this->getAllStores();
        }

        return array_merge([
            $currentStoreTransfer,
        ], $this->getStoresWithSharedPersistence($currentStoreTransfer));
    }

    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        $storeName = $this->storeReferenceReader->getStoreNameByStoreReference($storeReference);

        return $this->getStoreByName($storeName);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    protected function cacheStoreTransfers(array $storeTransfers): void
    {
        foreach ($storeTransfers as $storeTransfer) {
            $this->storeCache->cacheStore($storeTransfer);
        }
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<string>
     */
    protected function getNotCachedStoreNames(array $storeNames): array
    {
        $unresolvedStoreNames = [];

        foreach ($storeNames as $storeName) {
            if ($this->storeCache->hasStoreByStoreName($storeName)) {
                continue;
            }

            $unresolvedStoreNames[] = $storeName;
        }

        return $unresolvedStoreNames;
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoresByStoreNamesFromCache(array $storeNames): array
    {
        $resolvedStoreTransfers = [];

        foreach ($storeNames as $storeName) {
            if (!$this->storeCache->hasStoreByStoreName($storeName)) {
                continue;
            }

            $resolvedStoreTransfers[] = $this->storeCache->getStoreByStoreName($storeName);
        }

        return $resolvedStoreTransfers;
    }

    public function getStoreCollection(StoreCriteriaTransfer $storeCriteriaTransfer): StoreCollectionTransfer
    {
        $cacheKey = md5(serialize($storeCriteriaTransfer->toArray()));

        if (isset(static::$memoryCache[$cacheKey])) {
            return static::$memoryCache[$cacheKey];
        }

        $withExpanders = $storeCriteriaTransfer->getStoreConditions() && $storeCriteriaTransfer->getStoreConditions()->getWithExpanders() !== null
            ? $storeCriteriaTransfer->getStoreConditions()->getWithExpanders()
            : true;

        $storeCollectionTransfer = (new StoreCollectionTransfer())->setStores(new ArrayObject(
            $this->getStoreTransfersByStoreNames(
                $this->storeRepository->getStoreNamesByCriteria($storeCriteriaTransfer),
                $withExpanders,
            ),
        ));

        static::$memoryCache[$cacheKey] = $storeCollectionTransfer;

        return $storeCollectionTransfer;
    }

    public function clearMemoryCache(): void
    {
        static::$memoryCache = [];

        $this->storeCache->clearCache();
    }

    /**
     * @deprecated Will be removed after dynamic multi-store is always enabled.
     *
     * @return array<string>
     */
    protected function getAllStoreNames(): array
    {
        return Store::getInstance()->getAllowedStores();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Cache;

use Generated\Shared\Transfer\StoreTransfer;

interface StoreCacheInterface
{
    public function hasStoreByStoreId(int $idStore): bool;

    public function hasStoreByStoreName(string $storeName): bool;

    public function cacheStore(StoreTransfer $storeTransfer): void;

    public function getStoreByStoreId(int $idStore): StoreTransfer;

    public function getStoreByStoreName(string $storeName): StoreTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreRepositoryInterface
{
    public function storeExists(string $name): bool;

    public function findStoreByName(string $storeName): ?StoreTransfer;

    public function findStoreById(int $idStore): ?StoreTransfer;

    /**
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getStoreTransfersByStoreNames(array $storeNames): array;

    /**
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return array<string>
     */
    public function getStoreNamesByCriteria(StoreCriteriaTransfer $storeCriteriaTransfer): array;
}

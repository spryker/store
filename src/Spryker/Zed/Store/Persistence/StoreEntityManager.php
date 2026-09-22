<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Persistence;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Store\Persistence\StorePersistenceFactory getFactory()
 */
class StoreEntityManager extends AbstractEntityManager implements StoreEntityManagerInterface
{
    public function createStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeEntity = (new SpyStore())
            ->setName($storeTransfer->getNameOrFail());

        if ($this->isVisibleToCustomerSupported()) {
            $storeEntity->setIsVisibleToCustomer($storeTransfer->getIsVisibleToCustomer() ?? true);
        }

        $storeEntity->save();

        $storeTransfer->setIdStore($storeEntity->getIdStore());

        return $storeTransfer;
    }

    public function updateStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        $storeQuery = $this->getFactory()
            ->createStoreQuery()
            ->requirePk($storeTransfer->getIdStoreOrFail())
            ->setName($storeTransfer->getNameOrFail());

        if ($storeTransfer->getIsVisibleToCustomer() !== null && $this->isVisibleToCustomerSupported()) {
            $storeQuery->setIsVisibleToCustomer($storeTransfer->getIsVisibleToCustomer());
        }

        $storeQuery->save();

        return $storeTransfer;
    }

    protected function isVisibleToCustomerSupported(): bool
    {
        return defined(SpyStoreTableMap::class . '::COL_IS_VISIBLE_TO_CUSTOMER');
    }
}

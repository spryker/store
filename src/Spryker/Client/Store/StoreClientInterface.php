<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store;

use Generated\Shared\Transfer\StoreCollectionTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreClientInterface
{
    /**
     * Specification:
     * - Retrieves the current Store as a transfer object.
     * - Throws when the current store could not be resolved (e.g. it was never published to Storage).
     *
     * @api
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();

    /**
     * Specification:
     * - Retrieves a Store as a transfer object.
     * - Executes stack of {@link \Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface} plugins.
     * - Throws when the requested store could not be resolved (e.g. it was never published to Storage).
     *
     * @api
     *
     * @param string $storeName
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName(string $storeName): StoreTransfer;

    /**
     * Specification:
     * - Returns true if dynamic store functionality enabled.
     *
     * @api
     *
     * @return bool
     */
    public function isDynamicStoreEnabled(): bool;

    /**
     * Specification:
     * - Returns true if the current store is provided in the application.
     *
     * @api
     *
     * @return bool
     */
    public function isCurrentStoreDefined(): bool;

    /**
     * Specification:
     * - Retrieves filtered stores using StoreCriteriaTransfer.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\StoreStorage\StoreStorageClientInterface::getStoreNames()} instead.
     *
     * @param \Generated\Shared\Transfer\StoreCriteriaTransfer $storeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreCollectionTransfer
     */
    public function getStoreCollection(StoreCriteriaTransfer $storeCriteriaTransfer): StoreCollectionTransfer;
}

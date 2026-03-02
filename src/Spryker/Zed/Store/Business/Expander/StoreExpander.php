<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Store\Reader\StoreReaderInterface;

/**
 * @deprecated Will be removed after dynamic multi-store is always enabled.
 */
class StoreExpander implements StoreExpanderInterface
{
    /**
     * @var \Spryker\Shared\Store\Reader\StoreReaderInterface
     */
    protected $sharedStoreReader;

    public function __construct(StoreReaderInterface $sharedStoreReader)
    {
        $this->sharedStoreReader = $sharedStoreReader;
    }

    public function expandStore(StoreTransfer $storeTransfer): StoreTransfer
    {
        $idStore = $storeTransfer->getIdStore();

        $storeTransfer->fromArray(
            $this->sharedStoreReader->getStoreByName($storeTransfer->getNameOrFail())->toArray(),
            true,
        );

        $storeTransfer->setDefaultLocaleIsoCode(Store::getInstance()->getCurrentLocale());

        return $storeTransfer->setIdStore($idStore);
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStores(array $storeTransfers): array
    {
        foreach ($storeTransfers as $key => $storeTransfer) {
            $storeTransfers[$key] = $this->expandStore($storeTransfer);
        }

        return $storeTransfers;
    }
}

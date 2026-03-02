<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Reader;

use Generated\Shared\Transfer\StoreTransfer;

/**
 * @deprecated Will be removed without replacement.
 */
interface StoreReferenceReaderInterface
{
    public function getStoreNameByStoreReference(string $storeReference): string;

    public function extendStoreByStoreReference(StoreTransfer $storeTransfer): StoreTransfer;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Reader;

use Generated\Shared\Transfer\StoreTransfer;

interface StoreReaderInterface
{
    public function getStoreByName(string $storeName): StoreTransfer;
}

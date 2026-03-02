<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Writer;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreWriterInterface
{
    public function createStore(StoreTransfer $storeTransfer): StoreResponseTransfer;

    public function updateStore(StoreTransfer $storeTransfer): StoreResponseTransfer;
}

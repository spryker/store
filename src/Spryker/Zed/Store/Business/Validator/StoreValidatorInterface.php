<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Validator;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreValidatorInterface
{
    public function validatePreCreate(StoreTransfer $storeTransfer): StoreResponseTransfer;

    public function validatePreUpdate(StoreTransfer $storeTransfer): StoreResponseTransfer;

    public function validateStoreNameIsUnique(StoreTransfer $storeTransfer): StoreResponseTransfer;
}

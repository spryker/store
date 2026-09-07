<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Validator;

use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;

class CustomerStoreValidator implements CustomerStoreValidatorInterface
{
    protected const string ERROR_MESSAGE_STORE_UNKNOWN = 'store.validation.unknown_store';

    public function __construct(protected StoreRepositoryInterface $storeRepository)
    {
    }

    public function validateCustomerStore(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        $customerResponseTransfer = (new CustomerResponseTransfer())
            ->setCustomerTransfer($customerTransfer)
            ->setIsSuccess(true);

        $storeName = $customerTransfer->getStoreName();

        if (!$storeName || $this->storeRepository->findStoreByName($storeName) !== null) {
            return $customerResponseTransfer;
        }

        return $customerResponseTransfer
            ->setIsSuccess(false)
            ->addError((new CustomerErrorTransfer())->setMessage(static::ERROR_MESSAGE_STORE_UNKNOWN));
    }
}

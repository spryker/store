<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\Customer;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\CustomerExtension\Dependency\Plugin\CustomerValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Store\Business\StoreBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreCustomerValidatorPlugin extends AbstractPlugin implements CustomerValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Validates that the store assigned to the customer exists.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function validate(CustomerTransfer $customerTransfer): CustomerResponseTransfer
    {
        return $this->getBusinessFactory()
            ->createCustomerStoreValidator()
            ->validateCustomerStore($customerTransfer);
    }
}

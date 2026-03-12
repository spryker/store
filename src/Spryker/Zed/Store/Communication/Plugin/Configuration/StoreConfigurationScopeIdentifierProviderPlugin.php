<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\Configuration;

use Spryker\Shared\Store\StoreConstants;
use Spryker\Zed\ConfigurationExtension\Dependency\Plugin\ConfigurationScopeIdentifierProviderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 */
class StoreConfigurationScopeIdentifierProviderPlugin extends AbstractPlugin implements ConfigurationScopeIdentifierProviderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getScopeKey(): string
    {
        return StoreConstants::SCOPE_STORE;
    }

    /**
     * {@inheritDoc}
     * - Returns list of store names.
     *
     * @api
     *
     * @return array<string>
     */
    public function getIdentifiers(): array
    {
        $stores = $this->getFacade()->getAllStores();

        return array_map(fn ($store) => $store->getNameOrFail(), $stores);
    }
}

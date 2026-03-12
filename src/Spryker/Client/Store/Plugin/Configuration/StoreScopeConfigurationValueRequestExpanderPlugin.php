<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Plugin\Configuration;

use Generated\Shared\Transfer\ConfigurationScopeTransfer;
use Generated\Shared\Transfer\ConfigurationValueRequestTransfer;
use Spryker\Client\ConfigurationExtension\Dependency\Plugin\ConfigurationValueRequestExpanderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Store\StoreConstants;

/**
 * @method \Spryker\Client\Store\StoreClientInterface getClient()
 */
class StoreScopeConfigurationValueRequestExpanderPlugin extends AbstractPlugin implements ConfigurationValueRequestExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands Configuration request with the current store as store scope if it is not provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurationValueRequestTransfer $configurationValueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurationValueRequestTransfer
     */
    public function expand(ConfigurationValueRequestTransfer $configurationValueRequestTransfer): ConfigurationValueRequestTransfer
    {
        foreach ($configurationValueRequestTransfer->getScopes() as $scope) {
            if ($scope->getKey() === StoreConstants::SCOPE_STORE) {
                return $configurationValueRequestTransfer;
            }
        }

        return $configurationValueRequestTransfer->addScope(
            (new ConfigurationScopeTransfer())
                ->setKey(StoreConstants::SCOPE_STORE)
                ->setIdentifier($this->getClient()->getCurrentStore()->getName()),
        );
    }
}

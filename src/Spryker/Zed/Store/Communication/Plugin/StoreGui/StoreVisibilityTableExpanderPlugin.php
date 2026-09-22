<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 */
class StoreVisibilityTableExpanderPlugin extends AbstractPlugin implements StoreTableExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds the "Visible on Storefront" column header to the Store table.
     *
     * @api
     *
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        return $this->getFactory()
            ->createStoreVisibilityTableExpander()
            ->expandConfig($config);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function expandStoreTransfers(array $storeTransfers): array
    {
        return $storeTransfers;
    }

    /**
     * {@inheritDoc}
     * - Fills the "Visible on Storefront" column value for each row of the Store table.
     *
     * @api
     *
     * @param array<mixed> $storeDataItem
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<mixed>
     */
    public function expandDataItem(array $storeDataItem, StoreTransfer $storeTransfer): array
    {
        return $this->getFactory()
            ->createStoreVisibilityTableExpander()
            ->expandDataItem($storeDataItem, $storeTransfer);
    }
}

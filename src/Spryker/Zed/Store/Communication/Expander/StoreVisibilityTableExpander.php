<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;

class StoreVisibilityTableExpander implements StoreVisibilityTableExpanderInterface
{
    protected const string COL_IS_VISIBLE_TO_CUSTOMER = 'is_visible_to_customer';

    protected const string VALUE_COL_IS_VISIBLE_TO_CUSTOMER = 'Visible to Customer';

    public function __construct(protected StoreRepositoryInterface $storeRepository)
    {
    }

    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        if (!$this->storeRepository->isVisibleToCustomerSupported()) {
            return $config;
        }

        $config->setHeader($config->getHeader() + [
            static::COL_IS_VISIBLE_TO_CUSTOMER => static::VALUE_COL_IS_VISIBLE_TO_CUSTOMER,
        ]);

        return $config;
    }

    /**
     * @param array<string, mixed> $storeDataItem Table row being assembled column key => rendered value; heterogeneous by column.
     *
     * @return array<string, mixed>
     */
    public function expandDataItem(array $storeDataItem, StoreTransfer $storeTransfer): array
    {
        if (!$this->storeRepository->isVisibleToCustomerSupported()) {
            return $storeDataItem;
        }

        $storeDataItem[static::COL_IS_VISIBLE_TO_CUSTOMER] = $storeTransfer->getIsVisibleToCustomer() !== false ? 'Yes' : 'No';

        return $storeDataItem;
    }
}

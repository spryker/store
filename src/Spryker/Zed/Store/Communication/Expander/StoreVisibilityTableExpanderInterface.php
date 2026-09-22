<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

interface StoreVisibilityTableExpanderInterface
{
    public function expandConfig(TableConfiguration $config): TableConfiguration;

    /**
     * @param array<string, mixed> $storeDataItem Table row being assembled column key => rendered value; heterogeneous by column.
     *
     * @return array<string, mixed>
     */
    public function expandDataItem(array $storeDataItem, StoreTransfer $storeTransfer): array;
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Communication\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Store\Communication\Expander\StoreVisibilityTableExpander;
use Spryker\Zed\Store\Persistence\StoreRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Communication
 * @group Expander
 * @group StoreVisibilityTableExpanderTest
 * Add your own group annotations below this line
 */
class StoreVisibilityTableExpanderTest extends Unit
{
    public function testExpandConfigAddsVisibilityColumnHeader(): void
    {
        // Arrange
        $config = new TableConfiguration();
        $config->setHeader(['name' => 'Name']);
        $expander = new StoreVisibilityTableExpander(new StoreRepository());

        // Act
        $config = $expander->expandConfig($config);

        // Assert
        $this->assertArrayHasKey('is_visible_to_customer', $config->getHeader());
    }

    public function testExpandDataItemAddsYesForVisibleStore(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setIsVisibleToCustomer(true);
        $expander = new StoreVisibilityTableExpander(new StoreRepository());

        // Act
        $item = $expander->expandDataItem([], $storeTransfer);

        // Assert
        $this->assertSame('Yes', $item['is_visible_to_customer']);
    }

    public function testExpandDataItemAddsNoForHiddenStore(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setIsVisibleToCustomer(false);
        $expander = new StoreVisibilityTableExpander(new StoreRepository());

        // Act
        $item = $expander->expandDataItem([], $storeTransfer);

        // Assert
        $this->assertSame('No', $item['is_visible_to_customer']);
    }
}

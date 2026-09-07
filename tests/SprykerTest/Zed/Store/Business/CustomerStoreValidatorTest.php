<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Store\Communication\Plugin\Customer\StoreCustomerValidatorPlugin;
use SprykerTest\Zed\Store\StoreBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Business
 * @group CustomerStoreValidatorTest
 * Add your own group annotations below this line
 */
class CustomerStoreValidatorTest extends Unit
{
    protected const string UNKNOWN_STORE_NAME = 'NotAStore';

    protected StoreBusinessTester $tester;

    public function testValidateCustomerStoreAcceptsAConfiguredStore(): void
    {
        // Arrange
        $storeTransfer = $this->tester->haveStore();
        $customerTransfer = (new CustomerTransfer())->setStoreName($storeTransfer->getNameOrFail());

        // Act
        $customerResponseTransfer = (new StoreCustomerValidatorPlugin())->validate($customerTransfer);

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
        $this->assertCount(0, $customerResponseTransfer->getErrors());
    }

    public function testValidateCustomerStoreAcceptsACustomerWithoutAStore(): void
    {
        // Act — the store is optional on a customer.
        $customerResponseTransfer = (new StoreCustomerValidatorPlugin())->validate(new CustomerTransfer());

        // Assert
        $this->assertTrue($customerResponseTransfer->getIsSuccess());
    }

    public function testValidateCustomerStoreRejectsAStoreThatIsNotConfigured(): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())->setStoreName(static::UNKNOWN_STORE_NAME);

        // Act
        $customerResponseTransfer = (new StoreCustomerValidatorPlugin())->validate($customerTransfer);

        // Assert
        $this->assertFalse($customerResponseTransfer->getIsSuccess());
        $this->assertCount(1, $customerResponseTransfer->getErrors());
        $this->assertNotEmpty($customerResponseTransfer->getErrors()->offsetGet(0)->getMessage());
    }
}

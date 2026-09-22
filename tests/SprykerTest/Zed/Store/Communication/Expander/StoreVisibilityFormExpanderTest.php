<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store\Communication\Expander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Communication\Expander\StoreVisibilityFormExpander;
use Spryker\Zed\Store\Persistence\StoreRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Forms;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Store
 * @group Communication
 * @group Expander
 * @group StoreVisibilityFormExpanderTest
 * Add your own group annotations below this line
 */
class StoreVisibilityFormExpanderTest extends Unit
{
    public function testExpandAddsIsVisibleToCustomerFieldWhenSchemaSupportsIt(): void
    {
        // Arrange
        $storeTransfer = (new StoreTransfer())->setIsVisibleToCustomer(false);
        $expander = new StoreVisibilityFormExpander(new StoreRepository());

        // Act
        $form = $expander->expand($this->createFormBuilder($storeTransfer), $storeTransfer)->getForm();

        // Assert
        $this->assertTrue($form->has('isVisibleToCustomer'));
        $this->assertFalse($form->get('isVisibleToCustomer')->getData());
    }

    /**
     * A brand-new store transfer has no `isVisibleToCustomer` value yet — the checkbox must default
     * to checked so the UI doesn't misrepresent a not-yet-saved store as hidden.
     */
    public function testExpandDefaultsCheckboxToCheckedWhenStoreTransferHasNoValueYet(): void
    {
        // Arrange
        $storeTransfer = new StoreTransfer();
        $expander = new StoreVisibilityFormExpander(new StoreRepository());

        // Act
        $form = $expander->expand($this->createFormBuilder($storeTransfer), $storeTransfer)->getForm();

        // Assert
        $this->assertTrue($form->get('isVisibleToCustomer')->getData());
    }

    protected function createFormBuilder(StoreTransfer $storeTransfer)
    {
        return Forms::createFormFactoryBuilder()
            ->getFormFactory()
            ->createBuilder(FormType::class, $storeTransfer, ['data_class' => StoreTransfer::class]);
    }
}

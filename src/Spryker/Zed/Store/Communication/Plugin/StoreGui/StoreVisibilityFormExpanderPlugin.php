<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\Store\Communication\StoreCommunicationFactory getFactory()
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 */
class StoreVisibilityFormExpanderPlugin extends AbstractPlugin implements StoreFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds the "Visible on Storefront" checkbox to the Store form.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface> $builder
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<string, \Symfony\Component\Form\FormBuilderInterface>
     */
    public function expand(FormBuilderInterface $builder, StoreTransfer $storeTransfer): FormBuilderInterface
    {
        return $this->getFactory()
            ->createStoreVisibilityFormExpander()
            ->expand($builder, $storeTransfer);
    }
}

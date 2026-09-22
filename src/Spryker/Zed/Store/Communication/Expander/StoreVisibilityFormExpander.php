<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class StoreVisibilityFormExpander implements StoreVisibilityFormExpanderInterface
{
    protected const string FIELD_IS_VISIBLE_TO_CUSTOMER = 'isVisibleToCustomer';

    protected const string LABEL_IS_VISIBLE_TO_CUSTOMER = 'Visible to Customer';

    protected const string HELP_IS_VISIBLE_TO_CUSTOMER = 'When disabled, the store is hidden from customers on the storefront (Yves) and in public Glue APIs until you enable it here.';

    public function __construct(protected StoreRepositoryInterface $storeRepository)
    {
    }

    public function expand(FormBuilderInterface $builder, StoreTransfer $storeTransfer): FormBuilderInterface
    {
        if (!$this->storeRepository->isVisibleToCustomerSupported()) {
            return $builder;
        }

        $builder->add(static::FIELD_IS_VISIBLE_TO_CUSTOMER, CheckboxType::class, [
            'label' => static::LABEL_IS_VISIBLE_TO_CUSTOMER,
            'required' => false,
            'help' => static::HELP_IS_VISIBLE_TO_CUSTOMER,
            'data' => $storeTransfer->getIsVisibleToCustomer() ?? true,
        ]);

        return $builder;
    }
}

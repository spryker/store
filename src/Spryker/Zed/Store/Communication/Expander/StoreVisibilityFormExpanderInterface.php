<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Expander;

use Generated\Shared\Transfer\StoreTransfer;
use Symfony\Component\Form\FormBuilderInterface;

interface StoreVisibilityFormExpanderInterface
{
    public function expand(FormBuilderInterface $builder, StoreTransfer $storeTransfer): FormBuilderInterface;
}

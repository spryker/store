<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface;

/**
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 */
class QuoteStoreValidatorPlugin implements QuoteValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validates if provided store in quote is available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        return $this->getFacade()->validateQuoteStore($quoteTransfer);
    }
}

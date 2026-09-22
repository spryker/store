<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Store\Communication\Expander\StoreVisibilityFormExpander;
use Spryker\Zed\Store\Communication\Expander\StoreVisibilityFormExpanderInterface;
use Spryker\Zed\Store\Communication\Expander\StoreVisibilityTableExpander;
use Spryker\Zed\Store\Communication\Expander\StoreVisibilityTableExpanderInterface;
use Spryker\Zed\Store\Communication\Form\DataProvider\StoreRelationToggleDataProvider;
use Spryker\Zed\Store\Communication\Form\Transformer\IdStoresDataTransformer;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Business\StoreFacadeInterface getFacade()
 * @method \Spryker\Zed\Store\Persistence\StoreRepositoryInterface getRepository()
 * @method \Spryker\Zed\Store\Persistence\StoreEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\DataTransformerInterface<array<int, int>|null, string>
     */
    public function createIdStoresDataTransformer()
    {
        return new IdStoresDataTransformer();
    }

    /**
     * @return \Spryker\Zed\Store\Communication\Form\DataProvider\StoreRelationToggleDataProviderInterface
     */
    public function createStoreRelationToggleDataProvider()
    {
        return new StoreRelationToggleDataProvider($this->getFacade());
    }

    public function createStoreVisibilityFormExpander(): StoreVisibilityFormExpanderInterface
    {
        return new StoreVisibilityFormExpander($this->getRepository());
    }

    public function createStoreVisibilityTableExpander(): StoreVisibilityTableExpanderInterface
    {
        return new StoreVisibilityTableExpander($this->getRepository());
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Store\Reader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\Store\Reader\StoreReaderInterface as ClientStoreReaderInterface;
use Spryker\Client\Store\StoreDependencyProvider;
use Spryker\Service\Container\Attributes\Stack;
use Spryker\Shared\Store\Reader\StoreReaderInterface;

class StoreReader implements StoreReaderInterface, ClientStoreReaderInterface
{
    /**
     * @var array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected static $storeCache = [];

    /**
     * @param array<\Spryker\Client\StoreExtension\Dependency\Plugin\StoreExpanderPluginInterface> $storeExpanderPlugins
     */
    #[Stack(
        dependencyProvider: StoreDependencyProvider::class,
        dependencyProviderMethod: 'getStoreExpanderPlugins',
        provideToArgument: 'storeExpanderPlugins',
    )]
    public function __construct(protected array $storeExpanderPlugins)
    {
    }

    /**
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName): StoreTransfer
    {
        if (array_key_exists($storeName, static::$storeCache)) {
            return static::$storeCache[$storeName];
        }

        $storeTransfer = (new StoreTransfer())
            ->setName($storeName);

        foreach ($this->storeExpanderPlugins as $storeExpanderPlugin) {
            $storeTransfer = $storeExpanderPlugin->expand($storeTransfer);
        }

        static::$storeCache[$storeName] = $storeTransfer;

        return $storeTransfer;
    }
}

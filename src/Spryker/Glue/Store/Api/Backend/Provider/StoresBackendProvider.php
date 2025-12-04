<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\Store\Api\Backend\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Generated\Api\Backend\StoresBackendResource;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @implements \ApiPlatform\State\ProviderInterface<\Generated\Api\Backend\StoresBackendResource>
 */
class StoresBackendProvider implements ProviderInterface
{
    public function __construct(protected StoreFacadeInterface $storeFacade)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $name = $uriVariables['name'] ?? null;

        if ($name === null) {
            return $this->provideCollection($context);
        }

        $storeTransfer = $this->storeFacade->getStoreByName($name);
        /** @var \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer */

        if ($storeTransfer === null) {
            return null;
        }

        return $this->mapTransferToResource($storeTransfer);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<\Generated\Api\Backend\StoresBackendResource>
     */
    protected function provideCollection(array $context): array
    {
        $storeTransfers = $this->storeFacade->getAllStores();
        $resources = [];

        foreach ($storeTransfers as $storeTransfer) {
            $resources[] = $this->mapTransferToResource($storeTransfer);
        }

        return $resources;
    }

    /**
     * @phpstan-param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @phpstan-return \Generated\Api\Backend\StoresBackendResource
     */
    protected function mapTransferToResource(StoreTransfer $storeTransfer): StoresBackendResource
    {
        return StoresBackendResource::fromArray($storeTransfer->toArray());
    }
}

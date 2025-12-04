<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\Store\Api\Backend\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Generated\Api\Backend\StoresBackendResource;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @implements \ApiPlatform\State\ProcessorInterface<\Generated\Api\Backend\StoresBackendResource, \Generated\Api\Backend\StoresBackendResource>
 */
class StoresBackendProcessor implements ProcessorInterface
{
    public function __construct(protected StoreFacadeInterface $storeFacade)
    {
    }

    /**
     * @param \Generated\Api\Backend\StoresBackendResource|mixed $data
     * @param \ApiPlatform\Metadata\Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return \Generated\Api\Backend\StoresBackendResource|mixed
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $storeTransfer = $this->mapResourceToTransfer($data);

        if ($operation->getName() === 'post') {
            $storeTransferResponseTransfer = $this->storeFacade->createStore($storeTransfer);

            return $this->mapTransferToResource($storeTransferResponseTransfer->getStoreOrFail());
        }

        if ($operation->getName() === 'patch') {
            $existingStoreTransfer = $this->storeFacade->getStoreByName($uriVariables['name']);

            if (!($existingStoreTransfer instanceof StoreTransfer)) {
                return $data;
            }

            $storeTransfer->setIdStore($existingStoreTransfer->getIdStore());
            $storeTransferResponseTransfer = $this->storeFacade->updateStore($storeTransfer);

            return $this->mapTransferToResource($storeTransferResponseTransfer->getStoreOrFail());
        }

        return $data;
    }

    protected function mapResourceToTransfer(StoresBackendResource $resource): StoreTransfer
    {
        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($resource->toArray(), true);

        return $storeTransfer;
    }

    protected function mapTransferToResource(StoreTransfer $storeTransfer): StoresBackendResource
    {
        return StoresBackendResource::fromArray($storeTransfer->toArray());
    }
}

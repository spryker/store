<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\Store\Api\Backend\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use Generated\Api\Backend\StoresBackendResource;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreApplicationContextTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

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

        if ($operation instanceof Post) {
            $storeTransferResponseTransfer = $this->storeFacade->createStore($storeTransfer);

            $this->validateStoreResponse($storeTransferResponseTransfer);

            return $this->mapTransferToResource($storeTransferResponseTransfer->getStoreOrFail());
        }

        if ($operation instanceof Patch) {
            $existingStoreTransfer = $this->storeFacade->getStoreByName($uriVariables['name']);

            if (!($existingStoreTransfer instanceof StoreTransfer)) {
                return $data;
            }

            $storeTransfer->setIdStore($existingStoreTransfer->getIdStore());

            $storeTransferResponseTransfer = $this->storeFacade->updateStore($storeTransfer);

            $this->validateStoreResponse($storeTransferResponseTransfer);

            return $this->mapTransferToResource($storeTransferResponseTransfer->getStoreOrFail());
        }

        return $data;
    }

    protected function mapResourceToTransfer(StoresBackendResource $resource): StoreTransfer
    {
        $storeTransfer = new StoreTransfer();
        $storeTransfer->fromArray($resource->toArray(), true);

        if (property_exists($resource, 'applicationContextCollection') && $resource->applicationContextCollection !== null) {
            $collection = new StoreApplicationContextCollectionTransfer();

            if (isset($resource->applicationContextCollection['applicationContexts']) && is_array($resource->applicationContextCollection['applicationContexts'])) {
                foreach ($resource->applicationContextCollection['applicationContexts'] as $context) {
                    $contextTransfer = new StoreApplicationContextTransfer();
                    $contextTransfer->setApplication($context['application'] ?? null);
                    $contextTransfer->setTimezone($context['timezone']);
                    $collection->addApplicationContext($contextTransfer);
                }
            }

            $storeTransfer->setApplicationContextCollection($collection);
        }

        return $storeTransfer;
    }

    protected function mapTransferToResource(StoreTransfer $storeTransfer): StoresBackendResource
    {
        return StoresBackendResource::fromArray($storeTransfer->toArray());
    }

    protected function validateStoreResponse(StoreResponseTransfer $storeResponseTransfer): void
    {
        if (!$storeResponseTransfer->getIsSuccessful()) {
            $errorMessages = [];

            foreach ($storeResponseTransfer->getMessages() as $messageTransfer) {
                $errorMessages[] = $messageTransfer->getMessage() ?? $messageTransfer->getValue();
            }

            $errorMessage = implode(' ', $errorMessages);

            throw new UnprocessableEntityHttpException($errorMessage);
        }
    }
}

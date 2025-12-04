<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\Store\Api\Storefront\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Generated\Api\Storefront\StoresStorefrontResource;
use Spryker\Client\StoreStorage\StoreStorageClientInterface;

/**
 * @implements \ApiPlatform\State\ProviderInterface<\Generated\Api\Storefront\StoresStorefrontResource>
 */
class StoresStorefrontProvider implements ProviderInterface
{
    public function __construct(protected StoreStorageClientInterface $storeStorageClient)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $name = $uriVariables['name'] ?? null;
        $storeNames = $this->storeStorageClient->getStoreNames();

        if ($name === null) {
            return $this->provideCollection($storeNames);
        }

        foreach ($storeNames as $storeName) {
            if ($storeName === $name) {
                return $this->mapTransferToResource($storeName);
            }
        }

        return null;
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Api\Storefront\StoresStorefrontResource>
     */
    protected function provideCollection(array $storeNames): array
    {
        $resources = [];

        foreach ($storeNames as $storeName) {
            $resources[] = $this->mapTransferToResource($storeName);
        }

        return $resources;
    }

    protected function mapTransferToResource(string $storeName): StoresStorefrontResource
    {
        return StoresStorefrontResource::fromArray(['name' => $storeName]);
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Store\StorefrontApi;

use Codeception\Stub;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Client\StoreStorage\StoreStorageClientInterface;
use SprykerTest\ApiPlatform\Test\StorefrontApiTestCase;
use SprykerTest\Glue\Store\StorefrontApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Store
 * @group StorefrontApi
 * @group StoresStorefrontApiTest
 * Add your own group annotations below this line
 */
class StoresStorefrontApiTest extends StorefrontApiTestCase
{
    protected StorefrontApiTester $tester;

    public function testGivenExistingStoreWhenRetrievingViaGetThenStoreDataIsReturned(): void
    {
        // Arrange
        $this->mockStoreClient();

        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => ['DE'],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        // Act
        $this->createClient()->request('GET', '/stores/DE');

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['data' => ['attributes' => ['name' => 'DE']]]);
    }

    public function testGivenMultipleStoresWhenRetrievingCollectionViaGetThenAllStoresAreReturned(): void
    {
        // Arrange
        $this->mockStoreClient();

        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => ['DE', 'AT'],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        // Act
        $this->createClient()->request('GET', '/stores');

        // Assert
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->getClient()->getResponse()->getContent(), true);
        $this->assertIsArray($response['data']);
        $this->assertCount(2, $response['data']);
    }

    public function testGivenNonExistentStoreWhenRetrievingViaGetThen404IsReturned(): void
    {
        // Arrange
        $this->mockStoreClient();

        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => [],
        ]);

        $glossaryStub = Stub::makeEmpty(GlossaryStorageClientInterface::class, [
            'translate' => function (string $id): string {
                return sprintf('%s-translated-by-mock', $id);
            },
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);
        $this->getContainer()->set(GlossaryStorageClientInterface::class, $glossaryStub);

        // Act
        $this->createClient()->request('GET', '/stores/NON-EXISTENT-STORE');

        // Assert
        $this->assertResponseStatusCodeSame(404);
    }

    public function testGivenPaginationParamsWhenRetrievingCollectionThenPaginatedResultsAreReturned(): void
    {
        // Arrange
        $this->mockStoreClient();

        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => ['DE', 'AT'],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        // Act
        $this->createClient()->request('GET', '/stores?page=1&itemsPerPage=10');

        // Assert
        $this->assertResponseIsSuccessful();
        $response = json_decode($this->getClient()->getResponse()->getContent(), true);
        $this->assertIsArray($response['data']);
    }

    protected function mockStoreClient(): void
    {
        $storeTransfer = new StoreTransfer();
        $storeTransfer->setAvailableLocaleIsoCodes(['de' => 'de_DE', 'en' => 'en_US']);

        $clientStub = Stub::makeEmpty(StoreClientInterface::class, [
            'getCurrentStore' => $storeTransfer,
        ]);

        $this->getContainer()->set(StoreClientInterface::class, $clientStub);
    }
}

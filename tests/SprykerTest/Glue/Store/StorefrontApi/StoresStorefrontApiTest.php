<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Store\StorefrontApi;

use Codeception\Stub;
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
        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => ['DE'],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        // Act
        $this->createClient()->request('GET', '/stores/DE');

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['name' => 'DE']);
    }

    public function testGivenMultipleStoresWhenRetrievingCollectionViaGetThenAllStoresAreReturned(): void
    {
        // Arrange
        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => ['DE', 'AT'],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        // Act
        $this->createClient()->request('GET', '/stores');

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@type' => 'Collection']);
    }

    public function testGivenNonExistentStoreWhenRetrievingViaGetThen404IsReturned(): void
    {
        // Act
        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => [],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        $this->createClient()->request('GET', '/stores/NON-EXISTENT-STORE');

        // Assert
        $this->assertResponseStatusCodeSame(404);
    }

    public function testGivenPaginationParamsWhenRetrievingCollectionThenPaginatedResultsAreReturned(): void
    {
        // Arrange
        $storeStorageClientStub = Stub::makeEmpty(StoreStorageClientInterface::class, [
            'getStoreNames' => ['DE', 'AT'],
        ]);

        $this->getContainer()->set(StoreStorageClientInterface::class, $storeStorageClientStub);

        // Act
        $this->createClient()->request('GET', '/stores?page=1&itemsPerPage=10');

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@type' => 'Collection']);
    }
}

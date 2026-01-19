<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Store\BackendApi;

use SprykerTest\ApiPlatform\Test\BackendApiTestCase;
use SprykerTest\Glue\Store\BackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Store
 * @group BackendApi
 * @group StoresBackendApiTest
 * Add your own group annotations below this line
 */
class StoresBackendApiTest extends BackendApiTestCase
{
    protected BackendApiTester $tester;

    public function testGivenInvalidDataWhenCreatingStoreViaPostThenValidationErrorIsReturned(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $invalidStoreData = ['timezone' => 'Europe/Berlin'];

        // Act
        $this->createClient()->request('POST', '/stores', ['json' => $invalidStoreData]);

        // Assert
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains(['@type' => 'ConstraintViolation']);
        $this->assertJsonContains([
            'violations' => [
                ['propertyPath' => 'name'],
            ],
        ]);
    }

    public function testGivenValidDataWhenCreatingStoreViaPostThenStoreIsCreatedSuccessfully(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $storeData = [
            'name' => 'API_TEST_STORE',
        ];

        // Act
        $this->createClient()->request('POST', '/stores', ['json' => $storeData]);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['name' => 'API_TEST_STORE']);
    }

    public function testGivenExistingStoreWhenRetrievingViaGetThenStoreDataIsReturned(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $storeTransfer = $this->tester->haveStore([
            'name' => 'API_TEST_GET_STORE',
        ]);

        // Act
        $this->createClient()->request('GET', sprintf('/stores/%s', $storeTransfer->getName()));

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['name' => 'API_TEST_GET_STORE', 'timezone' => 'UTC']);
    }

    public function testGivenMultipleStoresWhenRetrievingCollectionViaGetThenAllStoresAreReturned(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $this->tester->haveStore(['name' => 'API_TEST_STORE_1']);
        $this->tester->haveStore(['name' => 'API_TEST_STORE_2']);
        $this->tester->haveStore(['name' => 'API_TEST_STORE_3']);

        // Act
        $response = $this->createClient()->request('GET', '/stores');

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@type' => 'Collection']);

        $storeNames = array_column($response->toArray()['member'], 'name');
        $this->assertContains('API_TEST_STORE_1', $storeNames);
        $this->assertContains('API_TEST_STORE_2', $storeNames);
        $this->assertContains('API_TEST_STORE_3', $storeNames);
    }

    public function testGivenExistingStoreWhenUpdatingViaPatchThenStoreIsUpdatedSuccessfully(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $storeData = [
            'name' => 'API_TEST_PATCH_STORE',
            'timezone' => 'UTC',
        ];
        $storeTransfer = $this->tester->haveStore($storeData);

        $storeData['timezone'] = 'America/New_York';

        // Act
        $this->createClient()->request('PATCH', sprintf('/stores/%s', $storeTransfer->getName()), [
            'json' => $storeData,
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
        ]);

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['timezone' => 'America/New_York']);
    }

    public function testGivenDuplicateNameWhenCreatingStoreViaPostThenErrorIsReturned(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $this->tester->haveStore(['name' => 'API_DUPLICATE_STORE']);
        $duplicateData = [
            'name' => 'API_DUPLICATE_STORE',
        ];

        // Act
        $this->createClient()->request('POST', '/stores', ['json' => $duplicateData]);

        // Assert
        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains(['@type' => 'Error']);
        $this->assertJsonContains(['detail' => 'A store with the same name already exists.']);
    }

    public function testGivenPaginationParamsWhenRetrievingCollectionThenPaginatedResultsAreReturned(): void
    {
        // Arrange
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('is test can only run in successfully when Dynamic Store mode is enabled.');
        }

        $itemsPerPage = 10;
        $totalStores = $itemsPerPage * 2;

        for ($i = 1; $i <= $totalStores; $i++) {
            $this->tester->haveStore(['name' => sprintf('API_TEST_PAGINATED_STORE_%d', $i)]);
        }

        // Act
        $response = $this->createClient()->request('GET', sprintf('/stores?page=1&itemsPerPage=%d', $itemsPerPage));

        // Assert
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@type' => 'Collection']);

        $responseData = $response->toArray();
        $this->assertGreaterThanOrEqual($itemsPerPage, count($responseData['member']));
        $this->assertArrayHasKey('view', $responseData);
    }
}

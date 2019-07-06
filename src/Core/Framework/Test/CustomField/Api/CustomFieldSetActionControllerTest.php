<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\CustomField\Api;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;

class CustomFieldSetActionControllerTest extends TestCase
{
    use AdminApiTestBehaviour;

    public function testGetAvailableRelations(): void
    {
        $this->getClient()->request('GET', '/api/v1/_action/attribute-set/relations');
        $response = $this->getClient()->getResponse();

        static::assertEquals(200, $response->getStatusCode());
        static::assertEquals('application/json', $response->headers->get('Content-Type'));

        $availableRelations = \json_decode($response->getContent(), true);
        static::assertNotEmpty($availableRelations);

        static::assertContains('product', $availableRelations);
        static::assertNotContains('product_translation', $availableRelations);

        static::assertContains('product_manufacturer', $availableRelations);
        static::assertNotContains('product-manufacturer', $availableRelations);
    }
}

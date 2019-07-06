<?php declare(strict_types=1);

namespace Shopware\Core\Content\Test\ProductStream;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Framework\Api\Controller\SyncController;
use Shopware\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\PlatformRequest;

class ProductStreamSyncTest extends TestCase
{
    use AdminFunctionalTestBehaviour;

    /**
     * @var Connection
     */
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->getContainer()->get(Connection::class);
    }

    public function testSyncProductStream(): void
    {
        $id1 = Uuid::randomHex();
        $id2 = Uuid::randomHex();
        $data = [
            [
                'action' => SyncController::ACTION_UPSERT,
                'entity' => $this->getContainer()->get(ProductStreamDefinition::class)->getEntityName(),
                'payload' => [
                    [
                        'id' => $id1,
                        'name' => 'Test stream',
                    ],
                    [
                        'id' => $id2,
                        'name' => 'Test stream - 2',
                    ],
                ],
            ],
        ];

        $this->getClient()->request('POST', '/api/v' . PlatformRequest::API_VERSION . '/_action/sync', [], [], [], json_encode($data));
        $response = $this->getClient()->getResponse();

        static::assertSame(200, $response->getStatusCode(), $response->getContent());

        $result = $this->connection
            ->executeQuery('SELECT * from product_stream inner join product_stream_translation on product_stream.id = product_stream_translation.product_stream_id order by name');

        static::assertEquals('Test stream', $result->fetch()['name']);
        static::assertEquals('Test stream - 2', $result->fetch()['name']);
    }
}

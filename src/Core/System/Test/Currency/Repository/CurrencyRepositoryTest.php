<?php declare(strict_types=1);

namespace Shopware\Core\System\Test\Currency\Repository;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Term\EntityScoreQueryBuilder;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Term\SearchTermInterpreter;
use Shopware\Core\Framework\Test\TestCaseBase\DatabaseTransactionBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;

class CurrencyRepositoryTest extends TestCase
{
    use KernelTestBehaviour;
    use DatabaseTransactionBehaviour;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $currencyRepository;

    protected function setUp(): void
    {
        $this->currencyRepository = $this->getContainer()->get('currency.repository');
        $this->connection = $this->getContainer()->get(Connection::class);
    }

    public function testSearchRanking(): void
    {
        $recordA = Uuid::randomHex();
        $recordB = Uuid::randomHex();

        $records = [
            ['id' => $recordA, 'decimalPrecision' => 2, 'name' => 'match', 'isoCode' => 'USD', 'shortName' => 'test', 'factor' => 1, 'symbol' => 'A'],
            ['id' => $recordB, 'decimalPrecision' => 2, 'name' => 'not', 'isoCode' => 'EUR', 'shortName' => 'match', 'factor' => 1, 'symbol' => 'A'],
        ];

        $this->currencyRepository->create($records, Context::createDefaultContext());

        $criteria = new Criteria();

        $builder = $this->getContainer()->get(EntityScoreQueryBuilder::class);
        $pattern = $this->getContainer()->get(SearchTermInterpreter::class)->interpret('match');
        $queries = $builder->buildScoreQueries($pattern, $this->currencyRepository->getDefinition(), $this->currencyRepository->getDefinition()->getEntityName());
        $criteria->addQuery(...$queries);

        $result = $this->currencyRepository->searchIds($criteria, Context::createDefaultContext());

        static::assertCount(2, $result->getIds());

        static::assertEquals(
            [$recordA, $recordB],
            $result->getIds()
        );

        static::assertGreaterThan(
            $result->getDataFieldOfId($recordB, '_score'),
            $result->getDataFieldOfId($recordA, '_score')
        );
    }
}

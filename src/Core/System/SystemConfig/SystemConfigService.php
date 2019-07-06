<?php declare(strict_types=1);

namespace Shopware\Core\System\SystemConfig;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidException;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SystemConfig\Exception\InvalidDomainException;
use Shopware\Core\System\SystemConfig\Exception\InvalidKeyException;

class SystemConfigService
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $systemConfigRepository;

    public function __construct(Connection $connection, EntityRepositoryInterface $systemConfigRepository)
    {
        $this->connection = $connection;
        $this->systemConfigRepository = $systemConfigRepository;
    }

    public function get(string $key, ?string $salesChannelId = null, bool $inherit = true)
    {
        $criteria = new Criteria();

        $filter = [new EqualsFilter('salesChannelId', $salesChannelId)];
        if ($inherit) {
            $filter[] = new EqualsFilter('salesChannelId', null);
        }

        $criteria->addFilter(new MultiFilter(
            MultiFilter::CONNECTION_OR,
            $filter
        ));
        $criteria->addFilter(new EqualsFilter('configurationKey', $key));
        $criteria->addSorting(new FieldSorting('salesChannelId', FieldSorting::ASCENDING));

        $last = $this->systemConfigRepository
            ->search($criteria, Context::createDefaultContext())
            ->last();

        return $last ? $last->getConfigurationValue() : null;
    }

    public function getDomain(string $domain, ?string $salesChannelId = null, bool $inherit = false): array
    {
        $domain = trim($domain);
        if ($domain === '') {
            throw new InvalidDomainException('Empty domain');
        }

        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('LOWER(HEX(id))')
            ->from('system_config');

        if ($inherit) {
            $queryBuilder->where('sales_channel_id IS NULL OR sales_channel_id = :salesChannelId');
        } elseif ($salesChannelId === null) {
            $queryBuilder->where('sales_channel_id IS NULL');
        } else {
            $queryBuilder->where('sales_channel_id = :salesChannelId');
        }

        $domain = rtrim($domain, '.') . '.';
        $escapedDomain = str_replace('%', '\\%', $domain);

        $salesChannelId = $salesChannelId ? Uuid::fromHexToBytes($salesChannelId) : null;

        $queryBuilder->andWhere('configuration_key LIKE :prefix')
            ->orderBy('configuration_key', 'ASC')
            ->addOrderBy('sales_channel_id', 'ASC')
            ->setParameter('prefix', $escapedDomain . '%')
            ->setParameter('salesChannelId', $salesChannelId);
        $ids = $queryBuilder->execute()->fetchAll(FetchMode::COLUMN);

        if (empty($ids)) {
            return [];
        }

        $criteria = new Criteria($ids);
        $collection = $this->systemConfigRepository
            ->search($criteria, Context::createDefaultContext())
            ->getEntities();

        $collection->sortByIdArray($ids);
        $merged = [];
        foreach ($collection as $cur) {
            // use the last one with the same key. entities with sales_channel_id === null are sorted before the others
            $merged[$cur->getConfigurationKey()] = $cur->getConfigurationValue();
        }

        return $merged;
    }

    public function set(string $key, $value, ?string $salesChannelId = null): void
    {
        $key = trim($key);
        $this->validate($key, $salesChannelId);

        $id = $this->getId($key, $salesChannelId);
        if ($value === null) {
            if ($id) {
                $this->systemConfigRepository->delete([['id' => $id]], Context::createDefaultContext());
            }

            return;
        }

        $data = [
            'id' => $id ?? Uuid::randomHex(),
            'configurationKey' => $key,
            'configurationValue' => $value,
            'salesChannelId' => $salesChannelId,
        ];
        $this->systemConfigRepository->upsert([$data], Context::createDefaultContext());
    }

    public function delete(string $key, ?string $salesChannel = null): void
    {
        $this->set($key, null, $salesChannel);
    }

    private function validate(string $key, ?string $salesChannelId): void
    {
        $key = trim($key);
        if ($key === '') {
            throw new InvalidKeyException('key may not be empty');
        }
        if ($salesChannelId && !Uuid::isValid($salesChannelId)) {
            throw new InvalidUuidException($salesChannelId);
        }
    }

    private function getId($key, ?string $salesChannelId = null): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(
            new EqualsFilter('configurationKey', $key),
            new EqualsFilter('salesChannelId', $salesChannelId)
        );

        $ids = $this->systemConfigRepository->searchIds($criteria, Context::createDefaultContext())->getIds();

        return array_shift($ids);
    }
}

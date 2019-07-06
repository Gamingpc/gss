<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\SearchKeyword;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\Aggregate\ProductKeywordDictionary\ProductKeywordDictionaryDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductSearchKeyword\ProductSearchKeywordDefinition;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\Util\EventIdExtractor;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\IteratorFactory;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Indexing\IndexerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Doctrine\MultiInsertQueryQueue;
use Shopware\Core\Framework\Event\ProgressAdvancedEvent;
use Shopware\Core\Framework\Event\ProgressFinishedEvent;
use Shopware\Core\Framework\Event\ProgressStartedEvent;
use Shopware\Core\Framework\Language\LanguageEntity;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductSearchKeywordIndexer implements IndexerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EventIdExtractor
     */
    private $eventIdExtractor;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var IteratorFactory
     */
    private $iteratorFactory;

    /**
     * @var EntityRepositoryInterface
     */
    private $languageRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductSearchKeywordAnalyzerInterface
     */
    private $analyzer;

    /**
     * @var ProductSearchKeywordDefinition
     */
    private $productSearchKeywordDefinition;

    /**
     * @var ProductKeywordDictionaryDefinition
     */
    private $productKeywordDictionaryDefinition;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EventIdExtractor $eventIdExtractor,
        Connection $connection,
        IteratorFactory $iteratorFactory,
        EntityRepositoryInterface $languageRepository,
        EntityRepositoryInterface $productRepository,
        ProductSearchKeywordAnalyzerInterface $analyzer,
        ProductSearchKeywordDefinition $productSearchKeywordDefinition,
        ProductKeywordDictionaryDefinition $productKeywordDictionaryDefinition
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventIdExtractor = $eventIdExtractor;
        $this->connection = $connection;
        $this->iteratorFactory = $iteratorFactory;
        $this->languageRepository = $languageRepository;
        $this->productRepository = $productRepository;
        $this->analyzer = $analyzer;
        $this->productSearchKeywordDefinition = $productSearchKeywordDefinition;
        $this->productKeywordDictionaryDefinition = $productKeywordDictionaryDefinition;
    }

    public function index(\DateTimeInterface $timestamp): void
    {
        $languages = $this->languageRepository->search(new Criteria(), Context::createDefaultContext());

        /** @var LanguageEntity $language */
        foreach ($languages as $language) {
            $context = new Context(
                new Context\SystemSource(),
                [],
                Defaults::CURRENCY,
                [$language->getId(), $language->getParent(), Defaults::LANGUAGE_SYSTEM],
                Defaults::LIVE_VERSION
            );

            $iterator = $this->iteratorFactory->createIterator($this->productRepository->getDefinition());

            $this->eventDispatcher->dispatch(
                ProgressStartedEvent::NAME,
                new ProgressStartedEvent(
                    sprintf('Start indexing product keywords for language %s', $language->getName()),
                    $iterator->fetchCount()
                )
            );

            $this->connection->executeUpdate(
                'DELETE FROM product_keyword_dictionary WHERE language_id = :id',
                ['id' => Uuid::fromHexToBytes($language->getId())]
            );

            while ($ids = $iterator->fetch()) {
                $this->update($ids, $context);

                $this->eventDispatcher->dispatch(
                    ProgressAdvancedEvent::NAME,
                    new ProgressAdvancedEvent(\count($ids))
                );
            }

            $this->eventDispatcher->dispatch(
                ProgressFinishedEvent::NAME,
                new ProgressFinishedEvent(sprintf('Finished indexing product keywords for language %s', $language->getName()))
            );
        }
    }

    public function refresh(EntityWrittenContainerEvent $event): void
    {
        $ids = $this->eventIdExtractor->getProductIds($event);

        $this->update($ids, $event->getContext());
    }

    public function update(array $ids, Context $context): void
    {
        if (empty($ids)) {
            return;
        }

        $products = $context->disableCache(function (Context $context) use ($ids) {
            $context->setConsiderInheritance(true);

            return $this->productRepository->search(new Criteria($ids), $context);
        });

        $versionId = Uuid::fromHexToBytes($context->getVersionId());
        $languageId = Uuid::fromHexToBytes($context->getLanguageId());

        $insert = new MultiInsertQueryQueue($this->connection, 250, false, true);

        $now = (new \DateTime())->format(Defaults::STORAGE_DATE_FORMAT);

        /** @var ProductEntity $product */
        foreach ($products as $product) {
            $keywords = $this->analyzer->analyze($product, $context);

            $productId = Uuid::fromHexToBytes($product->getId());

            foreach ($keywords as $keyword) {
                $insert->addInsert(
                    $this->productSearchKeywordDefinition->getEntityName(),
                    [
                        'id' => Uuid::randomBytes(),
                        'version_id' => $versionId,
                        'product_version_id' => $versionId,
                        'language_id' => $languageId,
                        'product_id' => $productId,
                        'keyword' => $keyword->getKeyword(),
                        'ranking' => $keyword->getRanking(),
                        'created_at' => $now,
                    ]
                );

                $insert->addInsert(
                    $this->productKeywordDictionaryDefinition->getEntityName(),
                    [
                        'id' => Uuid::randomBytes(),
                        'language_id' => $languageId,
                        'keyword' => $keyword->getKeyword(),
                    ]
                );
            }
        }

        $this->connection->transactional(
            function () use ($insert, $ids, $languageId) {
                $bytes = array_map(function ($id) {
                    return Uuid::fromHexToBytes($id);
                }, $ids);

                $this->connection->executeUpdate(
                    'DELETE FROM product_search_keyword WHERE product_id IN (:ids) AND language_id = :language',
                    ['ids' => $bytes, 'language' => $languageId],
                    ['ids' => Connection::PARAM_STR_ARRAY]
                );

                $insert->execute();
            }
        );
    }
}

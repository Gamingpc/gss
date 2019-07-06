<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Translation;

use Doctrine\DBAL\Connection;
use Psr\Cache\CacheItemPoolInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\Snippet\Aggregate\SnippetSet\SnippetSetDefinition;
use Shopware\Core\Framework\Snippet\SnippetDefinition;
use Shopware\Core\Framework\Snippet\SnippetEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TranslatorCacheInvalidate implements EventSubscriberInterface
{
    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(CacheItemPoolInterface $cache, Connection $connection)
    {
        $this->cache = $cache;
        $this->connection = $connection;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SnippetEvents::SNIPPET_WRITTEN_EVENT => 'invalidate',
            SnippetEvents::SNIPPET_DELETED_EVENT => 'invalidate',
            SnippetEvents::SNIPPET_SET_DELETED_EVENT => 'invalidate',
        ];
    }

    public function invalidate(EntityWrittenEvent $event): void
    {
        if ($event->getDefinition()->getClass() === SnippetSetDefinition::class) {
            $this->clearCache($event->getIds());

            return;
        }

        if ($event->getDefinition()->getClass() === SnippetDefinition::class) {
            $snippetIds = $event->getIds();

            $rows = $this->connection->fetchAll(
                'SELECT LOWER(HEX(snippet_set_id)) id FROM snippet WHERE HEX(id) IN (:ids)',
                ['ids' => $snippetIds],
                ['ids' => Connection::PARAM_STR_ARRAY]
            );
            $setIds = [];
            foreach ($rows as ['id' => $id]) {
                $setIds[] = $id;
            }

            $this->clearCache($setIds);

            return;
        }
    }

    private function clearCache(array $snippetSetIds): void
    {
        $snippetSetIds = array_unique($snippetSetIds);

        foreach ($snippetSetIds as $id) {
            $this->cache->deleteItem('translation.catalog.' . $id);
        }
    }
}

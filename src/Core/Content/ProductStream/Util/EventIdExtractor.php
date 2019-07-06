<?php declare(strict_types=1);

namespace Shopware\Core\Content\ProductStream\Util;

use Shopware\Core\Content\ProductStream\Aggregate\ProductStreamFilter\ProductStreamFilterDefinition;
use Shopware\Core\Content\ProductStream\ProductStreamDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

class EventIdExtractor
{
    public function getProductStreamIds(EntityWrittenContainerEvent $generic): array
    {
        $ids = [];

        $event = $generic->getEventByDefinition(ProductStreamDefinition::class);
        if ($event) {
            $ids = $event->getIds();
        }

        $event = $generic->getEventByDefinition(ProductStreamFilterDefinition::class);
        if ($event) {
            foreach ($event->getPayloads() as $id) {
                if (isset($id['productStreamId'])) {
                    $ids[] = $id['productStreamId'];
                }
            }
        }

        return $ids;
    }
}

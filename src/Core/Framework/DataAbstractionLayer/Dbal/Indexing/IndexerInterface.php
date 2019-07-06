<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Dbal\Indexing;

use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;

interface IndexerInterface
{
    public function index(\DateTimeInterface $timestamp): void;

    public function refresh(EntityWrittenContainerEvent $event): void;
}

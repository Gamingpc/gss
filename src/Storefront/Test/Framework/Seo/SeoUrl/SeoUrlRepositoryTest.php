<?php declare(strict_types=1);

namespace Shopware\Storefront\Test\Framework\Seo\SeoUrl;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Storefront\Framework\Seo\DbalIndexing\SeoUrl\ProductDetailPageSeoUrlIndexer;
use Shopware\Storefront\Framework\Seo\SeoUrl\SeoUrlDefinition;
use Shopware\Storefront\Framework\Seo\SeoUrl\SeoUrlEntity;

class SeoUrlRepositoryTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testCreate(): void
    {
        $id = Uuid::randomHex();
        $fk = Uuid::randomHex();
        $url = [
            'id' => $id,
            'salesChannelId' => Defaults::SALES_CHANNEL,
            'foreignKey' => $fk,

            'routeName' => ProductDetailPageSeoUrlIndexer::ROUTE_NAME,
            'pathInfo' => '/ugly/path',
            'seoPathInfo' => '/pretty/path',

            'isCanonical' => true,
            'isModified' => false,
        ];

        $context = Context::createDefaultContext();
        /** @var EntityRepositoryInterface $repo */
        $repo = $this->getContainer()->get('seo_url.repository');
        $events = $repo->create([$url], $context);
        static::assertCount(1, $events->getEvents());

        $event = $events->getEventByDefinition(SeoUrlDefinition::class);
        static::assertNotNull($event);
        static::assertCount(1, $event->getPayloads());
    }

    public function testUpdate(): void
    {
        $id = Uuid::randomHex();
        $fk = Uuid::randomHex();
        $url = [
            'id' => $id,
            'salesChannelId' => Defaults::SALES_CHANNEL,
            'foreignKey' => $fk,

            'routeName' => ProductDetailPageSeoUrlIndexer::ROUTE_NAME,
            'pathInfo' => '/ugly/path',
            'seoPathInfo' => '/pretty/path',

            'isCanonical' => true,
            'isModified' => false,
        ];

        $context = Context::createDefaultContext();
        /** @var EntityRepositoryInterface $repo */
        $repo = $this->getContainer()->get('seo_url.repository');
        $repo->create([$url], $context);

        $update = [
            'id' => $id,
            'seoPathInfo' => '/even/prettier/path',
        ];
        $events = $repo->update([$update], $context);
        $event = $events->getEventByDefinition(SeoUrlDefinition::class);
        static::assertNotNull($event);
        static::assertCount(1, $event->getPayloads());

        /** @var SeoUrlEntity $first */
        $first = $repo->search(new Criteria([$id]), $context)->first();
        static::assertEquals($update['id'], $first->getId());
        static::assertEquals($update['seoPathInfo'], $first->getSeoPathInfo());
    }

    public function testDelete(): void
    {
        $id = Uuid::randomHex();
        $fk = Uuid::randomHex();
        $url = [
            'id' => $id,
            'salesChannelId' => Defaults::SALES_CHANNEL,
            'foreignKey' => $fk,

            'routeName' => ProductDetailPageSeoUrlIndexer::ROUTE_NAME,
            'pathInfo' => '/ugly/path',
            'seoPathInfo' => '/pretty/path',

            'isCanonical' => true,
            'isModified' => false,
        ];

        $context = Context::createDefaultContext();
        /** @var EntityRepositoryInterface $repo */
        $repo = $this->getContainer()->get('seo_url.repository');
        $repo->create([$url], $context);

        $result = $repo->delete([['id' => $id]], $context);
        $event = $result->getEventByDefinition(SeoUrlDefinition::class);
        static::assertEquals([$id], $event->getIds());

        /** @var SeoUrlEntity|null $first */
        $first = $repo->search(new Criteria([$id]), $context)->first();
        static::assertNull($first);
    }
}

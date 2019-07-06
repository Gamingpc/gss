<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\DataAbstractionLayer\Cache;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\Aggregate\ProductCategory\ProductCategoryDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductManufacturer\ProductManufacturerDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Cache\EntityCacheKeyGenerator;
use Shopware\Core\Framework\DataAbstractionLayer\Cache\InvalidateCacheSubscriber;
use Shopware\Core\Framework\DataAbstractionLayer\EntityWriteResult;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use Shopware\Core\Framework\Event\NestedEventCollection;
use Shopware\Core\Framework\Language\LanguageDefinition;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class InvalidateCacheSubscriberTest extends TestCase
{
    use KernelTestBehaviour;

    public function testInvalidate(): void
    {
        $id = Uuid::randomHex();

        $context = Context::createDefaultContext();

        $events = new NestedEventCollection([
            new EntityWrittenEvent(
                $this->getContainer()->get(ProductDefinition::class),
                [new EntityWriteResult(
                    $id,
                    ['name' => 'test', 'id' => $id, 'stock' => 15, 'manufacturerId' => $id],
                    $this->getContainer()->get(ProductDefinition::class),
                    new EntityExistence($this->getContainer()->get(ProductDefinition::class), ['id' => $id], true, false, false, [])
                )],
                $context
            ),
            new EntityWrittenEvent(
                $this->getContainer()->get(ProductManufacturerDefinition::class),
                [new EntityWriteResult(
                    $id,
                    ['name' => 'test', 'id' => $id, 'active' => true],
                    $this->getContainer()->get(ProductDefinition::class),
                    new EntityExistence($this->getContainer()->get(ProductDefinition::class), ['id' => $id], true, false, false, [])
                )],
                $context
            ),
            new EntityWrittenEvent(
                $this->getContainer()->get(ProductCategoryDefinition::class),
                [new EntityWriteResult(
                    $id,
                    ['productId' => $id, 'categoryId' => $id],
                    $this->getContainer()->get(ProductDefinition::class),
                    new EntityExistence($this->getContainer()->get(ProductDefinition::class), ['id' => $id], true, false, false, [])
                )],
                $context
            ),
        ]);

        $event = new EntityWrittenContainerEvent($context, $events, []);

        $tags = [
            'product-' . $id,
            'product.name',
            'product.id',
            'product.stock',

            'product_manufacturer-' . $id,
            'product.product_manufacturer_id',
            'product_manufacturer.name',
            'product_manufacturer.id',

            'product_category-' . $id,
            'product_category.product_id',
            'category-' . $id,
            'product_category.category_id',
        ];

        $cache = $this->createMock(TagAwareAdapter::class);
        $cache->expects(static::once())
            ->method('invalidateTags')
            ->with($tags);

        $generator = new EntityCacheKeyGenerator($this->getContainer()->get(LanguageDefinition::class));
        $subscriber = new InvalidateCacheSubscriber($cache, $generator);

        $subscriber->entitiesWritten($event);
    }
}

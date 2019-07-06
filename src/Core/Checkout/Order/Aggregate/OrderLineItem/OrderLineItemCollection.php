<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Order\Aggregate\OrderLineItem;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                     add(OrderLineItemEntity $entity)
 * @method void                     set(string $key, OrderLineItemEntity $entity)
 * @method OrderLineItemEntity[]    getIterator()
 * @method OrderLineItemEntity[]    getElements()
 * @method OrderLineItemEntity|null get(string $key)
 * @method OrderLineItemEntity|null first()
 * @method OrderLineItemEntity|null last()
 */
class OrderLineItemCollection extends EntityCollection
{
    public function getOrderIds(): array
    {
        return $this->fmap(function (OrderLineItemEntity $orderLineItem) {
            return $orderLineItem->getOrderId();
        });
    }

    public function filterByOrderId(string $id): self
    {
        return $this->filter(function (OrderLineItemEntity $orderLineItem) use ($id) {
            return $orderLineItem->getOrderId() === $id;
        });
    }

    protected function getExpectedClass(): string
    {
        return OrderLineItemEntity::class;
    }
}

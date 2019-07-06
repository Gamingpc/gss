<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\DiscountSurcharge;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                         add(DiscountSurchargeEntity $entity)
 * @method void                         set(string $key, DiscountSurchargeEntity $entity)
 * @method DiscountSurchargeEntity[]    getIterator()
 * @method DiscountSurchargeEntity[]    getElements()
 * @method DiscountSurchargeEntity|null get(string $key)
 * @method DiscountSurchargeEntity|null first()
 * @method DiscountSurchargeEntity|null last()
 */
class DiscountSurchargeCollection extends EntityCollection
{
    public function getRuleIds(): array
    {
        return $this->fmap(function (DiscountSurchargeEntity $discountSurcharge) {
            return $discountSurcharge->getRuleId();
        });
    }

    public function filterByRuleId(string $id): self
    {
        return $this->filter(function (DiscountSurchargeEntity $discountSurcharge) use ($id) {
            return $discountSurcharge->getRuleId() === $id;
        });
    }

    protected function getExpectedClass(): string
    {
        return DiscountSurchargeEntity::class;
    }
}

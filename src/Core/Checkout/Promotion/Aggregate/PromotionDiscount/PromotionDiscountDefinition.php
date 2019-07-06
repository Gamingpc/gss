<?php
declare(strict_types=1);

namespace Shopware\Core\Checkout\Promotion\Aggregate\PromotionDiscount;

use Shopware\Core\Checkout\Promotion\Aggregate\PromotionDiscountRule\PromotionDiscountRuleDefinition;
use Shopware\Core\Checkout\Promotion\PromotionDefinition;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FloatField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class PromotionDiscountDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'promotion_discount';
    }

    public function getEntityClass(): string
    {
        return PromotionDiscountEntity::class;
    }

    public function getCollectionClass(): string
    {
        return PromotionDiscountCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('promotion_id', 'promotionId', PromotionDefinition::class, 'id'))->addFlags(new Required()),
            (new StringField('scope', 'scope'))->addFlags(new Required()),
            (new StringField('type', 'type', 32))->addFlags(new Required()),
            (new FloatField('value', 'value'))->addFlags(new Required()),
            (new BoolField('consider_advanced_rules', 'considerAdvancedRules'))->addFlags(new Required()),

            new ManyToOneAssociationField('promotion', 'promotion_id', PromotionDefinition::class, 'id'),
            (new ManyToManyAssociationField('discountRules', RuleDefinition::class, PromotionDiscountRuleDefinition::class, 'discount_id', 'rule_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}

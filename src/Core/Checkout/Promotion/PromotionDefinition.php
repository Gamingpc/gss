<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Promotion;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionCartRule\PromotionCartRuleDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionDiscount\PromotionDiscountDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionOrderRule\PromotionOrderRuleDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionPersonaCustomer\PromotionPersonaCustomerDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionPersonaRule\PromotionPersonaRuleDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionSalesChannel\PromotionSalesChannelDefinition;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionTranslation\PromotionTranslationDefinition;
use Shopware\Core\Content\Rule\RuleDefinition;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;

class PromotionDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'promotion';
    }

    public function getCollectionClass(): string
    {
        return PromotionCollection::class;
    }

    public function getEntityClass(): string
    {
        return PromotionEntity::class;
    }

    /**
     * Gets the default values for new entity instances.
     */
    public function getDefaults(EntityExistence $existence): array
    {
        if ($existence->exists()) {
            return [];
        }

        return [
            'active' => false,
            'exclusive' => false,
            'useCodes' => false,
            'maxRedemptionsGlobal' => 0,
            'maxRedemptionsPerCustomer' => 0,
        ];
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new TranslatedField('name'),
            (new BoolField('active', 'active'))->addFlags(new Required()),
            new DateField('valid_from', 'validFrom'),
            new DateField('valid_until', 'validUntil'),
            (new IntField('max_redemptions_global', 'maxRedemptionsGlobal'))->addFlags(new Required()),
            (new IntField('max_redemptions_per_customer', 'maxRedemptionsPerCustomer'))->addFlags(new Required()),
            (new BoolField('exclusive', 'exclusive'))->addFlags(new Required()),
            (new BoolField('use_codes', 'useCodes'))->addFlags(new Required()),
            new StringField('code', 'code'),
            (new IntField('order_count', 'orderCount'))->setFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new JsonField('orders_per_customer_count', 'ordersPerCustomerCount'))->setFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new OneToManyAssociationField('salesChannels', PromotionSalesChannelDefinition::class, 'promotion_id', 'id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('discounts', PromotionDiscountDefinition::class, 'promotion_id'))->addFlags(new CascadeDelete()),

            (new ManyToManyAssociationField('personaRules', RuleDefinition::class, PromotionPersonaRuleDefinition::class, 'promotion_id', 'rule_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('personaCustomers', CustomerDefinition::class, PromotionPersonaCustomerDefinition::class, 'promotion_id', 'customer_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('orderRules', RuleDefinition::class, PromotionOrderRuleDefinition::class, 'promotion_id', 'rule_id'))->addFlags(new CascadeDelete()),
            (new ManyToManyAssociationField('cartRules', RuleDefinition::class, PromotionCartRuleDefinition::class, 'promotion_id', 'rule_id'))->addFlags(new CascadeDelete()),

            new TranslationsAssociationField(PromotionTranslationDefinition::class, 'promotion_id'),
        ]);
    }
}

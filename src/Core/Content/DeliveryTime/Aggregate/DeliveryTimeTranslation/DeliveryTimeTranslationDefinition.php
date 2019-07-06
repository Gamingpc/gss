<?php declare(strict_types=1);

namespace Shopware\Core\Content\DeliveryTime\Aggregate\DeliveryTimeTranslation;

use Shopware\Core\Content\DeliveryTime\DeliveryTimeDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class DeliveryTimeTranslationDefinition extends EntityTranslationDefinition
{
    public function getEntityName(): string
    {
        return 'delivery_time_translation';
    }

    public function getEntityClass(): string
    {
        return DeliveryTimeTranslationEntity::class;
    }

    protected function getParentDefinitionClass(): string
    {
        return DeliveryTimeDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            new CustomFields(),
        ]);
    }
}

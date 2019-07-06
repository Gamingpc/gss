<?php declare(strict_types=1);

namespace Shopware\Core\Framework\CustomField\Aggregate\CustomFieldSetRelation;

use Shopware\Core\Framework\CustomField\Aggregate\CustomFieldSet\CustomFieldSetDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CustomFieldSetRelationDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'custom_field_set_relation';
    }

    public function getCollectionClass(): string
    {
        return CustomFieldSetRelationCollection::class;
    }

    public function getEntityClass(): string
    {
        return CustomFieldSetRelationEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new FkField('set_id', 'customFieldSetId', CustomFieldSetDefinition::class))->setFlags(new Required()),
            (new StringField('entity_name', 'entityName', 63))->addFlags(new Required()),

            new ManyToOneAssociationField('customFieldSet', 'set_id', CustomFieldSetDefinition::class, 'id', false),
        ]);
    }
}

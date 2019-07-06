<?php
declare(strict_types=1);

namespace Shopware\Core\Framework\Test\DataAbstractionLayer\Write\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\VersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class DeleteCascadeParentDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'delete_cascade_parent';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey()),

            new VersionField(),

            new FkField('delete_cascade_many_to_one_id', 'deleteCascadeManyToOneId', DeleteCascadeManyToOneDefinition::class),
            new ManyToOneAssociationField('manyToOne', 'delete_cascade_many_to_one_id', DeleteCascadeManyToOneDefinition::class, 'id', false),

            new StringField('name', 'name'),
            (new OneToManyAssociationField('cascades', DeleteCascadeChildDefinition::class, 'delete_cascade_parent_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}

class DeleteCascadeChildDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'delete_cascade_child';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey()),

            (new FkField('delete_cascade_parent_id', 'deleteCascadeParentId', DeleteCascadeParentDefinition::class))->addFlags(new Required()),
            (new ReferenceVersionField(DeleteCascadeParentDefinition::class))->addFlags(new Required()),

            new StringField('name', 'name'),
            new ManyToOneAssociationField('deleteCascadeParent', 'delete_cascade_parent_id', DeleteCascadeParentDefinition::class, 'id', false),
        ]);
    }
}

class DeleteCascadeManyToOneDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'delete_cascade_many_to_one';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey()),

            new StringField('name', 'name'),
            (new OneToManyAssociationField('parents', DeleteCascadeParentDefinition::class, 'delete_cascade_many_to_one_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}

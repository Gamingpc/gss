<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\DataAbstractionLayer\Field\TestDefinition;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\VersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class RootDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'root';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new VersionField(),
            new StringField('name', 'name'),
            new OneToOneAssociationField('sub', 'id', 'root_id', SubDefinition::class),
        ]);
    }
}

class SubDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'root_sub';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new VersionField(),
            new StringField('name', 'name'),
            new IntField('stock', 'stock'),
            new FkField('root_id', 'rootId', RootDefinition::class, 'id'),
            (new ReferenceVersionField(RootDefinition::class))->addFlags(new Required()),
            new OneToOneAssociationField('root', 'root_id', 'id', RootDefinition::class, false),
            new OneToManyAssociationField('manies', SubManyDefinition::class, 'root_sub_id'),
        ]);
    }
}

class SubManyDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'root_sub_many';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new VersionField(),
            new StringField('name', 'name'),
            (new FkField('root_sub_id', 'subId', SubDefinition::class, 'id'))->addFlags(new Required()),
            (new ReferenceVersionField(SubDefinition::class))->addFlags(new Required()),
            new ManyToOneAssociationField('sub', 'root_sub_id', SubDefinition::class, 'id', false),
        ]);
    }
}

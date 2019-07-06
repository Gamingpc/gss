<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\DataAbstractionLayer\Field\TestDefinition;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class WriteProtectedRelationDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return '_test_relation';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),

            (new OneToManyAssociationField('wp', WriteProtectedDefinition::class, 'relation_id', 'id'))->addFlags(new WriteProtected()),
            (new ManyToManyAssociationField('wps', WriteProtectedDefinition::class, WriteProtectedReferenceDefinition::class, 'relation_id', 'wp_id'))->addFlags(new WriteProtected()),

            (new OneToManyAssociationField('systemWp', WriteProtectedDefinition::class, 'system_relation_id', 'id'))->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
            (new ManyToManyAssociationField('systemWps', WriteProtectedDefinition::class, WriteProtectedReferenceDefinition::class, 'system_relation_id', 'system_wp_id'))->addFlags(new WriteProtected(Context::SYSTEM_SCOPE)),
        ]);
    }

    protected function defaultFields(): array
    {
        return [];
    }
}

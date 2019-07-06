<?php declare(strict_types=1);

namespace Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\Language\LanguageDefinition;
use Shopware\Core\Framework\Snippet\Aggregate\SnippetSet\SnippetSetDefinition;
use Shopware\Core\System\Currency\CurrencyDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class SalesChannelDomainDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'sales_channel_domain';
    }

    public function getEntityClass(): string
    {
        return SalesChannelDomainEntity::class;
    }

    public function getCollectionClass(): string
    {
        return SalesChannelDomainCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),

            (new StringField('url', 'url', 255))->addFlags(new Required()),

            (new FkField('sales_channel_id', 'salesChannelId', SalesChannelDefinition::class))->addFlags(new Required()),
            (new FkField('language_id', 'languageId', LanguageDefinition::class))->addFlags(new Required()),
            (new FkField('currency_id', 'currencyId', CurrencyDefinition::class))->addFlags(new Required()),
            (new FkField('snippet_set_id', 'snippetSetId', SnippetSetDefinition::class))->addFlags(new Required()),

            new ManyToOneAssociationField('salesChannel', 'sales_channel_id', SalesChannelDefinition::class, 'id', false),
            new ManyToOneAssociationField('language', 'language_id', LanguageDefinition::class, 'id', false),
            new ManyToOneAssociationField('currency', 'currency_id', CurrencyDefinition::class, 'id', false),
            new ManyToOneAssociationField('snippetSet', 'snippet_set_id', SnippetSetDefinition::class, 'id', false),

            new CustomFields(),
        ]);
    }
}

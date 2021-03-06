<?php declare(strict_types=1);

namespace Shopware\Core\Content\NewsletterReceiver\Aggregate\NewsletterReceiverTag;

use Shopware\Core\Content\NewsletterReceiver\NewsletterReceiverDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use Shopware\Core\System\Tag\TagDefinition;

class NewsletterReceiverTagDefinition extends MappingEntityDefinition
{
    public function getEntityName(): string
    {
        return 'newsletter_receiver_tag';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('newsletter_receiver_id', 'newsletterReceiverId', NewsletterReceiverDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('tag_id', 'tagId', TagDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('newsletterReceiver', 'newsletter_receiver_id', NewsletterReceiverDefinition::class, 'id', false),
            new ManyToOneAssociationField('tag', 'tag_id', TagDefinition::class, 'id', false),
        ]);
    }
}

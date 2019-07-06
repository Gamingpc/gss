<?php declare(strict_types=1);

namespace Shopware\Core\Content\Cms;

use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsBlock\CmsBlockCollection;
use Shopware\Core\Content\Media\MediaEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CmsPageEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var CmsBlockCollection|null
     */
    protected $blocks;

    /**
     * @var EntityCollection|null
     */
    protected $translations;

    /**
     * @var CategoryCollection|null
     */
    protected $categories;

    /**
     * @var array|null
     */
    protected $config;

    /**
     * @var string|null
     */
    protected $previewMediaId;

    /**
     * @var MediaEntity|null
     */
    protected $previewMedia;

    /**
     * @var array|null
     */
    protected $customFields;

    /**
     * @var bool
     */
    protected $locked;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    public function getBlocks(): ?CmsBlockCollection
    {
        return $this->blocks;
    }

    public function setBlocks(CmsBlockCollection $blocks): void
    {
        $this->blocks = $blocks;
    }

    public function getTranslations(): ?EntityCollection
    {
        return $this->translations;
    }

    public function setTranslations(EntityCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getCategories(): ?CategoryCollection
    {
        return $this->categories;
    }

    public function setCategories(CategoryCollection $categories): void
    {
        $this->categories = $categories;
    }

    public function getConfig(): ?array
    {
        return $this->config;
    }

    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    public function getPreviewMediaId(): ?string
    {
        return $this->previewMediaId;
    }

    public function setPreviewMediaId(string $previewMediaId): void
    {
        $this->previewMediaId = $previewMediaId;
    }

    public function getPreviewMedia(): ?MediaEntity
    {
        return $this->previewMedia;
    }

    public function setPreviewMedia(MediaEntity $previewMedia): void
    {
        $this->previewMedia = $previewMedia;
    }

    public function getLocked(): bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): void
    {
        $this->locked = $locked;
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateType;

use Shopware\Core\Content\MailTemplate\Aggregate\MailTemplateTypeTranslation\MailTemplateTypeTranslationCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateCollection;

class MailTemplateTypeEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $technicalName;

    /**
     * @var MailTemplateTypeTranslationCollection|null
     */
    protected $translations;

    /**
     * @var MailTemplateTypeCollection|null
     */
    protected $mailTemplates;

    /**
     * @var array|null
     */
    protected $customFields;

    /**
     * @var \DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface
     */
    protected $updatedAt;

    /**
     * @var StateMachineStateCollection|null
     */
    protected $assignedStateMachineStates;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    public function getTranslations(): ?MailTemplateTypeTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(?MailTemplateTypeTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getMailTemplates(): ?MailTemplateTypeCollection
    {
        return $this->mailTemplates;
    }

    public function setMailTemplates(?MailTemplateTypeCollection $mailTemplates): void
    {
        $this->mailTemplates = $mailTemplates;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getAssignedStateMachineStates(): ?StateMachineStateCollection
    {
        return $this->assignedStateMachineStates;
    }

    public function setAssignedStateMachineStates(?StateMachineStateCollection $assignedStateMachineStates): void
    {
        $this->assignedStateMachineStates = $assignedStateMachineStates;
    }
}

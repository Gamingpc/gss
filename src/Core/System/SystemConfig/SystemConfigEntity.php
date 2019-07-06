<?php declare(strict_types=1);

namespace Shopware\Core\System\SystemConfig;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class SystemConfigEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $configurationKey;

    /**
     * @var mixed
     */
    protected $configurationValue;

    /**
     * @var string|null
     */
    protected $salesChannelId;

    /**
     * @var ?SalesChannelEntity
     */
    protected $salesChannel;

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getConfigurationKey(): string
    {
        return $this->configurationKey;
    }

    public function setConfigurationKey(string $configurationKey): void
    {
        $this->configurationKey = $configurationKey;
    }

    public function getConfigurationValue()
    {
        return $this->configurationValue;
    }

    public function setConfigurationValue($configurationValue): void
    {
        $this->configurationValue = $configurationValue;
    }

    public function getSalesChannelId(): ?string
    {
        return $this->salesChannelId;
    }

    public function setSalesChannelId(?string $salesChannelId): void
    {
        $this->salesChannelId = $salesChannelId;
    }

    public function getSalesChannel(): ?SalesChannelEntity
    {
        return $this->salesChannel;
    }

    public function setSalesChannel(SalesChannelEntity $salesChannel): void
    {
        $this->salesChannel = $salesChannel;
    }
}

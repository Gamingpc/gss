<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Field;

use Shopware\Core\Framework\DataAbstractionLayer\FieldSerializer\PasswordFieldSerializer;

class PasswordField extends Field implements StorageAware
{
    /**
     * @var string
     */
    private $storageName;

    /**
     * @var int
     */
    private $algorithm;

    /**
     * @var array
     */
    private $hashOptions;

    public function __construct(string $storageName, string $propertyName, int $algorithm = PASSWORD_DEFAULT, array $hashOptions = [])
    {
        parent::__construct($propertyName);
        $this->storageName = $storageName;
        $this->algorithm = $algorithm;
        $this->hashOptions = $hashOptions;
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    public function getAlgorithm(): int
    {
        return $this->algorithm;
    }

    public function getHashOptions(): array
    {
        return $this->hashOptions;
    }

    protected function getSerializerClass(): string
    {
        return PasswordFieldSerializer::class;
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\FieldSerializer;

use Shopware\Core\Framework\DataAbstractionLayer\Exception\DecodeByHydratorException;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InvalidSerializerFieldException;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use Shopware\Core\Framework\DataAbstractionLayer\Write\FieldException\ExpectedArrayException;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteCommandExtractor;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use Shopware\Core\Framework\Uuid\Uuid;

class ManyToOneAssociationFieldSerializer implements FieldSerializerInterface
{
    /**
     * @var WriteCommandExtractor
     */
    protected $writeExtractor;

    public function __construct(
        WriteCommandExtractor $writeExtractor
    ) {
        $this->writeExtractor = $writeExtractor;
    }

    public function encode(
        Field $field,
        EntityExistence $existence,
        KeyValuePair $data,
        WriteParameterBag $parameters
    ): \Generator {
        if (!$field instanceof ManyToOneAssociationField) {
            throw new InvalidSerializerFieldException(ManyToOneAssociationField::class, $field);
        }
        /* @var ManyToOneAssociationField $field */
        if (!\is_array($data->getValue())) {
            throw new ExpectedArrayException($parameters->getPath());
        }

        $referenceField = $field->getReferenceDefinition()->getFields()->getByStorageName($field->getReferenceField());
        $value = $data->getValue();
        if (isset($value[$referenceField->getPropertyName()])) {
            $id = $value[$referenceField->getPropertyName()];
        } else {
            $id = Uuid::randomHex();
            $value[$referenceField->getPropertyName()] = $id;
        }

        $this->writeExtractor->extract(
            $value,
            $parameters->cloneForSubresource(
                $field->getReferenceDefinition(),
                $parameters->getPath() . '/' . $data->getKey()
            )
        );

        $fkField = $parameters->getDefinition()->getFields()->getByStorageName($field->getStorageName());

        /* @var FkField $fkField */
        yield $fkField->getPropertyName() => $id;
    }

    public function decode(Field $field, $value): void
    {
        throw new DecodeByHydratorException($field);
    }
}

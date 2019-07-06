<?php
declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\FieldSerializer;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InvalidSerializerFieldException;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Field;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use Shopware\Core\Framework\Uuid\Uuid;

class ReferenceVersionFieldSerializer implements FieldSerializerInterface
{
    public function encode(
        Field $field,
        EntityExistence $existence,
        KeyValuePair $data,
        WriteParameterBag $parameters
    ): \Generator {
        if (!$field instanceof ReferenceVersionField) {
            throw new InvalidSerializerFieldException(ReferenceVersionField::class, $field);
        }

        /** @var ReferenceVersionField $field */
        if ($data->getValue() !== null || $parameters->getDefinition() === $field->getVersionReferenceDefinition()) {
            // parent inheritance with versioning
            $value = $data->getValue() ?? Defaults::LIVE_VERSION;
        } elseif ($parameters->getContext()->has($field->getVersionReferenceDefinition()->getClass(), 'versionId')) {
            // if the reference is already written, use the version id of the written entity
            $value = $parameters->getContext()->get($field->getVersionReferenceDefinition()->getClass(), 'versionId');
        } elseif ($parameters->getDefinition()->getParentDefinition() === $field->getVersionReferenceDefinition()) {
            // if the current entity is a sub entity (e.g. order -> line-item)
            // and the version id isn't set, use the same version id of the own entity
            // this is the case, if a entity is created over a sub api call
            $value = $parameters->getContext()->get($parameters->getDefinition()->getClass(), 'versionId');
        } else {
            $value = Defaults::LIVE_VERSION;
        }

        yield $field->getStorageName() => Uuid::fromHexToBytes($value);
    }

    public function decode(Field $field, $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return Uuid::fromBytesToHex($value);
    }
}

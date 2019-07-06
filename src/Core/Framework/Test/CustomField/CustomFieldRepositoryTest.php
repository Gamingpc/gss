<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\CustomField;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\CustomField\CustomFieldDefinition;
use Shopware\Core\Framework\CustomField\CustomFieldEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;

class CustomFieldRepositoryTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testCreate(): void
    {
        $repo = $this->getContainer()->get('custom_field.repository');

        $id = Uuid::randomHex();
        $attribute = [
            'id' => $id,
            'name' => 'foo.size',
            'type' => 'int',
        ];
        $result = $repo->create([$attribute], Context::createDefaultContext());

        $events = $result->getEventByDefinition(CustomFieldDefinition::class);
        static::assertNotNull($events);

        $payloads = $events->getPayloads();
        static::assertNotEmpty($payloads);

        static::assertEquals($attribute['id'], $payloads[0]['id']);
        static::assertEquals($attribute['name'], $payloads[0]['name']);
        static::assertEquals($attribute['type'], $payloads[0]['type']);
    }

    public function testSearchId(): void
    {
        $repo = $this->getContainer()->get('custom_field.repository');

        $sizeId = Uuid::randomHex();
        $descriptionId = Uuid::randomHex();
        $attributes = [
            [
                'id' => $sizeId,
                'name' => 'foo.size',
                'type' => 'int',
                'config' => ['fieldType' => 'color-picker'],
            ],
            [
                'id' => $descriptionId,
                'name' => 'foo.description',
                'type' => 'string',
                'config' => ['fieldType' => 'date-picker'],
            ],
        ];
        $repo->create($attributes, Context::createDefaultContext());
        $result = $repo->search(new Criteria([$sizeId]), Context::createDefaultContext());

        /** @var CustomFieldEntity $attribute */
        $attribute = $result->first();
        static::assertNotNull($attribute);

        static::assertEquals($sizeId, $attribute->getId());
        static::assertEquals($attributes[0]['name'], $attribute->getName());
        static::assertEquals($attributes[0]['type'], $attribute->getType());
        static::assertEquals($attributes[0]['config'], $attribute->getConfig());
    }

    public function testDelete(): void
    {
        $repo = $this->getContainer()->get('custom_field.repository');

        $sizeId = Uuid::randomHex();
        $descriptionId = Uuid::randomHex();
        $attributes = [
            [
                'id' => $sizeId,
                'name' => 'foo.size',
                'type' => 'int',
            ],
            [
                'id' => $descriptionId,
                'name' => 'foo.description',
                'type' => 'string',
            ],
        ];
        $repo->create($attributes, Context::createDefaultContext());

        $result = $repo->delete([['id' => $sizeId]], Context::createDefaultContext());
        $event = $result->getEventByDefinition(CustomFieldDefinition::class);

        static::assertCount(1, $event->getIds());
        static::assertEquals($sizeId, $event->getIds()[0]);
    }

    public function testUpdate(): void
    {
        $repo = $this->getContainer()->get('custom_field.repository');

        $sizeId = Uuid::randomHex();
        $descriptionId = Uuid::randomHex();
        $attributes = [
            [
                'id' => $sizeId,
                'name' => 'foo.size',
                'type' => 'int',
            ],
            [
                'id' => $descriptionId,
                'name' => 'foo.description',
                'type' => 'string',
            ],
        ];
        $repo->create($attributes, Context::createDefaultContext());

        $update = [
            'id' => $descriptionId,
            'name' => 'Updated name',
        ];
        $result = $repo->update([$update], Context::createDefaultContext());

        $event = $result->getEventByDefinition(CustomFieldDefinition::class);
        static::assertCount(1, $event->getPayloads());
    }

    public function testUpsert(): void
    {
        $repo = $this->getContainer()->get('custom_field.repository');

        $sizeId = Uuid::randomHex();
        $descriptionId = Uuid::randomHex();
        $attributes = [
            [
                'id' => $sizeId,
                'name' => 'foo.size',
                'type' => 'int',
                'label' => 'The size of foo products',
            ],
            [
                'id' => $descriptionId,
                'name' => 'foo.description',
                'type' => 'string',
                'label' => 'Foo description',
            ],
        ];
        $result = $repo->upsert($attributes, Context::createDefaultContext());
        $event = $result->getEventByDefinition(CustomFieldDefinition::class);
        static::assertCount(2, $event->getPayloads());

        $result = $repo->upsert($attributes, Context::createDefaultContext());
        $event = $result->getEventByDefinition(CustomFieldDefinition::class);
        static::assertCount(2, $event->getPayloads());
    }
}

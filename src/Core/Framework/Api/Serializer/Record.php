<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Api\Serializer;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class Record implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $attributes = [
        'extensions' => [],
    ];

    /**
     * @var array
     */
    protected $links = [];

    /**
     * @var array[]
     */
    protected $relationships = [];

    /**
     * @var array
     */
    protected $meta;

    public function __construct(string $id = '', string $type = '')
    {
        $this->id = $id;
        $this->type = $type;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @return array[]
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function setAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function addLink(string $key, string $link): void
    {
        $this->links[$key] = $link;
    }

    public function getLink(string $key): string
    {
        return $this->links[$key];
    }

    public function addRelationship(string $key, array $relationship): void
    {
        $this->relationships[$key] = $relationship;
    }

    public function addMeta(string $key, $data): void
    {
        $this->meta[$key] = $data;
    }

    public function jsonSerialize(): array
    {
        $vars = get_object_vars($this);

        foreach ($vars['relationships'] as $i => $x) {
            unset($vars['relationships'][$i]['tmp']);
        }

        return $vars;
    }

    public function addExtension(string $key, $value): void
    {
        $this->attributes['extensions'][$key] = $value;
    }

    public function merge(Entity $entity): void
    {
        $this->id = $entity->getUniqueIdentifier();

        $data = $entity->jsonSerialize();

        foreach ($this->attributes as $key => $relationship) {
            $this->attributes[$key] = $data[$key] ?? null;
        }

        if (!empty($data['extensions']) && array_key_exists('extensions', $this->attributes) && is_array($this->attributes['extensions'])) {
            foreach ($this->attributes['extensions'] as $key => $relationship) {
                $this->attributes['extensions'][$key] = $data['extensions'][$key] ?? null;
            }
        } else {
            unset($this->attributes['extensions']);
        }

        foreach ($this->relationships as $key => &$relationship) {
            /** @var Entity|EntityCollection|null $relationData */
            $relationData = $data[$key] ?? null;

            if ($relationData === null) {
                continue;
            }

            $entityName = $relationship['tmp']['definition']->getEntityName();

            if ($relationData instanceof EntityCollection) {
                $relationship['data'] = [];

                foreach ($relationData as $item) {
                    $relationship['data'][] = [
                        'type' => $entityName,
                        'id' => $item->getUniqueIdentifier(),
                    ];
                }
            } else {
                $relationship['data'] = [
                    'type' => $entityName,
                    'id' => $relationData->getUniqueIdentifier(),
                ];
            }
        }
    }

    public function setRelationships(array $relationships): void
    {
        $this->relationships = $relationships;
    }
}

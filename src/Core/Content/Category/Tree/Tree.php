<?php declare(strict_types=1);

namespace Shopware\Core\Content\Category\Tree;

use Shopware\Core\Content\Category\CategoryEntity;

class Tree
{
    /**
     * @var TreeItem[]
     */
    protected $tree;

    /**
     * @var CategoryEntity
     */
    protected $active;

    public function __construct(CategoryEntity $active, array $tree)
    {
        $this->tree = $tree;
        $this->active = $active;
    }

    public function isSelected(CategoryEntity $category): bool
    {
        if ($category->getId() === $this->active->getId()) {
            return true;
        }

        $ids = explode('|', $this->active->getPath());

        return \in_array($category->getId(), $ids, true);
    }

    public function getTree(): array
    {
        return $this->tree;
    }

    public function setTree(array $tree): void
    {
        $this->tree = $tree;
    }

    public function getActive(): CategoryEntity
    {
        return $this->active;
    }

    public function setActive(CategoryEntity $active): void
    {
        $this->active = $active;
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation;

use Shopware\Core\Framework\DataAbstractionLayer\Search\CriteriaPartInterface;

interface Aggregation extends CriteriaPartInterface
{
    public function getField(): string;

    public function getName(): string;

    /**
     * @return string[]
     */
    public function getGroupByFields(): array;
}

<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Search\Aggregation;

use Shopware\Core\Framework\Struct\Collection;

/**
 * @method void                   set(string $key, AggregationResult $entity)
 * @method AggregationResult[]    getIterator()
 * @method AggregationResult[]    getElements()
 * @method AggregationResult|null first()
 * @method AggregationResult|null last()
 */
class AggregationResultCollection extends Collection
{
    /**
     * @param AggregationResult $result
     */
    public function add($result): void
    {
        $this->set($result->getAggregation()->getName(), $result);
    }

    public function get($name): ?AggregationResult
    {
        return $this->elements[$name] ?? null;
    }

    protected function getExpectedClass(): ?string
    {
        return AggregationResult::class;
    }
}

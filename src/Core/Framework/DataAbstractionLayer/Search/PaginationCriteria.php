<?php declare(strict_types=1);

namespace Shopware\Core\Framework\DataAbstractionLayer\Search;

class PaginationCriteria extends Criteria
{
    public function __construct(?int $limit, ?int $offset = null)
    {
        parent::__construct();
        $this->limit = $limit;
        $this->offset = $offset;
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Pricing;

use Shopware\Core\Framework\Struct\Struct;

class Price extends Struct
{
    /**
     * @var float
     */
    protected $net;

    /**
     * @var float
     */
    protected $gross;

    /**
     * @var bool
     */
    protected $linked;

    public function __construct(float $net, float $gross, bool $linked)
    {
        $this->net = $net;
        $this->gross = $gross;
        $this->linked = $linked;
    }

    public function getNet(): float
    {
        return $this->net;
    }

    public function setNet(float $net): void
    {
        $this->net = $net;
    }

    public function getGross(): float
    {
        return $this->gross;
    }

    public function setGross(float $gross): void
    {
        $this->gross = $gross;
    }

    public function getLinked(): bool
    {
        return $this->linked;
    }

    public function setLinked(bool $linked): void
    {
        $this->linked = $linked;
    }

    public function add(self $price): void
    {
        $this->gross += $price->getGross();
        $this->net += $price->getNet();
    }
}

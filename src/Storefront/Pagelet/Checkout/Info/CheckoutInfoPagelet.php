<?php declare(strict_types=1);

namespace Shopware\Storefront\Pagelet\Checkout\Info;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Framework\Struct\Struct;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CheckoutInfoPagelet extends Struct
{
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var SalesChannelContext
     */
    private $context;

    public function __construct(Cart $cart, SalesChannelContext $context)
    {
        $this->cart = $cart;
        $this->context = $context;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function setContext(SalesChannelContext $context): CheckoutInfoPagelet
    {
        $this->context = $context;

        return $this;
    }
}

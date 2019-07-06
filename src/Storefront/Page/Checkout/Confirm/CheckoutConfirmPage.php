<?php declare(strict_types=1);

namespace Shopware\Storefront\Page\Checkout\Confirm;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Payment\PaymentMethodCollection;
use Shopware\Core\Checkout\Shipping\ShippingMethodCollection;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Page\GenericPage;

class CheckoutConfirmPage extends GenericPage
{
    /**
     * @var Cart
     */
    protected $cart;

    /**
     * @var PaymentMethodCollection
     */
    protected $paymentMethods;

    /**
     * @var ShippingMethodCollection
     */
    protected $shippingMethods;

    public function __construct(
        SalesChannelContext $context,
        PaymentMethodCollection $paymentMethods,
        ShippingMethodCollection $shippingMethods
    ) {
        parent::__construct($context);
        $this->paymentMethods = $paymentMethods;
        $this->shippingMethods = $shippingMethods;
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): void
    {
        $this->cart = $cart;
    }

    public function getPaymentMethods(): PaymentMethodCollection
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(PaymentMethodCollection $paymentMethods): void
    {
        $this->paymentMethods = $paymentMethods;
    }

    public function getShippingMethods(): ShippingMethodCollection
    {
        return $this->shippingMethods;
    }

    public function setShippingMethods(ShippingMethodCollection $shippingMethods): void
    {
        $this->shippingMethods = $shippingMethods;
    }
}

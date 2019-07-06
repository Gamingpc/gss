<?php declare(strict_types=1);

namespace Shopware\Storefront\Event;

use Shopware\Storefront\Page\Account\Address\AccountAddressPageLoadedEvent;
use Shopware\Storefront\Page\Account\AddressList\AccountAddressListPageLoadedEvent;
use Shopware\Storefront\Page\Account\Login\AccountLoginPageLoadedEvent;
use Shopware\Storefront\Page\Account\Order\AccountOrderPageLoadedEvent;
use Shopware\Storefront\Page\Account\Overview\AccountOverviewPageLoadedEvent;
use Shopware\Storefront\Page\Account\PaymentMethod\AccountPaymentMethodPageLoadedEvent;
use Shopware\Storefront\Page\Account\Profile\AccountProfilePageLoadedEvent;
use Shopware\Storefront\Pagelet\Account\AddressList\AccountAddressListPageletLoadedEvent;
use Shopware\Storefront\Pagelet\Account\Order\AccountOrderPageletLoadedEvent;
use Shopware\Storefront\Pagelet\Account\PaymentMethod\AccountPaymentMethodPageletLoadedEvent;

class AccountEvents
{
    /**
     * @Event("Shopware\Storefront\Pagelet\Account\AddressList\AccountAddressListPageletLoadedEvent")
     */
    public const ACCOUNT_ADDRESS_LIST_PAGELET_LOADED_EVENT = AccountAddressListPageletLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Pagelet\Account\Order\AccountOrderPageletLoadedEvent")
     */
    public const ACCOUNT_ORDER_PAGELET_LOADED_EVENT = AccountOrderPageletLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Pagelet\Account\PaymentMethod\AccountPaymentMethodPageletLoadedEvent")
     */
    public const ACCOUNT_PAYMENT_METHOD_PAGELET_LOADED_EVENT = AccountPaymentMethodPageletLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\Profile\AccountProfilePageLoadedEvent")
     */
    public const ACCOUNT_PROFILE_PAGE_LOADED_EVENT = AccountProfilePageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\PaymentMethod\AccountPaymentMethodPageLoadedEvent")
     */
    public const ACCOUNT_PAYMENT_METHOD_PAGE_LOADED_EVENT = AccountPaymentMethodPageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\Overview\AccountOverviewPageLoadedEvent")
     */
    public const ACCOUNT_OVERVIEW_PAGE_LOADED_EVENT = AccountOverviewPageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\Order\AccountOrderPageLoadedEvent")
     */
    public const ACCOUNT_ORDER_PAGE_LOADED_EVENT = AccountOrderPageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\Login\AccountLoginPageLoadedEvent")
     */
    public const ACCOUNT_LOGIN_PAGE_LOADED_EVENT = AccountLoginPageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\AddressList\AccountAddressListPageLoadedEvent")
     */
    public const ACCOUNT_ADDRESS_LIST_PAGE_LOADED_EVENT = AccountAddressListPageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Page\Account\Address\AccountAddressPageLoadedEvent")
     */
    public const ACCOUNT_ADDRESS_PAGE_LOADED_EVENT = AccountAddressPageLoadedEvent::NAME;
}

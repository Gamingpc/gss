<?php declare(strict_types=1);

namespace Shopware\Core\Content\MailTemplate;

class MailTemplateTypes
{
    public const NEWSLETTER = 'newsletter';

    public const NEWSLETTER_DO_CONFIRM = 'newsletter_do_confirm'; // after subscription with confirm instructions

    public const NEWSLETTER_CONFIRMED = 'newsletter_confirmed'; // after confirmation is done

    public const DELIVERY_NOTE = 'delivery_mail';

    public const INVOICE = 'invoice_mail';

    public const CREDIT_NOTE = 'credit_note_mail';

    public const STORNO = 'storno_mail';

    public const MAILTYPE_ORDER_CONFIRM = 'order_confirmation_mail';

    public const MAILTYPE_PASSWORD_CHANGE = 'password_change';

    public const MAILTYPE_STOCK_WARNING = 'product_stock_warning';

    public const MAILTYPE_CUSTOMER_GROUP_CHANGE_ACCEPT = 'customer_group_change_accept';

    public const MAILTYPE_CUSTOMER_GROUP_CHANGE_REJECT = 'customer_group_change_reject';

    public const MAILTYPE_CUSTOMER_REGISTER = 'customer_register';

    public const MAILTYPE_SEPA_CONFIRMATION = 'sepa_confirmation';
    public const MAILTYPE_STATE_ENTER_ORDER_DELIVERY_STATE_SHIPPED_PARTIALLY = 'state_enter.order_delivery.state.shipped_partially';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_REFUNDED_PARTIALLY = 'state_enter.order_transaction.state.refunded_partially';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_REMINDED = 'state_enter.order_transaction.state.reminded';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_OPEN = 'state_enter.order_transaction.state.open';
    public const MAILTYPE_STATE_ENTER_ORDER_DELIVERY_STATE_RETURNED_PARTIALLY = 'state_enter.order_delivery.state.returned_partially';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_PAID = 'state_enter.order_transaction.state.paid';
    public const MAILTYPE_STATE_ENTER_ORDER_DELIVERY_STATE_RETURNED = 'state_enter.order_delivery.state.returned';
    public const MAILTYPE_STATE_ENTER_ORDER_STATE_CANCELLED = 'state_enter.order.state.cancelled';
    public const MAILTYPE_STATE_ENTER_ORDER_DELIVERY_STATE_CANCELLED = 'state_enter.order_delivery.state.cancelled';
    public const MAILTYPE_STATE_ENTER_ORDER_DELIVERY_STATE_SHIPPED = 'state_enter.order_delivery.state.shipped';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_CANCELLED = 'state_enter.order_transaction.state.cancelled';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_REFUNDED = 'state_enter.order_transaction.state.refunded';
    public const MAILTYPE_STATE_ENTER_ORDER_TRANSACTION_STATE_PAID_PARTIALLY = 'state_enter.order_transaction.state.paid_partially';
    public const MAILTYPE_STATE_ENTER_ORDER_STATE_OPEN = 'state_enter.order.state.open';
    public const MAILTYPE_STATE_ENTER_ORDER_STATE_IN_PROGRESS = 'state_enter.order.state.in_progress';
    public const MAILTYPE_STATE_ENTER_ORDER_STATE_COMPLETED = 'state_enter.order.state.completed';
}

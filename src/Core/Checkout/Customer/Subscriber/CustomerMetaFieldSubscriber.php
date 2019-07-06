<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\Subscriber;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Order\OrderDefinition;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Order\OrderEvents;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerMetaFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $customerRepository;

    public function __construct(EntityRepositoryInterface $orderRepository, EntityRepositoryInterface $customerRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OrderEvents::ORDER_WRITTEN_EVENT => 'fillCustomerMetaDataFields',
        ];
    }

    public function fillCustomerMetaDataFields(EntityWrittenEvent $event): void
    {
        if ($event->getDefinition()->getClass() !== OrderDefinition::class) {
            return;
        }

        $context = $event->getContext();

        foreach ($event->getWriteResults() as $writeResult) {
            if ($writeResult->getExistence() !== null && $writeResult->getExistence()->exists()) {
                break;
            }

            $payload = $writeResult->getPayload();
            /** @var \DateTimeInterface $orderDate */
            $orderDate = $payload['orderDate'];

            /** @var EntitySearchResult $orderResult */
            $orderResult = $this->orderRepository->search(
                (new Criteria([$payload['id']]))->addAssociation('orderCustomer'),
                $context
            );

            /** @var OrderEntity|null $order */
            $order = $orderResult->first();

            if (!($order instanceof OrderEntity)) {
                continue;
            }

            $customerId = $order->getOrderCustomer()->getCustomerId();
            $orderCount = 0;

            /** @var EntitySearchResult $customerResult */
            $customerResult = $this->customerRepository->search(
                (new Criteria([$customerId]))->addAssociation('orderCustomers'),
                $context
            );

            /** @var CustomerEntity $customer */
            $customer = $customerResult->first();

            if ($customer !== null && $customer->getOrderCustomers()) {
                $orderCount = $customer->getOrderCustomers()->count();
            }

            $data = [
                [
                    'id' => $customerId,
                    'orderCount' => $orderCount,
                    'lastOrderDate' => $orderDate->format('Y-m-d H:i:s.v'),
                ],
            ];

            $context->scope(Context::SYSTEM_SCOPE, function () use ($data, $context) {
                $this->customerRepository->update($data, $context);
            });
        }
    }
}

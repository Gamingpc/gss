<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Order\Api;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\CartPrice;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionDefinition;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\AdminApiTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\PlatformRequest;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineHistory\StateMachineHistoryCollection;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineHistory\StateMachineHistoryEntity;
use Shopware\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;
use Shopware\Core\System\StateMachine\StateMachineRegistry;
use Symfony\Component\HttpFoundation\Response;

class OrderTransactionActionControllerTest extends TestCase
{
    use AdminApiTestBehaviour;
    use IntegrationTestBehaviour;

    /**
     * @var StateMachineRegistry
     */
    private $stateMachineRegistry;

    /**
     * @var EntityRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $orderTransactionRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $stateMachineHistoryRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stateMachineRegistry = $this->getContainer()->get(StateMachineRegistry::class);
        $this->orderRepository = $this->getContainer()->get('order.repository');
        $this->customerRepository = $this->getContainer()->get('customer.repository');
        $this->orderTransactionRepository = $this->getContainer()->get('order_transaction.repository');
        $this->stateMachineHistoryRepository = $this->getContainer()->get('state_machine_history.repository');
    }

    public function testOrderNotFoundException(): void
    {
        $this->getClient()->request('GET', '/api/v' . PlatformRequest::API_VERSION . '/order-transaction/' . Uuid::randomHex() . '/actions/state');

        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response, true);

        static::assertEquals(Response::HTTP_NOT_FOUND, $this->getClient()->getResponse()->getStatusCode());
        static::assertArrayHasKey('errors', $response);
    }

    public function testGetAvailableStates(): void
    {
        $context = Context::createDefaultContext();
        $customerId = $this->createCustomer($context);
        $orderId = $this->createOrder($customerId, $context);
        $transactionId = $this->createOrderTransaction($orderId, $context);

        $this->getClient()->request('GET', '/api/v' . PlatformRequest::API_VERSION . '/_action/order-transaction/' . $transactionId . '/state');

        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response, true);

        static::assertEquals(Response::HTTP_OK, $this->getClient()->getResponse()->getStatusCode());
        static::assertNotNull($response['currentState']);
        static::assertEquals(OrderTransactionStates::STATE_OPEN, $response['currentState']['technicalName']);

        static::assertCount(4, $response['transitions']);
        static::assertEquals('cancel', $response['transitions'][0]['actionName']);
        static::assertStringEndsWith('/_action/order-transaction/' . $transactionId . '/state/cancel', $response['transitions'][0]['url']);
    }

    public function testTransitionToAllowedState(): void
    {
        $context = Context::createDefaultContext();
        $customerId = $this->createCustomer($context);
        $orderId = $this->createOrder($customerId, $context);
        $transactionId = $this->createOrderTransaction($orderId, $context);

        $this->getClient()->request('GET', '/api/v' . PlatformRequest::API_VERSION . '/_action/order-transaction/' . $transactionId . '/state');

        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response, true);

        $actionUrl = $response['transitions'][0]['url'];
        $transitionTechnicalName = $response['transitions'][0]['technicalName'];
        $startStateTechnicalName = $response['currentState']['technicalName'];

        $this->getClient()->request('POST', $actionUrl);

        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response, true);

        static::assertEquals(Response::HTTP_OK, $this->getClient()->getResponse()->getStatusCode(), print_r($response, true));
        static::assertEquals($transactionId, $response['data']['id']);

        $stateId = $response['data']['relationships']['stateMachineState']['data']['id'] ?? null;
        static::assertNotNull($stateId);

        $destinationStateTechnicalName = null;

        foreach ($response['included'] as $relationship) {
            if ($relationship['type'] === $this->getContainer()->get(StateMachineStateDefinition::class)->getEntityName()) {
                $destinationStateTechnicalName = $relationship['attributes']['technicalName'];
                break;
            }
        }

        static::assertEquals($transitionTechnicalName, $destinationStateTechnicalName);

        // test whether the state history was written
        /** @var StateMachineHistoryCollection $history */
        $history = $this->stateMachineHistoryRepository->search(new Criteria(), $context);

        static::assertCount(1, $history->getElements(), 'Expected history to be written');
        /** @var StateMachineHistoryEntity $historyEntry */
        $historyEntry = array_values($history->getElements())[0];

        static::assertEquals($startStateTechnicalName, $historyEntry->getFromStateMachineState()->getTechnicalName());
        static::assertEquals($destinationStateTechnicalName, $historyEntry->getToStateMachineState()->getTechnicalName());

        static::assertEquals($this->getContainer()->get(OrderTransactionDefinition::class)->getEntityName(), $historyEntry->getEntityName());
        static::assertEquals($transactionId, $historyEntry->getEntityId()['id']);
        static::assertEquals(Defaults::LIVE_VERSION, $historyEntry->getEntityId()['version_id']);
    }

    public function testTransitionToNotAllowedState(): void
    {
        $context = Context::createDefaultContext();
        $customerId = $this->createCustomer($context);
        $orderId = $this->createOrder($customerId, $context);
        $transactionId = $this->createOrderTransaction($orderId, $context);

        $this->getClient()->request('POST', '/api/v' . PlatformRequest::API_VERSION . '/_action/order-transaction/' . $transactionId . '/state/foo');

        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response, true);

        static::assertEquals(Response::HTTP_BAD_REQUEST, $this->getClient()->getResponse()->getStatusCode());
        static::assertArrayHasKey('errors', $response);
    }

    public function testTransitionToEmptyState(): void
    {
        $context = Context::createDefaultContext();
        $customerId = $this->createCustomer($context);
        $orderId = $this->createOrder($customerId, $context);
        $transactionId = $this->createOrderTransaction($orderId, $context);

        $this->getClient()->request('POST', '/api/v' . PlatformRequest::API_VERSION . '/_action/order-transaction/' . $transactionId . '/state');

        $response = $this->getClient()->getResponse()->getContent();
        $response = json_decode($response, true);

        static::assertEquals(Response::HTTP_BAD_REQUEST, $this->getClient()->getResponse()->getStatusCode());
        static::assertArrayHasKey('errors', $response);
    }

    private function createOrder(string $customerId, Context $context): string
    {
        $orderId = Uuid::randomHex();
        $stateId = $this->stateMachineRegistry->getInitialState(OrderStates::STATE_MACHINE, $context)->getId();
        $billingAddressId = Uuid::randomHex();

        $order = [
            'id' => $orderId,
            'orderDate' => (new \DateTimeImmutable())->format(Defaults::STORAGE_DATE_FORMAT),
            'price' => new CartPrice(10, 10, 10, new CalculatedTaxCollection(), new TaxRuleCollection(), CartPrice::TAX_STATE_NET),
            'shippingCosts' => new CalculatedPrice(10, 10, new CalculatedTaxCollection(), new TaxRuleCollection()),
            'orderCustomer' => [
                'customerId' => $customerId,
                'email' => 'test@example.com',
                'salutationId' => $this->getValidSalutationId(),
                'firstName' => 'Max',
                'lastName' => 'Mustermann',
            ],
            'stateId' => $stateId,
            'paymentMethodId' => $this->getValidPaymentMethodId(),
            'currencyId' => Defaults::CURRENCY,
            'currencyFactor' => 1.0,
            'salesChannelId' => Defaults::SALES_CHANNEL,
            'billingAddressId' => $billingAddressId,
            'addresses' => [
                [
                    'salutationId' => $this->getValidSalutationId(),
                    'firstName' => 'Max',
                    'lastName' => 'Mustermann',
                    'street' => 'Ebbinghoff 10',
                    'zipcode' => '48624',
                    'city' => 'Schöppingen',
                    'countryId' => $this->getValidCountryId(),
                ],
            ],
            'lineItems' => [],
            'deliveries' => [
            ],
            'context' => '{}',
            'payload' => '{}',
        ];

        $this->orderRepository->upsert([$order], $context);

        return $orderId;
    }

    private function createCustomer(Context $context): string
    {
        $customerId = Uuid::randomHex();
        $addressId = Uuid::randomHex();

        $customer = [
            'id' => $customerId,
            'customerNumber' => '1337',
            'salutationId' => $this->getValidSalutationId(),
            'firstName' => 'Max',
            'lastName' => 'Mustermann',
            'email' => Uuid::randomHex() . '@example.com',
            'password' => 'shopware',
            'defaultPaymentMethodId' => $this->getValidPaymentMethodId(),
            'groupId' => Defaults::FALLBACK_CUSTOMER_GROUP,
            'salesChannelId' => Defaults::SALES_CHANNEL,
            'defaultBillingAddressId' => $addressId,
            'defaultShippingAddressId' => $addressId,
            'addresses' => [
                [
                    'id' => $addressId,
                    'customerId' => $customerId,
                    'countryId' => $this->getValidCountryId(),
                    'salutationId' => $this->getValidSalutationId(),
                    'firstName' => 'Max',
                    'lastName' => 'Mustermann',
                    'street' => 'Ebbinghoff 10',
                    'zipcode' => '48624',
                    'city' => 'Schöppingen',
                ],
            ],
        ];

        $this->customerRepository->upsert([$customer], $context);

        return $customerId;
    }

    private function createOrderTransaction(string $orderId, Context $context): string
    {
        $transactionId = Uuid::randomHex();
        $stateId = $this->stateMachineRegistry->getInitialState(OrderTransactionStates::STATE_MACHINE, $context)->getId();

        $transaction = [
            'id' => $transactionId,
            'orderId' => $orderId,
            'paymentMethodId' => $this->getValidPaymentMethodId(),
            'stateId' => $stateId,
            'amount' => new CalculatedPrice(
                100,
                100,
                new CalculatedTaxCollection(),
                new TaxRuleCollection()
            ),
        ];

        $this->orderTransactionRepository->upsert([$transaction], $context);

        return $transactionId;
    }
}

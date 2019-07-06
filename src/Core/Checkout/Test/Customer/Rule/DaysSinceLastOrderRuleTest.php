<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Customer\Rule;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\CheckoutRuleScope;
use Shopware\Core\Checkout\Customer\CustomerCollection;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Rule\DaysSinceLastOrderRule;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Write\FieldException\WriteStackException;
use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Rule\RuleScope;
use Shopware\Core\Framework\Test\TestCaseBase\DatabaseTransactionBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Validator\Constraints\NotBlank;

class DaysSinceLastOrderRuleTest extends TestCase
{
    use KernelTestBehaviour;
    use DatabaseTransactionBehaviour;
    use OrderFixture;

    /**
     * @var EntityRepositoryInterface
     */
    private $ruleRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $conditionRepository;

    /**
     * @var Context
     */
    private $context;

    protected function setUp(): void
    {
        $this->ruleRepository = $this->getContainer()->get('rule.repository');
        $this->conditionRepository = $this->getContainer()->get('rule_condition.repository');
        $this->context = Context::createDefaultContext();
    }

    public function testValidateWithMissingValues(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new DaysSinceLastOrderRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var ConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/daysPassed', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should not be blank.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithEmptyValues(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new DaysSinceLastOrderRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'daysPassed' => null,
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var ConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/daysPassed', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should not be blank.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithStringValue(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new DaysSinceLastOrderRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'daysPassed' => '10',
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var ConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/daysPassed', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame('This value should be of type int.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithInvalidValue(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new DaysSinceLastOrderRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'daysPassed' => false,
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var ConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(2, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/daysPassed', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should be of type int.', $exception->getViolations()->get(1)->getMessage());
            }
        }
    }

    public function testIfRuleIsConsistent(): void
    {
        $ruleId = Uuid::randomHex();
        $this->ruleRepository->create(
            [['id' => $ruleId, 'name' => 'Demo rule', 'priority' => 1]],
            Context::createDefaultContext()
        );

        $id = Uuid::randomHex();
        $this->conditionRepository->create([
            [
                'id' => $id,
                'type' => (new DaysSinceLastOrderRule())->getName(),
                'ruleId' => $ruleId,
                'value' => [
                    'daysPassed' => 10,
                ],
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
    }

    public function testRuleDoesNotMatchWithWrongScope(): void
    {
        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['count' => 2, 'operator' => Rule::OPERATOR_LT]);

        $result = $rule->match($this->getMockForAbstractClass(RuleScope::class));

        static::assertFalse($result);
    }

    public function testRuleMatchesWithDayBefore(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn((new \DateTime())->modify('-1 day'));

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertTrue($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testRuleMatchesWithDayBeforePlusOneMinute59(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $dateTime = (new \DateTime())->setTime(11, 59);
        $orderDate = clone $dateTime;
        $orderDate->modify('-1 day +1 minute');

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn($orderDate);

        $rule = new DaysSinceLastOrderRule($dateTime);
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertTrue($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testRuleDoesNotMatchWithSameDay(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn((new \DateTime())->setTime(0, 0));

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertFalse($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testRuleDoesNotMatchOnSameDayInLastMinute(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn((new \DateTime())->setTime(23, 59));

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertFalse($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testRuleMatchesWithDayBeforePlusOneMinute(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn((new \DateTime())->modify('-1 day')->modify('+1 minute'));

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertTrue($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testRuleMatchesWithDayBeforeMinusOneMinute(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn((new \DateTime())->modify('-1 day')->modify('-1 minute'));

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertTrue($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testRuleMatchesWithSameDayButLater(): void
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createMock(CustomerEntity::class);

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);
        $customer->method('getLastOrderDate')
            ->willReturn((new \DateTime())->modify('-30 minutes'));

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertFalse($rule->match(new CheckoutRuleScope($checkoutContext)));

        $rule->assign(['daysPassed' => 0, 'operator' => Rule::OPERATOR_EQ]);

        static::assertTrue($rule->match(new CheckoutRuleScope($checkoutContext)));
    }

    public function testWithRealCustomerEntity(): void
    {
        $scope = $this->createRealTestScope();

        $rule = new DaysSinceLastOrderRule();
        $rule->assign(['daysPassed' => 1, 'operator' => Rule::OPERATOR_EQ]);

        static::assertFalse($rule->match($scope));
    }

    public function testCustomerMetaFieldSubscriber(): void
    {
        /** @var EntityRepositoryInterface $orderRepository */
        $orderRepository = $this->getContainer()->get('order.repository');
        /** @var EntityRepositoryInterface $customerRepository */
        $customerRepository = $this->getContainer()->get('customer.repository');
        $defaultContext = Context::createDefaultContext();
        $orderId = Uuid::randomHex();
        $orderData = $this->getOrderData($orderId, $defaultContext);

        $orderRepository->create($orderData, $defaultContext);

        /** @var CustomerCollection|CustomerEntity[] $result */
        $result = $customerRepository->search(
            new Criteria([$orderData[0]['orderCustomer']['customer']['id']]),
            $defaultContext
        );

        static::assertSame(1, $result->first()->getOrderCount());
        static::assertNotNull($result->first()->getLastOrderDate());
    }

    private function createRealTestScope(): CheckoutRuleScope
    {
        $checkoutContext = $this->createMock(SalesChannelContext::class);
        $customer = $this->createTestOrderAndReturnCustomer();

        $checkoutContext->method('getCustomer')
            ->willReturn($customer);

        return new CheckoutRuleScope($checkoutContext);
    }

    private function createTestOrderAndReturnCustomer(): CustomerEntity
    {
        /** @var EntityRepositoryInterface $customerRepository */
        $customerRepository = $this->getContainer()->get('customer.repository');
        $orderRepository = $this->getContainer()->get('order.repository');

        $orderId = Uuid::randomHex();
        $defaultContext = Context::createDefaultContext();
        $orderData = $this->getOrderData($orderId, $defaultContext);

        $orderRepository->create($orderData, $defaultContext);
        $criteria = new Criteria([$orderData[0]['orderCustomer']['customer']['id']]);

        $result = $customerRepository->search($criteria, $defaultContext);

        return $result->first();
    }
}

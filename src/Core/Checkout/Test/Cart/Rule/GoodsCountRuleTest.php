<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Cart\Rule;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Rule\CartRuleScope;
use Shopware\Core\Checkout\Cart\Rule\GoodsCountRule;
use Shopware\Core\Checkout\Cart\Rule\LineItemOfTypeRule;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Write\FieldException\WriteStackException;
use Shopware\Core\Framework\Rule\Container\AndRule;
use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Test\TestCaseBase\DatabaseTransactionBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Test\TestCaseHelper\ReflectionHelper;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class GoodsCountRuleTest extends TestCase
{
    use KernelTestBehaviour;
    use DatabaseTransactionBehaviour;

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

    public function testValidateWithMissingParameters(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/count', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should not be blank.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithoutOptionalOperator(): void
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
                'type' => (new GoodsCountRule())->getName(),
                'ruleId' => $ruleId,
                'value' => [
                    'count' => 3,
                ],
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
    }

    public function testValidateWithMissingCount(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'operator' => Rule::OPERATOR_EQ,
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/count', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should not be blank.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithStringCount(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'operator' => Rule::OPERATOR_EQ,
                        'count' => '3',
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/count', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(Type::INVALID_TYPE_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should be of type int.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithFloatCount(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'operator' => Rule::OPERATOR_EQ,
                        'count' => 1.1,
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/count', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(Type::INVALID_TYPE_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should be of type int.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testAvailableOperators(): void
    {
        $ruleId = Uuid::randomHex();
        $this->ruleRepository->create(
            [['id' => $ruleId, 'name' => 'Demo rule', 'priority' => 1]],
            Context::createDefaultContext()
        );

        $conditionIdEq = Uuid::randomHex();
        $conditionIdNEq = Uuid::randomHex();
        $conditionIdLTE = Uuid::randomHex();
        $conditionIdGTE = Uuid::randomHex();
        $this->conditionRepository->create(
            [
                [
                    'id' => $conditionIdEq,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => $ruleId,
                    'value' => [
                        'count' => 1,
                        'operator' => Rule::OPERATOR_EQ,
                    ],
                ],
                [
                    'id' => $conditionIdNEq,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => $ruleId,
                    'value' => [
                        'count' => 1,
                        'operator' => Rule::OPERATOR_NEQ,
                    ],
                ],
                [
                    'id' => $conditionIdLTE,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => $ruleId,
                    'value' => [
                        'count' => 1,
                        'operator' => Rule::OPERATOR_LTE,
                    ],
                ],
                [
                    'id' => $conditionIdGTE,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => $ruleId,
                    'value' => [
                        'count' => 1,
                        'operator' => Rule::OPERATOR_GTE,
                    ],
                ],
            ], $this->context
        );

        static::assertCount(
            4, $this->conditionRepository->search(
            new Criteria([$conditionIdEq, $conditionIdNEq, $conditionIdLTE, $conditionIdGTE]), $this->context
        )
        );
    }

    public function testValidateWithInvalidOperator(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new GoodsCountRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'count' => 42,
                        'operator' => 'Invalid',
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/operator', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(Choice::NO_SUCH_CHOICE_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('The value you selected is not a valid choice.', $exception->getViolations()->get(0)->getMessage());
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
                'type' => (new GoodsCountRule())->getName(),
                'ruleId' => $ruleId,
                'value' => [
                    'operator' => Rule::OPERATOR_EQ,
                    'count' => 1,
                ],
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
    }

    public function testCreateRuleWithFilter(): void
    {
        $ruleId = Uuid::randomHex();
        $this->ruleRepository->create(
            [
                [
                    'id' => $ruleId,
                    'name' => 'LineItemRule',
                    'priority' => 0,
                    'conditions' => [
                        [
                            'type' => (new GoodsCountRule())->getName(),
                            'ruleId' => $ruleId,
                            'children' => [
                                [
                                    'type' => (new LineItemOfTypeRule())->getName(),
                                    'value' => ['lineItemType' => 'test'],
                                ],
                            ],
                            'value' => [
                                'count' => 100,
                                'operator' => Rule::OPERATOR_GTE,
                            ],
                        ],
                    ],
                ],
            ], Context::createDefaultContext()
        );

        $rule = $this->ruleRepository->search(new Criteria([$ruleId]), Context::createDefaultContext())->get($ruleId);

        static::assertNotNull($rule);
        static::assertFalse($rule->isInvalid());
        static::assertInstanceOf(AndRule::class, $rule->getPayload());
        /** @var AndRule $andRule */
        $andRule = $rule->getPayload();
        static::assertInstanceOf(GoodsCountRule::class, $andRule->getRules()[0]);
        $filterProperty = ReflectionHelper::getProperty(GoodsCountRule::class, 'filter');
        $filterRule = $filterProperty->getValue($andRule->getRules()[0]);
        static::assertInstanceOf(AndRule::class, $filterRule);
        static::assertInstanceOf(LineItemOfTypeRule::class, $filterRule->getRules()[0]);
    }

    public function testFilter(): void
    {
        $rule = (new GoodsCountRule())
            ->assign([
                'count' => 2,
                'filter' => new AndRule([
                    (new LineItemOfTypeRule())
                        ->assign(['lineItemType' => 'test']),
                ]),
                'operator' => GoodsCountRule::OPERATOR_LTE,
            ]);

        $item = new LineItem('1', 'test', 1);
        $item->setGood(true);
        $item->setPrice(new CalculatedPrice(40, 40, new CalculatedTaxCollection(), new TaxRuleCollection()));

        $item2 = new LineItem('2', 'test', 1);
        $item2->setGood(true);
        $item2->setPrice(new CalculatedPrice(100, 100, new CalculatedTaxCollection(), new TaxRuleCollection()));

        $item3 = new LineItem('3', 'test-not-matching', 1);
        $item3->setGood(true);
        $item3->setPrice(new CalculatedPrice(30, 30, new CalculatedTaxCollection(), new TaxRuleCollection()));

        $cart = new Cart('test', 'test');
        $cart->addLineItems(
            new LineItemCollection([$item, $item2, $item3])
        );

        $mock = $this->createMock(SalesChannelContext::class);

        $scope = new CartRuleScope($cart, $mock);

        for ($i = 0; $i <= 100; ++$i) {
            static::assertTrue($rule->match($scope));
        }
    }
}

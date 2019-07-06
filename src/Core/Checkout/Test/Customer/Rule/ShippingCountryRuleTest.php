<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Customer\Rule;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\Rule\ShippingCountryRule;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Write\FieldException\WriteStackException;
use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Test\TestCaseBase\DatabaseTransactionBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShippingCountryRuleTest extends TestCase
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
                    'type' => (new ShippingCountryRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/countryIds', $exception->getViolations()->get(0)->getPropertyPath());
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
                'type' => (new ShippingCountryRule())->getName(),
                'ruleId' => $ruleId,
                'value' => [
                    'countryIds' => [Uuid::randomHex(), Uuid::randomHex()],
                ],
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
    }

    public function testValidateWithMissingCountryIds(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new ShippingCountryRule())->getName(),
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
                static::assertSame('/conditions/' . $conditionId . '/countryIds', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should not be blank.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithEmptyCountryIds(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new ShippingCountryRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'operator' => Rule::OPERATOR_EQ,
                        'countryIds' => [],
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/countryIds', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame(NotBlank::IS_BLANK_ERROR, $exception->getViolations()->get(0)->getCode());
                static::assertSame('This value should not be blank.', $exception->getViolations()->get(0)->getMessage());
            }
        }
    }

    public function testValidateWithInvalidCountryIdsUuid(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new ShippingCountryRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'operator' => Rule::OPERATOR_EQ,
                        'countryIds' => ['INVALID-UUID', true, 3],
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(3, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/countryIds', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame('The value "INVALID-UUID" is not a valid uuid.', $exception->getViolations()->get(0)->getMessage());
                static::assertSame('The value "1" is not a valid uuid.', $exception->getViolations()->get(1)->getMessage());
                static::assertSame('The value "3" is not a valid uuid.', $exception->getViolations()->get(2)->getMessage());
            }
        }
    }

    public function testValidateWithValidOperators(): void
    {
        $ruleId = Uuid::randomHex();
        $this->ruleRepository->create(
            [['id' => $ruleId, 'name' => 'Demo rule', 'priority' => 1]],
            Context::createDefaultContext()
        );

        $conditionIdEq = Uuid::randomHex();
        $conditionIdNEq = Uuid::randomHex();
        $this->conditionRepository->create(
            [
                [
                    'id' => $conditionIdEq,
                    'type' => (new ShippingCountryRule())->getName(),
                    'ruleId' => $ruleId,
                    'value' => [
                        'countryIds' => [Uuid::randomHex(), Uuid::randomHex()],
                        'operator' => Rule::OPERATOR_EQ,
                    ],
                ],
                [
                    'id' => $conditionIdNEq,
                    'type' => (new ShippingCountryRule())->getName(),
                    'ruleId' => $ruleId,
                    'value' => [
                        'countryIds' => [Uuid::randomHex(), Uuid::randomHex()],
                        'operator' => Rule::OPERATOR_NEQ,
                    ],
                ],
            ], $this->context
        );

        static::assertCount(
            2, $this->conditionRepository->search(
            new Criteria([$conditionIdEq, $conditionIdNEq]), $this->context
        )
        );
    }

    public function testValidateWithInvalidOperators(): void
    {
        $conditionId = Uuid::randomHex();
        foreach ([Rule::OPERATOR_LTE, Rule::OPERATOR_GTE, 'Invalid'] as $operator) {
            try {
                $this->conditionRepository->create([
                    [
                        'id' => $conditionId,
                        'type' => (new ShippingCountryRule())->getName(),
                        'ruleId' => Uuid::randomHex(),
                        'value' => [
                            'countryIds' => [Uuid::randomHex(), Uuid::randomHex()],
                            'operator' => $operator,
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
                'type' => (new ShippingCountryRule())->getName(),
                'ruleId' => $ruleId,
                'value' => [
                    'operator' => Rule::OPERATOR_EQ,
                    'countryIds' => [Uuid::randomHex(), Uuid::randomHex()],
                ],
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
    }
}

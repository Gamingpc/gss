<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\Rule;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Rule\RuleEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Write\FieldException\WriteStackException;
use Shopware\Core\Framework\Rule\Container\AndRule;
use Shopware\Core\Framework\Rule\Container\OrRule;
use Shopware\Core\Framework\Test\TestCaseBase\DatabaseTransactionBehaviour;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;

class OrRuleTest extends TestCase
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

    public function testValidateWithInvalidRulesType(): void
    {
        $conditionId = Uuid::randomHex();
        try {
            $this->conditionRepository->create([
                [
                    'id' => $conditionId,
                    'type' => (new OrRule())->getName(),
                    'ruleId' => Uuid::randomHex(),
                    'value' => [
                        'rules' => ['Rule'],
                    ],
                ],
            ], $this->context);
            static::fail('Exception was not thrown');
        } catch (WriteStackException $stackException) {
            static::assertGreaterThan(0, count($stackException->getExceptions()));
            /** @var WriteConstraintViolationException $exception */
            foreach ($stackException->getExceptions() as $exception) {
                static::assertCount(1, $exception->getViolations());
                static::assertSame('/conditions/' . $conditionId . '/rules', $exception->getViolations()->get(0)->getPropertyPath());
                static::assertSame('This value "Rule" should be of type Shopware\Core\Framework\Rule\Rule.', $exception->getViolations()->get(0)->getMessage());
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
                'type' => (new OrRule())->getName(),
                'ruleId' => $ruleId,
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
    }

    public function testIfRuleWithChildRulesIsConsistent(): void
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
                'type' => (new OrRule())->getName(),
                'ruleId' => $ruleId,
                'children' => [
                    [
                        'type' => (new OrRule())->getName(),
                        'ruleId' => $ruleId,
                    ],
                ],
            ],
        ], $this->context);

        static::assertNotNull($this->conditionRepository->search(new Criteria([$id]), $this->context)->get($id));
        /** @var RuleEntity $ruleStruct */
        $ruleStruct = $this->ruleRepository->search(new Criteria([$ruleId]), $this->context)->get($ruleId);
        static::assertEquals(new AndRule([new OrRule([new OrRule()])]), $ruleStruct->getPayload());
    }
}

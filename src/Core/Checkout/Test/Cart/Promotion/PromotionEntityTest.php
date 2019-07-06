<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Cart\Promotion;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Customer\CustomerCollection;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\Rule\CustomerNumberRule;
use Shopware\Core\Checkout\Promotion\PromotionEntity;
use Shopware\Core\Content\Rule\RuleCollection;
use Shopware\Core\Content\Rule\RuleEntity;
use Shopware\Core\Framework\Rule\Container\AndRule;
use Shopware\Core\Framework\Rule\Container\OrRule;
use Shopware\Core\Framework\Rule\Rule;

class PromotionEntityTest extends TestCase
{
    /**
     * This test verifies, that we only get an
     * empty AND rule, if no precondition has been added.
     *
     * @test
     * @group promotions
     */
    public function testPreconditionRuleEmpty()
    {
        $promotion = new PromotionEntity();

        $expected = new AndRule();

        static::assertEquals($expected, $promotion->getPreconditionRule());
    }

    /**
     * This test verifies, that we have the correct persona
     * rule inside our precondition rule structure.
     * We simulate a new rule and rule entity, and add
     * that to the promotion.
     *
     * @test
     * @group promotions
     */
    public function testPreconditionRulePersonaRules()
    {
        $fakePersonaRule = new AndRule();

        $personaRuleEntity = new RuleEntity();
        $personaRuleEntity->setId('R1');
        $personaRuleEntity->setPayload($fakePersonaRule);

        $promotion = new PromotionEntity();
        $promotion->setPersonaRules(new RuleCollection([$personaRuleEntity]));

        $expected = new AndRule(
            [
                new OrRule(
                    [
                        new OrRule(
                            [$fakePersonaRule]
                        ),
                    ]
                ),
            ]
        );

        static::assertEquals($expected, $promotion->getPreconditionRule());
    }

    /**
     * This test verifies, that we have the correct
     * persona customer rules inside our precondition filter.
     * Thus we simulate a list of assigned customers, that will then be
     * converted into CustomerNumberRules and added to our main condition.
     * Why do we need separate customer rules? Because we don't want to match
     * a list of customer numbers, but only 1 single customer number...and thus only 1 single
     * rule should match within a list of rules, based on an OR condition.
     *
     * @test
     * @group promotions
     */
    public function testPreconditionRulePersonaCustomers()
    {
        $customer1 = new CustomerEntity();
        $customer1->setId('C1');
        $customer1->setCustomerNumber('C1');

        $customer2 = new CustomerEntity();
        $customer2->setId('C2');
        $customer2->setCustomerNumber('C2');

        $promotion = new PromotionEntity();
        $promotion->setPersonaCustomers(new CustomerCollection([$customer1, $customer2]));

        $custRule1 = new CustomerNumberRule();
        $custRule1->assign(['numbers' => ['C1'], 'operator' => CustomerNumberRule::OPERATOR_EQ]);

        $custRule2 = new CustomerNumberRule();
        $custRule2->assign(['numbers' => ['C2'], 'operator' => CustomerNumberRule::OPERATOR_EQ]);

        $expected = new AndRule(
            [
                new OrRule(
                    [
                        // this is the customer rules OR condition
                        new OrRule(
                            [
                                $custRule1,
                                $custRule2,
                            ]
                        ),
                    ]
                ),
            ]
        );

        static::assertEquals($expected, $promotion->getPreconditionRule());
    }

    /**
     * This test verifies, that we have the correct cart
     * rule inside our precondition rule structure.
     * We simulate a new rule and rule entity, and add
     * that to the promotion.
     *
     * @test
     * @group promotions
     */
    public function testPreconditionRuleCartRules()
    {
        $fakeCartRule = new AndRule();

        $cartRuleEntity = new RuleEntity();
        $cartRuleEntity->setId('C1');
        $cartRuleEntity->setPayload($fakeCartRule);

        $promotion = new PromotionEntity();
        $promotion->setCartRules(new RuleCollection([$cartRuleEntity]));

        $expected = new AndRule(
            [
                new OrRule(
                    [$fakeCartRule]
                ),
            ]
        );

        static::assertEquals($expected, $promotion->getPreconditionRule());
    }

    /**
     * This test verifies, that we have the correct order
     * rule inside our precondition rule structure.
     * We simulate a new rule and rule entity, and add
     * that to the promotion.
     *
     * @test
     * @group promotions
     */
    public function testPreconditionRuleOrderRules()
    {
        $fakeOrderRule = new AndRule();

        $orderRuleEntity = new RuleEntity();
        $orderRuleEntity->setId('O1');
        $orderRuleEntity->setPayload($fakeOrderRule);

        $promotion = new PromotionEntity();
        $promotion->setOrderRules(new RuleCollection([$orderRuleEntity]));

        $expected = new AndRule(
            [
                new OrRule(
                    [$fakeOrderRule]
                ),
            ]
        );

        static::assertEquals($expected, $promotion->getPreconditionRule());
    }

    /**
     * This test verifies, that our whole structure is correct
     * if all rules and customers are filled.
     * In that case we want a wrapping AND condition with different
     * OR conditions for each part of the topics.
     * So all conditions need to match when speaking about preconditions, but only
     * 1 rule has to match within of the separate topics.
     *
     * @test
     * @group promotions
     */
    public function testPreconditionRuleWithAllConditions()
    {
        $fakePersonaRule = new AndRule();
        $personaRuleEntity = new RuleEntity();
        $personaRuleEntity->setId('R1');
        $personaRuleEntity->setPayload($fakePersonaRule);

        $customer = new CustomerEntity();
        $customer->setId('CUST1');
        $customer->setCustomerNumber('CUST1');
        $custRule = new CustomerNumberRule();
        $custRule->assign(['numbers' => ['CUST1'], 'operator' => CustomerNumberRule::OPERATOR_EQ]);

        $fakeCartRule = new AndRule();
        $cartRuleEntity = new RuleEntity();
        $cartRuleEntity->setId('C1');
        $cartRuleEntity->setPayload($fakeCartRule);

        $fakeOrderRule = new AndRule();
        $orderRuleEntity = new RuleEntity();
        $orderRuleEntity->setId('O1');
        $orderRuleEntity->setPayload($fakeOrderRule);

        $promotion = new PromotionEntity();
        $promotion->setPersonaRules(new RuleCollection([$personaRuleEntity]));
        $promotion->setPersonaCustomers(new CustomerCollection([$customer]));
        $promotion->setCartRules(new RuleCollection([$cartRuleEntity]));
        $promotion->setOrderRules(new RuleCollection([$orderRuleEntity]));

        $expected = new AndRule(
            [
                new OrRule(
                    [
                        new OrRule(
                            [$fakePersonaRule]
                        ),
                        new OrRule(
                            [
                                $custRule,
                            ]
                        ),
                    ]
                ),
                new OrRule(
                    [$fakeCartRule]
                ),
                new OrRule(
                    [$fakeOrderRule]
                ),
            ]
        );

        static::assertEquals($expected, $promotion->getPreconditionRule());
    }
}

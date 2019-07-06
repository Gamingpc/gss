<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Cart\Promotion\Cart\Builder;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Promotion\Cart\Builder\PromotionItemBuilder;

class PromotionItemBuilderPlaceholderTest extends TestCase
{
    /**
     * This test verifies that the immutable LineItem Type from
     * the constructor is correctly used in the LineItem.
     *
     * @test
     * @group promotions
     */
    public function testLineItemType()
    {
        $builder = new PromotionItemBuilder('My-TYPE');

        $item = $builder->buildPlaceholderItem('CODE-123', 1);

        static::assertEquals('My-TYPE', $item->getType());
    }

    /**
     * This test verifies that we get a correct percentage price of 0
     * for our placeholder item. This is important to avoid any wrong
     * calculations or side effects that could modify the cart amount.
     *
     * @test
     * @group promotions
     */
    public function testDefaultPriceIsEmpty()
    {
        $builder = new PromotionItemBuilder('My-TYPE');

        $item = $builder->buildPlaceholderItem('CODE-123', 1);

        $expectedPriceDefinition = new PercentagePriceDefinition(0, 1);

        static::assertEquals($expectedPriceDefinition, $item->getPriceDefinition());
    }

    /**
     * This one is the most important test.
     * It asserts that our applied code is added to the expected property of the line item.
     * When it is converted into a real promotion line item, this code is being used
     * to fetch that promotion.
     *
     * @test
     * @group promotions
     */
    public function testCodeValueInPayload()
    {
        $builder = new PromotionItemBuilder('My-TYPE');

        $item = $builder->buildPlaceholderItem('CODE-123', 1);

        static::assertEquals('CODE-123', $item->getPayload()['code']);
    }

    /**
     * This test verifies that we have our correct prefix in the key.
     * We use the code as key to avoid andy randomly generated UIDs.
     * But we still need to ensure the key is unique. To avoid any interferences
     * with other line items, we use a promotion prefix for this.
     *
     * @test
     * @group promotions
     */
    public function testKeyIsUnique()
    {
        $builder = new PromotionItemBuilder('My-TYPE');

        $item = $builder->buildPlaceholderItem('CODE-123', 1);

        static::assertEquals('promotion-CODE-123', $item->getKey());
    }
}

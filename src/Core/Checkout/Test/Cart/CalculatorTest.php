<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Test\Cart;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Checkout\Cart\Calculator;
use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\Struct\AbsolutePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\Checkout\Cart\Rule\LineItemOfTypeRule;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Rule\Container\AndRule;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class CalculatorTest extends TestCase
{
    use KernelTestBehaviour;

    /**
     * @var SalesChannelContextFactory
     */
    private $factory;

    /**
     * @var Calculator
     */
    private $calculator;

    /**
     * @var SalesChannelContext
     */
    private $context;

    protected function setUp(): void
    {
        $this->calculator = $this->getContainer()->get(Calculator::class);
        $this->factory = $this->getContainer()->get(SalesChannelContextFactory::class);
        $this->context = $this->factory->create(Uuid::randomHex(), Defaults::SALES_CHANNEL);
    }

    public function testCalculateSimplePrice(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = (new LineItem('A', 'test', 1))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2));

        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(1, $calculated);
        static::assertSame(100.0, $calculated->get('A')->getPrice()->getTotalPrice());
    }

    public function testCalculateQuantityPrice(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = (new LineItem('A', 'test', 2))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 2));

        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(1, $calculated);
        static::assertSame(200.0, $calculated->get('A')->getPrice()->getTotalPrice());
    }

    public function testPercentagePrice(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = (new LineItem('A', 'test', 2))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 2));

        $cart->add($lineItem);

        $lineItem = (new LineItem('B', 'test', 1))
            ->setPriceDefinition(new PercentagePriceDefinition(-10, 2, null));

        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(2, $calculated);
        static::assertSame(200.0, $calculated->get('A')->getPrice()->getTotalPrice());
        static::assertSame(-20.0, $calculated->get('B')->getPrice()->getTotalPrice());
    }

    public function testPercentagePriceWithWrongPriority(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = (new LineItem('A', 'test', 2))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 2));

        $cart->add($lineItem);

        $lineItem = (new LineItem('B', 'test', 1))
            ->setPriceDefinition(new PercentagePriceDefinition(-10, 2, null));

        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(2, $calculated);
        static::assertSame(200.0, $calculated->get('A')->getPrice()->getTotalPrice());
        static::assertSame(-20.0, $calculated->get('B')->getPrice()->getTotalPrice());
    }

    public function testCalculateAbsolutePrice(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = (new LineItem('A', 'test', 2))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 2));

        $cart->add($lineItem);

        $lineItem = (new LineItem('B', 'test', 1))
            ->setPriceDefinition(new AbsolutePriceDefinition(-15, 2, null));

        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(2, $calculated);
        static::assertSame(200.0, $calculated->get('A')->getPrice()->getTotalPrice());
        static::assertSame(-15.0, $calculated->get('B')->getPrice()->getTotalPrice());
    }

    public function testPercentagePriceWithFilter(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = (new LineItem('A', 'test', 1))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 1));

        $cart->add($lineItem);

        $lineItem = (new LineItem('B', 'product', 2))
            ->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 2));

        $cart->add($lineItem);

        $lineItem = (new LineItem('C', 'test', 1))
            ->setPriceDefinition(
                new PercentagePriceDefinition(
                    -10,
                    2,
                    new AndRule([
                        (new LineItemOfTypeRule())->assign(['lineItemType' => 'product']), ])
                )
            );

        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(3, $calculated);
        static::assertSame(100.0, $calculated->get('A')->getPrice()->getTotalPrice());
        static::assertSame(200.0, $calculated->get('B')->getPrice()->getTotalPrice());
        static::assertSame(-20.0, $calculated->get('C')->getPrice()->getTotalPrice());
    }

    public function testAbsolutePriceWithFilter(): void
    {
        $cart = new Cart('test', 'test');

        $lineItem = new LineItem('A', 'test', 1);
        $lineItem->setPriceDefinition(
            new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 1)
        );
        $cart->add($lineItem);

        $lineItem = new LineItem('B', 'product', 2);
        $lineItem->setPriceDefinition(
            new QuantityPriceDefinition(100, new TaxRuleCollection([]), 2, 2)
        );
        $cart->add($lineItem);

        $lineItem = new LineItem('C', 'test', 1);
        $lineItem->setPriceDefinition(
            new AbsolutePriceDefinition(
                -10,
                2,
                new AndRule([(new LineItemOfTypeRule())->assign(['lineItemType' => 'product'])])
            )
        );
        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(3, $calculated);
        static::assertSame(100.0, $calculated->get('A')->getPrice()->getTotalPrice());
        static::assertSame(200.0, $calculated->get('B')->getPrice()->getTotalPrice());
        static::assertSame(-10.0, $calculated->get('C')->getPrice()->getTotalPrice());
    }

    public function testNestedLineItemPrice(): void
    {
        $lineItem = new LineItem('A', 'test');

        $product = new LineItem('B', 'product-1');
        $product->setPriceDefinition(new QuantityPriceDefinition(100, new TaxRuleCollection(), 2));

        $discount = new LineItem('C', 'discount-1');
        $discount->setPriceDefinition(new PercentagePriceDefinition(-10, 2, null));

        $children = new LineItemCollection([$product, $discount]);

        $lineItem->setChildren($children);

        $cart = new Cart('test', 'test');
        $cart->add($lineItem);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(1, $calculated);
        static::assertSame(90.0, $calculated->get('A')->getPrice()->getTotalPrice());
        static::assertSame(100.0, $calculated->get('A')->getChildren()->get('B')->getPrice()->getTotalPrice());
        static::assertSame(-10.0, $calculated->get('A')->getChildren()->get('C')->getPrice()->getTotalPrice());
    }

    public function testDeepNesting(): void
    {
        $cart = new Cart('test', 'test');

        $nested = (new LineItem('A', 'container', 1))->assign([
            'children' => new LineItemCollection([
                (new LineItem('P1', 'product', 1))->assign([
                    'priceDefinition' => new QuantityPriceDefinition(100, new TaxRuleCollection(), 2),
                ]),
                (new LineItem('P2', 'product', 1))->assign([
                    'priceDefinition' => new QuantityPriceDefinition(100, new TaxRuleCollection(), 2),
                ]),
                (new LineItem('D', 'discount', 1))->assign([
                    'priceDefinition' => new PercentagePriceDefinition(-10, 2),
                ]),
                (new LineItem('B', 'container', 1))->assign([
                    'children' => new LineItemCollection([
                        (new LineItem('P1', 'product', 1))->assign([
                            'priceDefinition' => new QuantityPriceDefinition(100, new TaxRuleCollection(), 2),
                        ]),
                        (new LineItem('P2', 'product', 1))->assign([
                            'priceDefinition' => new QuantityPriceDefinition(100, new TaxRuleCollection(), 2),
                        ]),
                        (new LineItem('D', 'discount', 1))->assign([
                            'priceDefinition' => new PercentagePriceDefinition(-10, 2),
                        ]),
                        (new LineItem('C', 'container', 1))->assign([
                            'children' => new LineItemCollection([
                                (new LineItem('P1', 'product', 1))->assign([
                                    'priceDefinition' => new QuantityPriceDefinition(100, new TaxRuleCollection(), 2),
                                ]),
                                (new LineItem('P2', 'product', 1))->assign([
                                    'priceDefinition' => new QuantityPriceDefinition(100, new TaxRuleCollection(), 2),
                                ]),
                                (new LineItem('D', 'discount', 1))->assign([
                                    'priceDefinition' => new PercentagePriceDefinition(-10, 2),
                                ]),
                            ]),
                        ]),
                    ]),
                ]),
            ]),
        ]);

        $cart->add($nested);

        $calculated = $this->calculator->calculate($cart, $this->context, new CartBehavior());

        static::assertCount(1, $calculated);

        $root = $calculated->get('A');
        static::assertSame(100.0, $root->getChildren()->get('P1')->getPrice()->getTotalPrice());
        static::assertSame(100.0, $root->getChildren()->get('P2')->getPrice()->getTotalPrice());
        static::assertSame(-54.2, $root->getChildren()->get('D')->getPrice()->getTotalPrice());

        $root = $root->getChildren()->get('B');
        static::assertSame(100.0, $root->getChildren()->get('P1')->getPrice()->getTotalPrice());
        static::assertSame(100.0, $root->getChildren()->get('P2')->getPrice()->getTotalPrice());
        static::assertSame(-38.0, $root->getChildren()->get('D')->getPrice()->getTotalPrice());

        $root = $root->getChildren()->get('C');
        static::assertSame(100.0, $root->getChildren()->get('P1')->getPrice()->getTotalPrice());
        static::assertSame(100.0, $root->getChildren()->get('P2')->getPrice()->getTotalPrice());
        static::assertSame(-20.0, $root->getChildren()->get('D')->getPrice()->getTotalPrice());

        static::assertSame(487.8, $calculated->get('A')->getPrice()->getTotalPrice());
    }
}

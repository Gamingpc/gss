<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Cart\Rule;

use Shopware\Core\Framework\Rule\Exception\UnsupportedOperatorException;
use Shopware\Core\Framework\Rule\Rule;
use Shopware\Core\Framework\Rule\RuleScope;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class LineItemUnitPriceRule extends Rule
{
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var string
     */
    protected $operator;

    public function __construct(string $operator = self::OPERATOR_EQ, ?float $amount = null)
    {
        parent::__construct();

        $this->operator = $operator;
        $this->amount = $amount;
    }

    /**
     * @throws UnsupportedOperatorException
     */
    public function match(RuleScope $scope): bool
    {
        if (!$scope instanceof LineItemScope) {
            return false;
        }

        $unitPrice = $scope->getLineItem()->getPrice()->getUnitPrice();

        $this->amount = (float) $this->amount;

        switch ($this->operator) {
            case self::OPERATOR_GTE:
                return $unitPrice >= $this->amount;

            case self::OPERATOR_LTE:
                return $unitPrice <= $this->amount;

            case self::OPERATOR_GT:
                return $unitPrice > $this->amount;

            case self::OPERATOR_LT:
                return $unitPrice < $this->amount;

            case self::OPERATOR_EQ:
                return $unitPrice === $this->amount;

            case self::OPERATOR_NEQ:
                return $unitPrice !== $this->amount;

            default:
                throw new UnsupportedOperatorException($this->operator, __CLASS__);
        }
    }

    public function getConstraints(): array
    {
        return [
            'amount' => [new NotBlank(), new Type('numeric')],
            'operator' => [
                new Choice(
                    [
                        self::OPERATOR_NEQ,
                        self::OPERATOR_GTE,
                        self::OPERATOR_LTE,
                        self::OPERATOR_EQ,
                        self::OPERATOR_GT,
                        self::OPERATOR_LT,
                    ]
                ),
            ],
        ];
    }

    public function getName(): string
    {
        return 'cartLineItemUnitPrice';
    }
}

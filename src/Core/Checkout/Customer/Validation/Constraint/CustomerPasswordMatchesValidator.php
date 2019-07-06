<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\Validation\Constraint;

use Shopware\Core\Checkout\Customer\Exception\BadCredentialsException;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CustomerPasswordMatchesValidator extends ConstraintValidator
{
    /**
     * @var AccountService
     */
    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function validate($password, Constraint $constraint)
    {
        if (!$constraint instanceof CustomerPasswordMatches) {
            return;
        }

        /** @var SalesChannelContext $context */
        $context = $constraint->getContext();

        $customer = null;

        try {
            /** @var string $email */
            $email = $context->getCustomer()->getEmail();

            $this->accountService->getCustomerByLogin($email, (string) $password, $constraint->getContext());

            return;
        } catch (BadCredentialsException $exception) {
            $this->context->buildViolation($constraint->message)
                ->setCode(CustomerPasswordMatches::CUSTOMER_PASSWORD_NOT_CORRECT)
                ->addViolation();
        }
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\Checkout\Customer\SalesChannel;

use Composer\Semver\Constraint\ConstraintInterface;
use Shopware\Core\Checkout\Cart\Exception\CustomerNotLoggedInException;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\CustomerEvents;
use Shopware\Core\Checkout\Customer\Event\CustomerChangedPaymentMethodEvent;
use Shopware\Core\Checkout\Customer\Event\CustomerLoginEvent;
use Shopware\Core\Checkout\Customer\Event\CustomerLogoutEvent;
use Shopware\Core\Checkout\Customer\Exception\AddressNotFoundException;
use Shopware\Core\Checkout\Customer\Exception\BadCredentialsException;
use Shopware\Core\Checkout\Customer\Exception\CustomerNotFoundException;
use Shopware\Core\Checkout\Customer\Password\LegacyPasswordVerifier;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerEmailUnique;
use Shopware\Core\Checkout\Customer\Validation\Constraint\CustomerPasswordMatches;
use Shopware\Core\Checkout\Customer\Validation\CustomerProfileValidationService;
use Shopware\Core\Checkout\Payment\Exception\UnknownPaymentMethodException;
use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Event\DataMappingEvent;
use Shopware\Core\Framework\Uuid\Exception\InvalidUuidException;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextPersister;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextServiceInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\Salutation\SalutationCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class AccountService
{
    /**
     * @var EntityRepositoryInterface
     */
    private $customerAddressRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $salutationRepository;

    /**
     * @var SalesChannelContextPersister
     */
    private $contextPersister;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var DataValidator
     */
    private $validator;

    /**
     * @var LegacyPasswordVerifier
     */
    private $legacyPasswordVerifier;

    /**
     * @var CustomerProfileValidationService
     */
    private $customerProfileValidationService;

    /**
     * @var EntityRepositoryInterface
     */
    private $paymentMethodRepository;

    /**
     * @var SalesChannelContextServiceInterface
     */
    private $contextService;

    public function __construct(
        EntityRepositoryInterface $customerAddressRepository,
        EntityRepositoryInterface $customerRepository,
        EntityRepositoryInterface $salutationRepository,
        SalesChannelContextPersister $contextPersister,
        EventDispatcherInterface $eventDispatcher,
        DataValidator $validator,
        CustomerProfileValidationService $customerProfileValidationService,
        LegacyPasswordVerifier $legacyPasswordVerifier,
        EntityRepositoryInterface $paymentMethodRepository,
        SalesChannelContextServiceInterface $contextService
    ) {
        $this->customerAddressRepository = $customerAddressRepository;
        $this->customerRepository = $customerRepository;
        $this->salutationRepository = $salutationRepository;
        $this->contextPersister = $contextPersister;
        $this->eventDispatcher = $eventDispatcher;
        $this->validator = $validator;
        $this->legacyPasswordVerifier = $legacyPasswordVerifier;
        $this->customerProfileValidationService = $customerProfileValidationService;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->contextService = $contextService;
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function getCustomerByEmail(string $email, SalesChannelContext $context, bool $includeGuest = false): CustomerEntity
    {
        $customers = $this->getCustomersByEmail($email, $context, $includeGuest);

        $customerCount = $customers->count();
        if ($customerCount === 1) {
            return $customers->first();
        }

        if ($includeGuest && $customerCount) {
            $customers->sort(static function (CustomerEntity $a, CustomerEntity $b) {
                return $a->getCreatedAt() <=> $b->getCreatedAt();
            });

            return $customers->last();
        }

        throw new CustomerNotFoundException($email);
    }

    public function getCustomersByEmail(string $email, SalesChannelContext $context, bool $includeGuests = true): EntitySearchResult
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customer.email', $email));
        if (!$includeGuests) {
            $criteria->addFilter(new EqualsFilter('customer.guest', 0));
        }
        // TODO NEXT-389 we have to check an option like "bind customer to salesChannel"
        // todo in this case we have to filter "customer.salesChannelId is null or salesChannelId = :current"

        return $this->customerRepository->search($criteria, $context->getContext());
    }

    /**
     * @throws CustomerNotLoggedInException
     */
    public function getCustomerByContext(SalesChannelContext $context): CustomerEntity
    {
        $this->validateCustomer($context);

        return $context->getCustomer();
    }

    public function saveProfile(DataBag $data, SalesChannelContext $context): void
    {
        /** @var DataValidationDefinition $validation */
        $validation = $this->customerProfileValidationService->buildUpdateValidation($context->getContext());

        $this->dispatchValidationEvent($validation, $context->getContext());

        $this->validator->validate($data->all(), $validation);

        $customer = $data->only('firstName', 'lastName', 'salutationId', 'title');

        if ($birthday = $this->getBirthday($data)) {
            $customer['birthday'] = $birthday;
        }

        $mappingEvent = new DataMappingEvent(CustomerEvents::MAPPING_CUSTOMER_PROFILE_SAVE, $data, $customer, $context->getContext());
        $this->eventDispatcher->dispatch($mappingEvent->getName(), $mappingEvent);

        $customer = $mappingEvent->getOutput();
        $customer['id'] = $context->getCustomer()->getId();

        $this->customerRepository->update([$customer], $context->getContext());

        $this->refreshContext($context);
    }

    public function getSalutationList(SalesChannelContext $context): SalutationCollection
    {
        $criteria = new Criteria([]);
        $criteria->addSorting(new FieldSorting('salutationKey', 'DESC'));

        /** @var SalutationCollection $salutations */
        $salutations = $this->salutationRepository->search($criteria, $context->getContext())
            ->getEntities();

        return $salutations;
    }

    public function savePassword(DataBag $data, SalesChannelContext $context): void
    {
        $this->validateCustomer($context);

        $this->validatePasswordFields($data, $context);

        $customerData = [
            'id' => $context->getCustomer()->getId(),
            'password' => $data->get('newPassword'),
        ];

        $this->customerRepository->update([$customerData], $context->getContext());
    }

    public function saveEmail(DataBag $data, SalesChannelContext $context): void
    {
        $this->validateCustomer($context);

        $this->validateEmail($data, $context);

        $customerData = [
            'id' => $context->getCustomer()->getId(),
            'email' => $data->get('email'),
        ];

        $this->customerRepository->update([$customerData], $context->getContext());

        $this->refreshContext($context);
    }

    /**
     * @throws CustomerNotLoggedInException
     * @throws InvalidUuidException
     * @throws AddressNotFoundException
     */
    public function setDefaultBillingAddress(string $addressId, SalesChannelContext $context): void
    {
        $this->validateCustomer($context);
        $this->validateAddressId($addressId, $context);

        $data = [
            'id' => $context->getCustomer()->getId(),
            'defaultBillingAddressId' => $addressId,
        ];
        $this->customerRepository->update([$data], $context->getContext());

        $this->refreshContext($context);
    }

    /**
     * @throws CustomerNotLoggedInException
     * @throws InvalidUuidException
     * @throws AddressNotFoundException
     */
    public function setDefaultShippingAddress(string $addressId, SalesChannelContext $context): void
    {
        $this->validateCustomer($context);
        $this->validateAddressId($addressId, $context);

        $data = [
            'id' => $context->getCustomer()->getId(),
            'defaultShippingAddressId' => $addressId,
        ];
        $this->customerRepository->update([$data], $context->getContext());

        $this->refreshContext($context);
    }

    /**
     * @throws BadCredentialsException
     * @throws UnauthorizedHttpException
     */
    public function login(string $email, SalesChannelContext $context, bool $includeGuest = false): string
    {
        if (empty($email)) {
            throw new BadCredentialsException();
        }

        try {
            $customer = $this->getCustomerByEmail($email, $context, $includeGuest);
        } catch (CustomerNotFoundException | BadCredentialsException $exception) {
            throw new UnauthorizedHttpException('json', $exception->getMessage());
        }

        $newToken = $this->contextPersister->replace($context->getToken());
        $this->contextPersister->save(
            $newToken,
            [
                'customerId' => $customer->getId(),
                'billingAddressId' => null,
                'shippingAddressId' => null,
            ]
        );

        $event = new CustomerLoginEvent($context->getContext(), $customer, $newToken, $context->getSalesChannel()->getId());
        $this->eventDispatcher->dispatch($event->getName(), $event);

        $this->refreshContext($context);

        return $newToken;
    }

    /**
     * @throws BadCredentialsException
     * @throws UnauthorizedHttpException
     */
    public function loginWithPassword(DataBag $data, SalesChannelContext $context): string
    {
        if (empty($data->get('username')) || empty($data->get('password'))) {
            throw new BadCredentialsException();
        }

        try {
            $customer = $this->getCustomerByLogin(
                $data->get('username'),
                $data->get('password'),
                $context
            );
        } catch (CustomerNotFoundException | BadCredentialsException $exception) {
            throw new UnauthorizedHttpException('json', $exception->getMessage());
        }

        $newToken = $this->contextPersister->replace($context->getToken());
        $this->contextPersister->save(
            $newToken,
            [
                'customerId' => $customer->getId(),
                'billingAddressId' => null,
                'shippingAddressId' => null,
            ]
        );

        $this->customerRepository->update([
            [
                'id' => $customer->getId(),
                'lastLogin' => new \DateTimeImmutable(),
            ],
        ], $context->getContext());

        $event = new CustomerLoginEvent($context->getContext(), $customer, $newToken, $context->getSalesChannel()->getId());
        $this->eventDispatcher->dispatch($event->getName(), $event);

        return $newToken;
    }

    public function logout(SalesChannelContext $context): void
    {
        $this->contextPersister->save(
            $context->getToken(),
            [
                'customerId' => null,
                'billingAddressId' => null,
                'shippingAddressId' => null,
            ]
        );

        $event = new CustomerLogoutEvent($context->getContext(), $context->getCustomer(), $context->getSalesChannel()->getId());
        $this->eventDispatcher->dispatch($event->getName(), $event);

        $this->refreshContext($context);
    }

    public function setNewsletterFlag(CustomerEntity $customer, bool $newsletter, SalesChannelContext $context): void
    {
        $customer->setNewsletter($newsletter);

        $this->customerRepository->update([[
            'id' => $customer->getId(),
            'newsletter' => $newsletter,
        ]], $context->getContext());
    }

    /**
     * @throws InvalidUuidException
     * @throws UnknownPaymentMethodException
     */
    public function changeDefaultPaymentMethod(string $paymentMethodId, RequestDataBag $requestDataBag, CustomerEntity $customer, SalesChannelContext $context): void
    {
        $this->validatePaymentMethodId($paymentMethodId, $context->getContext());

        $this->customerRepository->update([
            [
                'id' => $customer->getId(),
                'defaultPaymentMethodId' => $paymentMethodId,
            ],
        ], $context->getContext());

        $event = new CustomerChangedPaymentMethodEvent($context->getContext(), $customer, $requestDataBag, $context->getSalesChannel()->getId());
        $this->eventDispatcher->dispatch($event->getName(), $event);

        $this->refreshContext($context);
    }

    /**
     * @throws CustomerNotFoundException
     * @throws BadCredentialsException
     */
    public function getCustomerByLogin(string $email, string $password, SalesChannelContext $context): CustomerEntity
    {
        $customer = $this->getCustomerByEmail($email, $context);

        if ($customer->hasLegacyPassword()) {
            if (!$this->legacyPasswordVerifier->verify($password, $customer)) {
                throw new BadCredentialsException();
            }

            $this->updatePasswordHash($password, $customer, $context->getContext());

            return $customer;
        }

        if (!password_verify($password, $customer->getPassword())) {
            throw new BadCredentialsException();
        }

        return $customer;
    }

    private function validateEmail(DataBag $data, SalesChannelContext $context): void
    {
        $validation = new DataValidationDefinition('customer.email.update');

        $validation
            ->add(
                'email',
                new Email(),
                new EqualTo(['propertyPath' => 'emailConfirmation']),
                new CustomerEmailUnique(['context' => $context->getContext()])
            )
            ->add('password', new CustomerPasswordMatches(['context' => $context]));

        $this->dispatchValidationEvent($validation, $context->getContext());

        $this->validator->validate($data->all(), $validation);

        $this->tryValidateEqualtoConstraint($data->all(), 'email', $validation);
    }

    /**
     * @throws CustomerNotLoggedInException
     */
    private function validateCustomer(SalesChannelContext $context): void
    {
        if ($context->getCustomer()) {
            return;
        }

        throw new CustomerNotLoggedInException();
    }

    /**
     * @throws AddressNotFoundException
     * @throws InvalidUuidException
     */
    private function validateAddressId(string $addressId, SalesChannelContext $context): void
    {
        if (!Uuid::isValid($addressId)) {
            throw new InvalidUuidException($addressId);
        }

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('id', $addressId));
        $criteria->addFilter(new EqualsFilter('customerId', $context->getCustomer()->getId()));

        $searchResult = $this->customerAddressRepository->searchIds($criteria, $context->getContext());
        if ($searchResult->getTotal()) {
            return;
        }

        throw new AddressNotFoundException($addressId);
    }

    private function getBirthday(DataBag $data): ?\DateTimeInterface
    {
        $birthdayDay = $data->get('birthdayDay');
        $birthdayMonth = $data->get('birthdayMonth');
        $birthdayYear = $data->get('birthdayYear');

        if (!$birthdayDay || !$birthdayMonth || !$birthdayYear) {
            return null;
        }

        return new \DateTime(sprintf(
            '%s-%s-%s',
            $birthdayYear,
            $birthdayMonth,
            $birthdayDay
        ));
    }

    private function dispatchValidationEvent(DataValidationDefinition $definition, Context $context): void
    {
        $validationEvent = new BuildValidationEvent($definition, $context);
        $this->eventDispatcher->dispatch($validationEvent->getName(), $validationEvent);
    }

    private function updatePasswordHash(string $password, CustomerEntity $customer, Context $context): void
    {
        $this->customerRepository->update([
            [
                'id' => $customer->getId(),
                'password' => $password,
                'legacyPassword' => null,
                'legacyEncoder' => null,
            ],
        ], $context);
    }

    /**
     * @throws ConstraintViolationException
     */
    private function tryValidateEqualtoConstraint(array $data, string $field, DataValidationDefinition $validation): void
    {
        /** @var array $validations */
        $validations = $validation->getProperties();

        if (!array_key_exists($field, $validations)) {
            return;
        }

        /** @var array $fieldValidations */
        $fieldValidations = $validations[$field];

        /** @var EqualTo|null $equalityValidation */
        $equalityValidation = null;

        /** @var ConstraintInterface $emailValidation */
        foreach ($fieldValidations as $emailValidation) {
            if ($emailValidation instanceof EqualTo) {
                $equalityValidation = $emailValidation;
                break;
            }
        }

        if (!$equalityValidation instanceof EqualTo) {
            return;
        }

        $compareValue = $data[$equalityValidation->propertyPath] ?? null;
        if ($data[$field] === $compareValue) {
            return;
        }

        $message = str_replace('{{ compared_value }}', $compareValue, $equalityValidation->message);

        $violations = new ConstraintViolationList();
        $violations->add(new ConstraintViolation($message, $equalityValidation->message, [], '', $field, $data[$field]));

        throw new ConstraintViolationException($violations, $data);
    }

    /**
     * @throws ConstraintViolationException
     */
    private function validatePasswordFields(DataBag $data, SalesChannelContext $context): void
    {
        /** @var DataValidationDefinition $definition */
        $definition = new DataValidationDefinition('customer.password.update');

        $definition
            ->add('newPassword', new NotBlank(), new EqualTo(['propertyPath' => 'newPasswordConfirm']))
            ->add('password', new CustomerPasswordMatches(['context' => $context]));

        $this->dispatchValidationEvent($definition, $context->getContext());

        $this->validator->validate($data->all(), $definition);

        $this->tryValidateEqualtoConstraint($data->all(), 'newPassword', $definition);
    }

    /**
     * @throws InvalidUuidException
     * @throws UnknownPaymentMethodException
     */
    private function validatePaymentMethodId(string $paymentMethodId, Context $context): void
    {
        if (!Uuid::isValid($paymentMethodId)) {
            throw new InvalidUuidException($paymentMethodId);
        }

        /** @var PaymentMethodEntity|null $paymentMethod */
        $paymentMethod = $this->paymentMethodRepository->search(new Criteria([$paymentMethodId]), $context)->get($paymentMethodId);

        if (!$paymentMethod) {
            throw new UnknownPaymentMethodException($paymentMethodId);
        }
    }

    private function refreshContext(SalesChannelContext $context): void
    {
        $this->contextService->refresh(
            $context->getSalesChannel()->getId(),
            $context->getToken(),
            $context->getContext()->getLanguageId()
        );
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\System\SalesChannel;

use Shopware\Core\Checkout\Customer\Aggregate\CustomerGroup\CustomerGroupEntity;
use Shopware\Core\Checkout\Customer\CustomerCollection;
use Shopware\Core\Checkout\Document\Aggregate\DocumentBaseConfig\DocumentBaseConfigDefinition;
use Shopware\Core\Checkout\Order\OrderCollection;
use Shopware\Core\Checkout\Payment\PaymentMethodCollection;
use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Checkout\Promotion\Aggregate\PromotionSalesChannel\PromotionSalesChannelCollection;
use Shopware\Core\Checkout\Shipping\ShippingMethodCollection;
use Shopware\Core\Checkout\Shipping\ShippingMethodEntity;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\MailTemplate\Aggregate\MailHeaderFooter\MailHeaderFooterEntity;
use Shopware\Core\Content\MailTemplate\MailTemplateCollection;
use Shopware\Core\Content\NewsletterReceiver\NewsletterReceiverCollection;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use Shopware\Core\Framework\Language\LanguageCollection;
use Shopware\Core\Framework\Language\LanguageEntity;
use Shopware\Core\System\Country\CountryCollection;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Currency\CurrencyCollection;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\NumberRange\Aggregate\NumberRangeSalesChannel\NumberRangeSalesChannelCollection;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainCollection;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelTranslation\SalesChannelTranslationCollection;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelType\SalesChannelTypeEntity;
use Shopware\Core\System\SystemConfig\SystemConfigCollection;

class SalesChannelEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $typeId;

    /**
     * @var string
     */
    protected $languageId;

    /**
     * @var string
     */
    protected $currencyId;

    /**
     * @var string
     */
    protected $paymentMethodId;

    /**
     * @var string
     */
    protected $shippingMethodId;

    /**
     * @var string
     */
    protected $countryId;

    /**
     * @var string
     */
    protected $navigationCategoryId;

    /**
     * @var string|null
     */
    protected $footerCategoryId;

    /**
     * @var string|null
     */
    protected $serviceCategoryId;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $shortName;

    /**
     * @var string
     */
    protected $accessKey;

    /**
     * @var CurrencyCollection|null
     */
    protected $currencies;

    /**
     * @var LanguageCollection|null
     */
    protected $languages;

    /**
     * @var array|null
     */
    protected $configuration;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var string
     */
    protected $taxCalculationType;

    /**
     * @var SalesChannelTypeEntity
     */
    protected $type;

    /**
     * @var CurrencyEntity
     */
    protected $currency;

    /**
     * @var LanguageEntity
     */
    protected $language;

    /**
     * @var PaymentMethodEntity|null
     */
    protected $paymentMethod;

    /**
     * @var ShippingMethodEntity|null
     */
    protected $shippingMethod;

    /**
     * @var CountryEntity|null
     */
    protected $country;

    /**
     * @var OrderCollection|null
     */
    protected $orders;

    /**
     * @var CustomerCollection|null
     */
    protected $customers;

    /**
     * @var CountryCollection|null
     */
    protected $countries;

    /**
     * @var PaymentMethodCollection|null
     */
    protected $paymentMethods;

    /**
     * @var ShippingMethodCollection|null
     */
    protected $shippingMethods;

    /**
     * @var SalesChannelTranslationCollection|null
     */
    protected $translations;

    /**
     * @var SalesChannelDomainCollection|null
     */
    protected $domains;

    /**
     * @var SystemConfigCollection|null
     */
    protected $systemConfigs;

    /**
     * @var array|null
     */
    protected $customFields;

    /**
     * @var CategoryEntity|null
     */
    protected $navigationCategory;

    /**
     * @var CategoryEntity|null
     */
    protected $footerCategory;

    /**
     * @var CategoryEntity|null
     */
    protected $serviceCategory;

    /**
     * @var ProductVisibilityCollection|null
     */
    protected $productVisibilities;

    /**
     * @var MailTemplateCollection|null
     */
    protected $mailTemplates;

    /**
     * @var string|null
     */
    protected $mailHeaderFooterId;

    /**
     * @var NumberRangeSalesChannelCollection|null
     */
    protected $numberRangeSalesChannels;

    /**
     * @var MailHeaderFooterEntity|null
     */
    protected $mailHeaderFooter;

    /**
     * @var string
     */
    protected $customerGroupId;

    /**
     * @var CustomerGroupEntity
     */
    protected $customerGroup;

    /**
     * @var NewsletterReceiverCollection|null
     */
    protected $newsletterReceivers;

    /**
     * @var PromotionSalesChannelCollection|null
     */
    protected $promotionSalesChannels;

    /**
     * @var DocumentBaseConfigDefinition|null
     */
    protected $documentBaseConfigSalesChannels;

    public function getMailHeaderFooter(): ?MailHeaderFooterEntity
    {
        return $this->mailHeaderFooter;
    }

    public function setMailHeaderFooter(?MailHeaderFooterEntity $mailHeaderFooter): void
    {
        $this->mailHeaderFooter = $mailHeaderFooter;
    }

    public function getMailHeaderFooterId(): ?string
    {
        return $this->mailHeaderFooterId;
    }

    public function setMailHeaderFooterId(string $mailHeaderFooterId): void
    {
        $this->mailHeaderFooterId = $mailHeaderFooterId;
    }

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function setLanguageId(string $languageId): void
    {
        $this->languageId = $languageId;
    }

    public function getCurrencyId(): string
    {
        return $this->currencyId;
    }

    public function setCurrencyId(string $currencyId): void
    {
        $this->currencyId = $currencyId;
    }

    public function getPaymentMethodId(): string
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(string $paymentMethodId): void
    {
        $this->paymentMethodId = $paymentMethodId;
    }

    public function getShippingMethodId(): string
    {
        return $this->shippingMethodId;
    }

    public function setShippingMethodId(string $shippingMethodId): void
    {
        $this->shippingMethodId = $shippingMethodId;
    }

    public function getCountryId(): string
    {
        return $this->countryId;
    }

    public function setCountryId(string $countryId): void
    {
        $this->countryId = $countryId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(?string $shortName): void
    {
        $this->shortName = $shortName;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function setAccessKey(string $accessKey): void
    {
        $this->accessKey = $accessKey;
    }

    public function getCurrencies(): ?CurrencyCollection
    {
        return $this->currencies;
    }

    public function setCurrencies(CurrencyCollection $currencies): void
    {
        $this->currencies = $currencies;
    }

    public function getLanguages(): ?LanguageCollection
    {
        return $this->languages;
    }

    public function setLanguages(LanguageCollection $languages): void
    {
        $this->languages = $languages;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getTaxCalculationType(): string
    {
        return $this->taxCalculationType;
    }

    public function setTaxCalculationType(string $taxCalculationType): void
    {
        $this->taxCalculationType = $taxCalculationType;
    }

    public function getCurrency(): CurrencyEntity
    {
        return $this->currency;
    }

    public function setCurrency(CurrencyEntity $currency): void
    {
        $this->currency = $currency;
    }

    public function getLanguage(): LanguageEntity
    {
        return $this->language;
    }

    public function setLanguage(LanguageEntity $language): void
    {
        $this->language = $language;
    }

    public function getPaymentMethod(): ?PaymentMethodEntity
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(PaymentMethodEntity $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getShippingMethod(): ?ShippingMethodEntity
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod(ShippingMethodEntity $shippingMethod): void
    {
        $this->shippingMethod = $shippingMethod;
    }

    public function getCountry(): ?CountryEntity
    {
        return $this->country;
    }

    public function setCountry(CountryEntity $country): void
    {
        $this->country = $country;
    }

    public function getOrders(): ?OrderCollection
    {
        return $this->orders;
    }

    public function setOrders(OrderCollection $orders): void
    {
        $this->orders = $orders;
    }

    public function getCustomers(): ?CustomerCollection
    {
        return $this->customers;
    }

    public function setCustomers(CustomerCollection $customers): void
    {
        $this->customers = $customers;
    }

    public function getTypeId(): string
    {
        return $this->typeId;
    }

    public function setTypeId(string $typeId): void
    {
        $this->typeId = $typeId;
    }

    public function getType(): SalesChannelTypeEntity
    {
        return $this->type;
    }

    public function setType(SalesChannelTypeEntity $type): void
    {
        $this->type = $type;
    }

    public function getCountries(): ?CountryCollection
    {
        return $this->countries;
    }

    public function setCountries(CountryCollection $countries): void
    {
        $this->countries = $countries;
    }

    public function getTranslations(): ?SalesChannelTranslationCollection
    {
        return $this->translations;
    }

    public function setTranslations(SalesChannelTranslationCollection $translations): void
    {
        $this->translations = $translations;
    }

    public function getPaymentMethods(): ?PaymentMethodCollection
    {
        return $this->paymentMethods;
    }

    public function setPaymentMethods(PaymentMethodCollection $paymentMethods): void
    {
        $this->paymentMethods = $paymentMethods;
    }

    public function getShippingMethods(): ?ShippingMethodCollection
    {
        return $this->shippingMethods;
    }

    public function setShippingMethods(ShippingMethodCollection $shippingMethods): void
    {
        $this->shippingMethods = $shippingMethods;
    }

    public function getDomains(): ?SalesChannelDomainCollection
    {
        return $this->domains;
    }

    public function setDomains(?SalesChannelDomainCollection $domains): void
    {
        $this->domains = $domains;
    }

    public function getSystemConfigs(): ?SystemConfigCollection
    {
        return $this->systemConfigs;
    }

    public function setSystemConfigs(SystemConfigCollection $systemConfigs): void
    {
        $this->systemConfigs = $systemConfigs;
    }

    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }

    public function setCustomFields(?array $customFields): void
    {
        $this->customFields = $customFields;
    }

    public function getNavigationCategoryId(): string
    {
        return $this->navigationCategoryId;
    }

    public function setNavigationCategoryId(string $navigationCategoryId): void
    {
        $this->navigationCategoryId = $navigationCategoryId;
    }

    public function getNavigationCategory(): ?CategoryEntity
    {
        return $this->navigationCategory;
    }

    public function setNavigationCategory(CategoryEntity $navigationCategory): void
    {
        $this->navigationCategory = $navigationCategory;
    }

    public function getProductVisibilities(): ?ProductVisibilityCollection
    {
        return $this->productVisibilities;
    }

    public function setProductVisibilities(ProductVisibilityCollection $productVisibilities): void
    {
        $this->productVisibilities = $productVisibilities;
    }

    public function getMailTemplates(): ?MailTemplateCollection
    {
        return $this->mailTemplates;
    }

    public function setMailTemplates(MailTemplateCollection $mailTemplates): void
    {
        $this->mailTemplates = $mailTemplates;
    }

    public function getCustomerGroupId(): string
    {
        return $this->customerGroupId;
    }

    public function setCustomerGroupId(string $customerGroupId): void
    {
        $this->customerGroupId = $customerGroupId;
    }

    public function getCustomerGroup(): CustomerGroupEntity
    {
        return $this->customerGroup;
    }

    public function setCustomerGroup(CustomerGroupEntity $customerGroup): void
    {
        $this->customerGroup = $customerGroup;
    }

    public function getNewsletterReceivers(): ?NewsletterReceiverCollection
    {
        return $this->newsletterReceivers;
    }

    public function setNewsletterReceivers(NewsletterReceiverCollection $newsletterReceivers): void
    {
        $this->newsletterReceivers = $newsletterReceivers;
    }

    public function getPromotionSalesChannels(): ?PromotionSalesChannelCollection
    {
        return $this->promotionSalesChannels;
    }

    public function setPromotionSalesChannels(PromotionSalesChannelCollection $promotionSalesChannels): void
    {
        $this->promotionSalesChannels = $promotionSalesChannels;
    }

    public function getNumberRangeSalesChannels(): ?NumberRangeSalesChannelCollection
    {
        return $this->numberRangeSalesChannels;
    }

    public function setNumberRangeSalesChannels(?NumberRangeSalesChannelCollection $numberRangeSalesChannels): void
    {
        $this->numberRangeSalesChannels = $numberRangeSalesChannels;
    }

    public function getFooterCategoryId(): ?string
    {
        return $this->footerCategoryId;
    }

    public function setFooterCategoryId(string $footerCategoryId): void
    {
        $this->footerCategoryId = $footerCategoryId;
    }

    public function getServiceCategoryId(): ?string
    {
        return $this->serviceCategoryId;
    }

    public function setServiceCategoryId(string $serviceCategoryId): void
    {
        $this->serviceCategoryId = $serviceCategoryId;
    }

    public function getFooterCategory(): ?CategoryEntity
    {
        return $this->footerCategory;
    }

    public function setFooterCategory(CategoryEntity $footerCategory): void
    {
        $this->footerCategory = $footerCategory;
    }

    public function getServiceCategory(): ?CategoryEntity
    {
        return $this->serviceCategory;
    }

    public function setServiceCategory(CategoryEntity $serviceCategory): void
    {
        $this->serviceCategory = $serviceCategory;
    }

    public function getDocumentBaseConfigSalesChannels(): ?DocumentBaseConfigDefinition
    {
        return $this->documentBaseConfigSalesChannels;
    }

    public function setDocumentBaseConfigSalesChannels(DocumentBaseConfigDefinition $documentBaseConfigSalesChannels): void
    {
        $this->documentBaseConfigSalesChannels = $documentBaseConfigSalesChannels;
    }
}

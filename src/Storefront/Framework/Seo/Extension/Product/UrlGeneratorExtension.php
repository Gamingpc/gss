<?php declare(strict_types=1);

namespace Shopware\Storefront\Framework\Seo\Extension\Product;

use Shopware\Core\Framework\DataAbstractionLayer\EntityExtensionInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Deferred;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Storefront\Framework\Seo\SeoUrl\SeoUrlDefinition;
use Shopware\Storefront\Framework\Seo\SeoUrl\SeoUrlEntity;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UrlGeneratorExtension implements EntityExtensionInterface, EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new StringField('url', 'url'))->addFlags(new Deferred(), new WriteProtected())
        );
    }

    public function getDefinitionClass(): string
    {
        return SeoUrlDefinition::class;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'seo_url.loaded' => 'seoUrlBasicLoaded',
        ];
    }

    public function seoUrlBasicLoaded(EntityLoadedEvent $event): void
    {
        $request = $this->requestStack->getMasterRequest();

        if (!$request) {
            return;
        }

        /** @var SeoUrlEntity $seoUrl */
        foreach ($event->getEntities() as $seoUrl) {
            $seoUrl->setUrl($request->getSchemeAndHttpHost() . $request->getBaseUrl() . '/' . $seoUrl->getSeoPathInfo());
        }
    }
}

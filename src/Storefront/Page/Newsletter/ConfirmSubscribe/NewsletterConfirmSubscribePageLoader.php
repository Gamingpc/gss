<?php declare(strict_types=1);

namespace Shopware\Storefront\Page\Newsletter\ConfirmSubscribe;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
use Shopware\Storefront\Framework\Page\PageWithHeaderLoader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class NewsletterConfirmSubscribePageLoader implements PageLoaderInterface
{
    /**
     * @var PageWithHeaderLoader|PageLoaderInterface
     */
    private $genericLoader;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        PageLoaderInterface $genericLoader,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->genericLoader = $genericLoader;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function load(Request $request, SalesChannelContext $context)
    {
        $page = $this->genericLoader->load($request, $context);
        $page = NewsletterConfirmSubscribePage::createFrom($page);

        $this->eventDispatcher->dispatch(
            NewsletterConfirmSubscribePageLoadedEvent::NAME,
            new NewsletterConfirmSubscribePageLoadedEvent($page, $context, $request)
        );

        return $page;
    }
}

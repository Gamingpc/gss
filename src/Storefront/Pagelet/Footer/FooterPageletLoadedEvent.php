<?php declare(strict_types=1);

namespace Shopware\Storefront\Pagelet\Footer;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class FooterPageletLoadedEvent extends NestedEvent
{
    public const NAME = 'footer.pagelet.loaded.event';

    /**
     * @var FooterPagelet
     */
    protected $pagelet;

    /**
     * @var SalesChannelContext
     */
    protected $context;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(FooterPagelet $page, SalesChannelContext $context, Request $request)
    {
        $this->pagelet = $page;
        $this->context = $context;
        $this->request = $request;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->context->getContext();
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function getPagelet(): FooterPagelet
    {
        return $this->pagelet;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}

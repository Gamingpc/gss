<?php declare(strict_types=1);

namespace Shopware\Storefront\Event;

use Shopware\Storefront\Page\Search\SearchPageLoadedEvent;
use Shopware\Storefront\Pagelet\Suggest\SuggestPageletLoadedEvent;

class SearchEvents
{
    /**
     * @Event("Shopware\Storefront\Page\Search\SearchPageLoadedEvent")
     */
    public const SEARCH_PAGE_LOADED_EVENT = SearchPageLoadedEvent::NAME;

    /**
     * @Event("Shopware\Storefront\Pagelet\Suggest\SuggestPageletLoadedEvent")
     */
    public const SUGGEST_PAGELET_LOADED_EVENT = SuggestPageletLoadedEvent::NAME;
}

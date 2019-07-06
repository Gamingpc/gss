<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Api\Response;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Context\ContextSource;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ResponseFactoryInterface
{
    public function supports(string $contentType, ContextSource $origin): bool;

    public function createDetailResponse(Entity $entity, EntityDefinition $definition, Request $request, Context $context, bool $setLocationHeader = false): Response;

    public function createListingResponse(EntitySearchResult $searchResult, EntityDefinition $definition, Request $request, Context $context): Response;

    public function createRedirectResponse(EntityDefinition $definition, string $id, Request $request, Context $context): Response;
}

<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Snippet\Files\de_DE;

use Shopware\Core\Framework\Snippet\Files\SnippetFileInterface;

class SnippetFile_de_DE implements SnippetFileInterface
{
    public function getName(): string
    {
        return 'messages.de-DE';
    }

    public function getPath(): string
    {
        return __DIR__ . '/messages.de-DE.json';
    }

    public function getIso(): string
    {
        return 'de-DE';
    }

    public function getAuthor(): string
    {
        return 'Shopware';
    }

    public function isBase(): bool
    {
        return true;
    }
}

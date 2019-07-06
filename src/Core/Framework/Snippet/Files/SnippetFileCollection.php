<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Snippet\Files;

use Shopware\Core\Framework\Snippet\Exception\InvalidSnippetFileException;
use Shopware\Core\Framework\Struct\Collection;

/**
 * @method void                      set(string $key, SnippetFileInterface $entity)
 * @method SnippetFileInterface[]    getIterator()
 * @method SnippetFileInterface[]    getElements()
 * @method SnippetFileInterface|null get(string $key)
 * @method SnippetFileInterface|null first()
 * @method SnippetFileInterface|null last()
 */
class SnippetFileCollection extends Collection
{
    /**
     * @param SnippetFileInterface $snippetFile
     */
    public function add($snippetFile): void
    {
        $this->set($snippetFile->getName(), $snippetFile);
    }

    public function getFilesArray(bool $isBase = true): array
    {
        return array_filter($this->toArray(), function ($file) use ($isBase) {
            return $file['isBase'] === $isBase;
        });
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->getListSortedByIso() as $isoFiles) {
            /** @var SnippetFileInterface $snippetFile */
            foreach ($isoFiles as $snippetFile) {
                $name = $snippetFile->getName();
                $data[$name] = [
                    'name' => $name,
                    'iso' => $snippetFile->getIso(),
                    'path' => $snippetFile->getPath(),
                    'author' => $snippetFile->getAuthor(),
                    'isBase' => $snippetFile->isBase(),
                ];
            }
        }

        return $data;
    }

    public function getIsoList(): array
    {
        return array_keys($this->getListSortedByIso());
    }

    public function getSnippetFilesByIso(string $iso): array
    {
        $list = $this->getListSortedByIso();

        return $list[$iso] ?? [];
    }

    public function getBaseFileByIso(string $iso): SnippetFileInterface
    {
        $files = $this->getSnippetFilesByIso($iso);

        /** @var SnippetFileInterface $file */
        foreach ($files as $file) {
            if (!$file->isBase()) {
                continue;
            }

            return $file;
        }

        throw new InvalidSnippetFileException($iso);
    }

    protected function getExpectedClass(): ?string
    {
        return SnippetFileInterface::class;
    }

    private function getListSortedByIso(): array
    {
        $list = [];

        foreach ($this->getIterator() as $element) {
            $list[$element->getIso()][] = $element;
        }

        return $list;
    }
}

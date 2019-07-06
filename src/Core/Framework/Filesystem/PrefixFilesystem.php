<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Filesystem;

use League\Flysystem\FilesystemInterface;

class PrefixFilesystem extends AbstractFilesystem
{
    /**
     * @var string
     */
    private $prefix;

    public function __construct(FilesystemInterface $filesystem, string $prefix)
    {
        parent::__construct($filesystem);

        if (empty($prefix)) {
            throw new \InvalidArgumentException('The prefix must not be empty.');
        }

        $this->prefix = $this->normalizePrefix($prefix);
    }

    /**
     * {@inheritdoc}
     */
    public function stripPath(string $path): string
    {
        $prefix = rtrim($this->prefix, '/');
        $path = str_replace($prefix, '', $path);
        $path = ltrim($path, '/');

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function preparePath(string $path): string
    {
        return $this->prefix . $path;
    }

    private function normalizePrefix(string $prefix): string
    {
        return trim($prefix, '/') . '/';
    }
}

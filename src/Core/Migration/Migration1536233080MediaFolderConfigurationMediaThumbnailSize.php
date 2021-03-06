<?php declare(strict_types=1);

namespace Shopware\Core\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1536233080MediaFolderConfigurationMediaThumbnailSize extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1536233080;
    }

    public function update(Connection $connection): void
    {
        $connection->exec('
            CREATE TABLE `media_folder_configuration_media_thumbnail_size` (
                `media_folder_configuration_id` BINARY(16) NOT NULL,
                `media_thumbnail_size_id` BINARY(16) NOT NULL,
                PRIMARY KEY (`media_folder_configuration_id`, `media_thumbnail_size_id`),
                CONSTRAINT `fk.media_folder_configuration_thumbnail_size.conf_id` FOREIGN KEY (`media_folder_configuration_id`)
                  REFERENCES `media_folder_configuration` (`id`) ON DELETE CASCADE,
                CONSTRAINT `fk.media_folder_configuration_thumbnail_size.size_id` FOREIGN KEY (`media_thumbnail_size_id`)
                  REFERENCES `media_thumbnail_size` (`id`) ON DELETE CASCADE
            );
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // no destructive changes
    }
}

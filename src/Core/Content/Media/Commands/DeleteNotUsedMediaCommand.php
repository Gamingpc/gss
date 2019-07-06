<?php declare(strict_types=1);

namespace Shopware\Core\Content\Media\Commands;

use Shopware\Core\Content\Media\DeleteNotUsedMediaService;
use Shopware\Core\Framework\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteNotUsedMediaCommand extends Command
{
    /**
     * @var DeleteNotUsedMediaService
     */
    private $deleteMediaService;

    public function __construct(DeleteNotUsedMediaService $deleteMediaService)
    {
        parent::__construct();

        $this->deleteMediaService = $deleteMediaService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('media:delete-unused')
            ->setDescription('Deletes all media files that are never used')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $context = Context::createDefaultContext();

        $count = $this->deleteMediaService->countNotUsedMedia($context);

        if ($count === 0) {
            $io->comment('No unused media files found.');

            return null;
        }

        $confirm = $io->confirm(sprintf('Are you sure that you want to delete %d media files?', $count), false);

        if (!$confirm) {
            $io->caution('Aborting due to user input.');

            return null;
        }

        $this->deleteMediaService->deleteNotUsedMedia($context);
        $io->success(sprintf('Successfully deleted %d media files.', $count));
    }
}

<?php declare(strict_types=1);

namespace Shopware\Core\System\User\Command;

use Shopware\Core\Framework\Provisioning\UserProvisioner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserCreateCommand extends Command
{
    /**
     * @var UserProvisioner
     */
    private $userProvisioner;

    public function __construct(UserProvisioner $userProvisioner)
    {
        parent::__construct();
        $this->userProvisioner = $userProvisioner;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('user:create')
            ->addArgument('username', InputArgument::REQUIRED, 'Username for the user')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password for the user')
            ->addOption('firstName', null, InputOption::VALUE_REQUIRED, 'The user\'s firstname')
            ->addOption('lastName', null, InputOption::VALUE_REQUIRED, 'The user\'s lastname')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'E-Mail for the user')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $password = $input->getOption('password');

        if (empty($password)) {
            $passwordQuestion = new Question('Password for the user');
            $passwordQuestion->setHidden(true);
            $passwordQuestion->setMaxAttempts(3);

            $password = $io->askQuestion($passwordQuestion);
        }

        $additionalData = [];
        if ($input->getOption('lastName')) {
            $additionalData['lastName'] = $input->getOption('lastName');
        }
        if ($input->getOption('firstName')) {
            $additionalData['firstName'] = $input->getOption('firstName');
        }
        if ($input->getOption('email')) {
            $additionalData['email'] = $input->getOption('email');
        }

        $this->userProvisioner->provision($username, $password, $additionalData);

        $io->success(sprintf('User "%s" successfully created.', $username));
    }
}

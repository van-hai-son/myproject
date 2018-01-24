<?php

namespace MyProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class OrderCommand
 *
 * @package MyProjectBundle\Command
 */
class OrderCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('myproject:order:add')
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Amount of users which you want to add.',
                50
            )
            ->addOption(
                'send-mail',
                null,
                InputOption::VALUE_OPTIONAL,
                'Enable send mail to user.',
                1
            )
            ->setDescription('Create Service Password, send email to inform Service Password for user');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        //$this->getContainer()->get('myproject.user_model')->persistUser();

        $io->success('Done');
    }
}

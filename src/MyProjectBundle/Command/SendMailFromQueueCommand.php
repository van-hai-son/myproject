<?php

namespace MyProjectBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SendMailFromQueueCommand
 *
 * @package MyProjectBundle\Command
 */
class SendMailFromQueueCommand extends ContainerAwareCommand
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('myproject:queue.create_order:send_mail');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $message = $container->get('queue_manager')->receiveMessage( 'send_create_order_mail');
        $messageArr = json_decode($message, true);
        $orderList = $container->get('repository.mysql.order')->findBy(['code' => $messageArr['code']]);
        $container->get('mailer_manager')->send('create_order', $orderList[0]);

        sleep(1000);
    }
}

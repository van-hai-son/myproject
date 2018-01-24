<?php

namespace MyProjectBundle\Command;

use MyProjectBundle\QueueManager\QueueManager;
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
        $message = $container->get('queue_manager')->receiveMessage( QueueManager::SEND_CREATE_ORDER_MAIL);
        $messageArr = json_decode($message, true);
        $orderList = $container->get('myproject.shopping_model')
            ->getOrderByConditions(['code' => $messageArr['code']]);
        $container->get('mailer.create_order')->send($orderList[0]);

        sleep(1000);
    }
}

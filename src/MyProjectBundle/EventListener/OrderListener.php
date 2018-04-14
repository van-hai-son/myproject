<?php

namespace MyProjectBundle\EventListener;

use Monolog\Logger;
use MyProjectBundle\Event\OrderEvent;
use MyProjectBundle\Queue\QueueManager;
use MyProjectBundle\Repository\MySQL\OrderRepository;

/**
 * Class OrderListener
 * @package MyProjectBundle\EventListener
 */
class OrderListener
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var QueueManager
     */
    private $queueManager;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * OrderListener constructor.
     * @param OrderRepository $orderRepository
     * @param QueueManager $queueManager
     * @param Logger $logger
     */
    public function __construct(
        OrderRepository $orderRepository,
        QueueManager $queueManager,
        Logger $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->queueManager = $queueManager;
        $this->logger = $logger;
    }

    /**
     * @param OrderEvent $orderEvent
     */
    public function createOrder(OrderEvent $orderEvent)
    {
        try {
            $request = $orderEvent->getRequest();
            $code = $this->orderRepository->createOrder($request);
            $this->queueManager->pushMessage('send_create_order_mail', $code);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $orderEvent->getRequest() ?? []);
        }
    }
}

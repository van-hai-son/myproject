<?php
namespace MyProjectBundle\QueueManager;

use QueueBundle\Queue;

/**
 * Class QueueManager
 * @package MyProjectBundle\QueueManager
 */
class QueueManager
{
    const SEND_CREATE_ORDER_MAIL = 'send_create_order_mail';

    /**
     * @var Queue
     */
    protected $queue;

    /**
     * QueueManager constructor.
     * @param Queue $queue
     */
    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param $queue
     * @param $message
     * @return bool
     */
    public function pushMessage($queue, $message)
    {
        return $this->queue->pushMessage($queue, $message);
    }

    /**
     * @param $queue
     * @return string
     */
    public function receiveMessage($queue)
    {
        return $this->queue->receiveMessage($queue);
    }
}
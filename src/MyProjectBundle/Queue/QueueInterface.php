<?php

namespace MyProjectBundle\Queue;

/**
 * Interface QueueInterface
 * @package namespace MyProjectBundle\Queue
 */
interface QueueInterface
{
    /**
     * @param string $queueName
     * @param string $message
     * @return bool
     */
    public function pushMessage($queueName, $message);

    /**
     * @param string $queueName
     * @return string
     */
    public function receiveMessage($queueName);
}

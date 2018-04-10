<?php
namespace MyProjectBundle\Queue;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class QueueManager
 * @package namespace MyProjectBundle\Queue
 */
class QueueManager
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * Queue constructor.
     * @param QueueInterface $queue
     * @param Logger $logger
     * @param array $configuration
     */
    public function __construct(QueueInterface $queue, Logger $logger, array $configuration)
    {
        $this->queue = $queue;
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    /**
     * @param string $queueName
     * @param string $message
     * @return bool
     */
    public function pushMessage(string $queueName, $message)
    {
        if (!isset($this->configuration[$queueName])) {
            $this->logger->error(sprintf('Queue %s does not set', $queueName));
            return false;
        }
        $queueUrl = $this->configuration[$queueName];
        $result = $this->queue->pushMessage($queueUrl, $message);
        if ($result['status'] == Response::HTTP_OK) {
            $this->logger->info('Send message to Queue complete: ', $result);
            return true;
        } else {
            $this->logger->error('Send message to Queue fail: ', $result);
            return false;
        }
    }

    /**
     * @param string $queueName
     * @return string
     */
    public function receiveMessage(string $queueName)
    {
        if (!isset($this->configuration[$queueName])) {
            $this->logger->error(sprintf('Queue %s does not set', $queueName));
            return '';
        }
        $queueUrl = $this->configuration[$queueName];
        $result = $this->queue->receiveMessage($queueUrl);
        if ($result['status'] == Response::HTTP_OK) {
            $this->logger->info('Receive message complete: ', $result);
            return $result['body'];
        } else {
            $this->logger->error('Receive message fail: ', $result);
            return '';
        }
    }
}

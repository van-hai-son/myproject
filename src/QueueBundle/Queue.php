<?php

namespace QueueBundle;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Queue
 * @package namespace QueueBundle
 */
class Queue
{
    /**
     * @var QueueInterface
     */
    protected $provider;

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
     * @param QueueInterface $provider
     * @param Logger $logger
     * @param array $configuration
     */
    public function __construct(QueueInterface $provider, Logger $logger, array $configuration)
    {
        $this->provider = $provider;
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    /**
     * @param string $queue
     * @param array $message
     * @return bool
     */
    public function pushMessage(string $queue, array $message)
    {
        if (!in_array($queue, $this->configuration)) {
            $this->logger->error(sprintf('Queue %s does not set', $queue));
            return false;
        }

        $request = [
            'queue' => $this->configuration[$queue],
            'message' => $message
        ];
        $result = $this->provider->sendMessage($request);
        if ($result['status'] == Response::HTTP_OK) {
            $this->logger->info('Send message to Queue complete: ', $result);
            return true;
        } else {
            $this->logger->error('Send message to Queue fail: ', $result);
            return false;
        }
    }

    /**
     * @param string $queue
     * @return string
     */
    public function receiveMessage(string $queue)
    {
        if (!isset($this->configuration[$queue])) {
            $this->logger->error(sprintf('Queue %s does not set', $queue));
            return '';
        }

        $request['queue'] = $this->configuration[$queue];
        $result = $this->provider->receiveMessage($request);
        if ($result['status'] == Response::HTTP_OK) {
            $this->logger->info('Receive message complete: ', $result);
            return $result['body'];
        } else {
            $this->logger->error('Receive message fail: ', $result);
            return '';
        }
    }
}

<?php

namespace MyProjectBundle\Queue\Provider;

use Aws\Credentials\Credentials;
use Aws\Sqs\SqsClient;
use MyProjectBundle\Queue\QueueInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SimpleQueueService
 * @package MyProjectBundle\Queue\Provider
 */
class SimpleQueueService implements QueueInterface
{
    const QUEUE_PROVIDER = 'SQS';

    /**
     * @var Credentials
     */
    protected $credentials;

    /**
     * SimpleQueueService constructor.
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct(string $apiKey, string $apiSecret)
    {
        $this->credentials = new Credentials($apiKey, $apiSecret);
    }

    /**
     * @param string $queueName
     * @param string $message
     * @return array
     */
    public function pushMessage($queueName, $message)
    {
        $UrlElement = explode('.', $queueName);
        $client = SqsClient::factory(
            [
                'credentials' => $this->credentials,
                'region' => $UrlElement[1],
                'version' => 'latest'
            ]
        );
        $result = $client->sendMessage([
            'QueueUrl'    => $queueName,
            'MessageBody' => $message,
            'MessageGroupId' => '586474de88e03'
        ]);

        return [
            'provider' => self::QUEUE_PROVIDER,
            'queue' => $queueName,
            'status' => $result['@metadata']['statusCode'] ?? Response::HTTP_FORBIDDEN
        ];
    }

    /**
     * @param string $queueName
     * @return array
     */
    public function receiveMessage($queueName)
    {
        $UrlElement = explode('.', $queueName);
        $client = SqsClient::factory(
            [
                'credentials' => $this->credentials,
                'region' => $UrlElement[1],
                'version' => 'latest'
            ]
        );
        $result = $client->receiveMessage(['QueueUrl' => $queueName]);

        return [
            'provider' => self::QUEUE_PROVIDER,
            'queue' => $queueName,
            'status' => $result['@metadata']['statusCode'] ?? Response::HTTP_FORBIDDEN,
            'body' => $result['Messages'][0]['Body'] ?? ''
        ];
    }
}

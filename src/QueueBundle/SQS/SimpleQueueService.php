<?php

namespace QueueBundle\SQS;

use Aws\Credentials\Credentials;
use Aws\Sqs\SqsClient;
use QueueBundle\QueueInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SimpleQueueService
 * @package QueueBundle\SQS
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
     * @param array $request
     * @return array
     */
    public function pushMessage(array $request)
    {
        $UrlElement = explode('.', $request['queue']);
        $client = SqsClient::factory(
            [
                'credentials' => $this->credentials,
                'region' => $UrlElement[1],
                'version' => 'latest'
            ]
        );

        $result = $client->sendMessage([
            'QueueUrl'    => $request['queue'],
            'MessageBody' => $request['message'],
            'MessageGroupId' => '586474de88e03'
        ]);
        return [
            'provider' => self::QUEUE_PROVIDER,
            'status' => $result['@metadata']['statusCode'] ?? Response::HTTP_FORBIDDEN
        ];
    }

    /**
     * @param array $request
     * @return array
     */
    public function receiveMessage(array $request)
    {
        $UrlElement = explode('.', $request['queue']);
        $client = SqsClient::factory(
            [
                'credentials' => $this->credentials,
                'region' => $UrlElement[1],
                'version' => 'latest'
            ]
        );

        $result = $client->receiveMessage(['QueueUrl' => $request['queue']]);
        return [
            'provider' => self::QUEUE_PROVIDER,
            'status' => $result['@metadata']['statusCode'] ?? Response::HTTP_FORBIDDEN,
            'body' => $result['Messages'][0]['Body'] ?? ''
        ];
    }
}
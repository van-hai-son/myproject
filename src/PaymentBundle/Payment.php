<?php

namespace PaymentBundle;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Payment
 * @package namespace PaymentBundle
 */
class Payment
{
    /**
     * @var PaymentInterface
     */
    protected $provider;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Payment constructor.
     * @param PaymentInterface $provider
     * @param Logger $logger
     */
    public function __construct(PaymentInterface $provider, Logger $logger)
    {
        $this->provider = $provider;
        $this->logger = $logger;
    }

    /**
     * @param $payInfo
     * @return bool
     */
    public function pay($payInfo)
    {
        $result = $this->provider->pay($payInfo);
        if ($result['status'] == Response::HTTP_OK) {
            $this->logger->info('Charge complete: ', $result);
            return true;
        } else {
            $this->logger->error('Charge fail: ', $result);
            return false;
        }
    }
}

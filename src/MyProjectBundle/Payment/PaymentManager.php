<?php

namespace MyProjectBundle\Payment;

use Monolog\Logger;

/**
 * Class PaymentManager
 * @package namespace MyProjectBundle\Payment
 */
class PaymentManager
{
    /**
     * @var PaymentInterface
     */
    protected $payment;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * PaymentManager constructor.
     * @param PaymentInterface $payment
     * @param Logger $logger
     */
    public function __construct(PaymentInterface $payment, Logger $logger)
    {
        $this->payment = $payment;
        $this->logger = $logger;
    }

    /**
     * @param array $payInfo
     * @return bool
     */
    public function pay($payInfo)
    {
        $result = $this->payment->pay($payInfo);
        if ($result['status']) {
            $this->logger->info(
                sprintf('Charge with %s complete: ', $result['provider']),
                $result['data']
            );
        } else {
            $this->logger->error(
                sprintf('Charge with %s fail: ', $result['provider']),
                $result['data']
            );
        }

        $result['status'];
    }
}

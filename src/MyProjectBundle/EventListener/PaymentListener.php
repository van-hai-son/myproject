<?php

namespace MyProjectBundle\EventListener;

use Monolog\Logger;
use MyProjectBundle\Event\PaymentEvent;
use MyProjectBundle\Payment\PaymentManager;

class PaymentListener
{
    /**
     * @var PaymentManager
     */
    private $paymentManager;

    /**
     * @var Logger
     */
    private $logger;
    /**
     * PaymentListener constructor.
     * @param PaymentManager $paymentManager
     * @param Logger $logger
     */
    public function __construct(PaymentManager $paymentManager, Logger $logger)
    {
        $this->paymentManager = $paymentManager;
        $this->logger = $logger;
    }

    /**
     * @param PaymentEvent $paymentEvent
     */
    public function createPayment(PaymentEvent $paymentEvent)
    {
        try {
            $request = $paymentEvent->getRequest();
            $this->paymentManager->pay($request);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $paymentEvent->getRequest() ?? []);
        }
    }
}

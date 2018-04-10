<?php

namespace MyProjectBundle\Payment;

/**
 * Interface PaymentInterface
 * @package namespace MyProjectBundle\Payment
 */
interface PaymentInterface
{
    /**
     * @param array $payInfo
     */
    public function pay($payInfo);
}

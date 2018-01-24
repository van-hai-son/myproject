<?php

namespace PaymentBundle;

/**
 * Interface PaymentInterface
 * @package namespace PaymentBundle
 */
interface PaymentInterface
{
    /**
     * @param array $payInfo
     */
    public function pay(array $payInfo);
}

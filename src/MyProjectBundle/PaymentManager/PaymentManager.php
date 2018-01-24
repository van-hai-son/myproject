<?php
namespace MyProjectBundle\PaymentManager;

use PaymentBundle\Payment;

/**
 * Class PaymentManager
 * @package MyProjectBundle\PaymentManager
 */
class PaymentManager
{
    const CREATE_ORDER_PAYMENT = 'create_order';
    const PAYMENT_TYPE = [
        self::CREATE_ORDER_PAYMENT
    ];

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * PaymentManager constructor.
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * @param $code
     * @param $payInfo
     * @return bool
     */
    public function pay($code, $payInfo)
    {
        if (in_array($code, self::PAYMENT_TYPE)) {
            $payInfo['description'] = $code;
            return $this->payment->pay($payInfo);
        }

        return false;
    }
}
<?php

namespace MyProjectBundle\Payment\Provider;

use MyProjectBundle\Payment\PaymentInterface;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;

/**
 * Class StripePayment
 * @package MyProjectBundle\Payment\Provider
 */
class StripePayment implements PaymentInterface
{
    const PAYMENT_PROVIDER = 'Stripe';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * StripePayment constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param array $payInfo
     * @return array
     */
    public function pay($payInfo)
    {
        $cardInfo = [
            'number' => $payInfo['number'],
            'exp_month' => $payInfo['exp_month'],
            'exp_year' => $payInfo['exp_year'],
            'cvc' => $payInfo['cvc']
        ];
        $paymentInfo = [
            'amount' => $payInfo['amount'],
            'currency' => 'usd',
            'source' => 'tok_visa',
            'description' => $payInfo['description']
        ];
        Stripe::setApiKey($this->apiKey);
        Token::create(["card" => $cardInfo]);
        $result = Charge::create(
            $paymentInfo,
            ["idempotency_key" => $this->generateIdempotencyKey()]
        );

        return [
            'provider' => self::PAYMENT_PROVIDER,
            'status' => ($result->status == 'succeeded') ? true : false,
            'data' => $result
        ];
    }

    /**
     * @return string
     */
    private function generateIdempotencyKey()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}

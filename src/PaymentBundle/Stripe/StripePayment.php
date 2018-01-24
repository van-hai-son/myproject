<?php

namespace PaymentBundle\Stripe;

use PaymentBundle\PaymentInterface;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class StripePayment
 * @package PaymentBundle\Stripe
 */
class StripePayment implements PaymentInterface
{
    const PAYMENT_PROVIDER = 'Stripe';
    const RESPONSE_STATUS = [
        'succeeded' => Response::HTTP_OK
    ];

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
    public function pay(array $payInfo)
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
            ["idempotency_key" => rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=')]
        );

        $status = Response::HTTP_FORBIDDEN;
        if (in_array($result->status, self::RESPONSE_STATUS)) {
            $status = self::RESPONSE_STATUS[$result->status];
        }

        return [
            'provider' => self::PAYMENT_PROVIDER,
            'status' => $status,
            'description' => $payInfo['description'],
            'amount' => $payInfo['amount']
        ];
    }
}
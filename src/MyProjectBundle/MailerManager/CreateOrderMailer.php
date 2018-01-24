<?php
namespace MyProjectBundle\MailerManager;

use MailerBundle\Mailer;
use MyProjectBundle\Entity\Order;

class CreateOrderMailer
{
    const CODE = 'create_order';

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * OrderMailer constructor.
     * @param Mailer $mailer
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function send(Order $order)
    {
        $dynamic = [
            'user_name' => $order->getCustomerName(),
            'user_email' => $order->getEmail()
        ];

        return $this->mailer->send(self::CODE, $dynamic);
    }
}
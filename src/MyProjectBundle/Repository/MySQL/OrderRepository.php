<?php

namespace MyProjectBundle\Repository\MySQL;

use Doctrine\ORM\EntityRepository;
use MyProjectBundle\Entity\Order;
use MyProjectBundle\Util\Generator;

/**
 * Class OrderRepository
 * @package MyProjectBundle\Repository\MySQL
 */
class OrderRepository extends EntityRepository
{
    /**
     * @var Generator
     */
    private $generator;

    /**
     * @param $orderRequest
     * @return bool
     */
    public function createOrder($orderRequest)
    {
        $code = $this->generator->generateOrderCode();
        foreach ($orderRequest['order'] as $item) {
            $order = new Order();
            $order->setCustomerName($orderRequest['name'])
                ->setPhone($orderRequest['phone'])
                ->setEmail($orderRequest['email'])
                ->setDeliveryAddress($orderRequest['address'])
                ->setDeliveryDate($orderRequest['date'])
                ->setOrderStatus($orderRequest['status'])
                ->setOrderCode($code)
                ->setProductId($item['id'])
                ->setProductName($item['name'])
                ->setAmount($item['amount'])
                ->setProductPrice($item['price']);
            $this->getEntityManager()->persist($order);
        }
        $this->getEntityManager()->flush();

        return $code;
    }

    /**
     * @param Generator $generator
     */
    public function setGenerator(Generator $generator)
    {
        $this->generator = $generator;
    }
}

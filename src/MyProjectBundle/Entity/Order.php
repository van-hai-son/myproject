<?php

namespace MyProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="`order`", indexes={@ORM\Index(name="order_idx", columns={"id"})})
 */
class Order extends Entity
{
    const NOT_PAY = 0;
    const IS_PAID = 1;
    const DELIVERY_WITH_CHARGE = 2;
    const DELIVERY_WITHOUT_CHARGE = 3;
    const COMPLETE = 4;
    const CANCEL = 5;
    const REFUND = 6;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="order_code", type="string", nullable=false, options={"default" : ""})
     */
    private $orderCode;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", nullable=false, options={"default" : ""})
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=false, options={"default" : ""})
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=false, options={"default" : ""})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false, options={"default" : 0})
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", nullable=false, options={"default" : ""})
     */
    private $productName;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_price", type="integer", nullable=false, options={"default" : 0})
     */
    private $productPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false, options={"default" : 0})
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_address", type="string", nullable=false, options={"default" : ""})
     */
    private $deliveryAddress;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="datetime", nullable=false)
     */
    private $deliveryDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_status", type="integer", nullable=false, options={"default" : 0})
     */
    private $orderStatus;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderCode()
    {
        return $this->orderCode;
    }

    /**
     * @param $orderCode
     * @return $this
     */
    public function setOrderCode($orderCode)
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param $customerName
     * @return $this
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return string
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * @param $productName
     * @return $this
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * @param $productPrice
     * @return $this
     */
    public function setProductPrice($productPrice)
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryAddress()
    {
        return $this->deliveryAddress;
    }

    /**
     * @param $deliveryAddress
     * @return $this
     */
    public function setDeliveryAddress($deliveryAddress)
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param $deliveryDate
     * @return $this
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * @return integer
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param $orderStatus
     * @return $this
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }
}

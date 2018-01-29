<?php

namespace MyProjectBundle\Model;

use MyProjectBundle\Document\Product;
use MyProjectBundle\Entity\Order;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;

/**
 * Class ShoppingModel
 * @package MyProjectBundle\Model
 */
class ShoppingModel extends BaseModel
{
    /**
     * @param $conditions
     * @return array|Order[]
     */
    public function getOrderByConditions($conditions)
    {
        return $this->getRepository(Order::class)->findBy($conditions);
    }

    /**
     * @param string $query
     * @return array
     */
    public function getProduct(string $query)
    {
        $productRepo = $this->getRepository(Product::class);
        $queryString = new QueryStringQuery($query);
        $search = $productRepo->createSearch();
        $search->addQuery($queryString);
        $products = $productRepo->findDocuments($search);

        /** @var Product $product */
        $result = [];
        foreach ($products as $product) {
            $result[] = [
                'name' => $product->getName(),
                'description' => $product->getDescription()
            ];
        }

        return $result;
    }

    /**
     * @param $orderRequest
     * @return bool
     */
    public function createOrder($orderRequest)
    {
        $code = strtoupper(bin2hex(random_bytes(4)));
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
            $this->save($order);
        }

        return $code;
    }

    /**
     * @param $orderRequest
     * @return int
     */
    public function calculatePrice(&$orderRequest)
    {
        $idList = array_column($orderRequest, 'id');
        $productList = $this->getRepository(Product::class)->findBy(['id' => $idList]);
        $priceList = [];
        foreach ($productList as $product) {
            $priceList[$product->getId()] = $product->getPrice();
        }

        $totalPrice = 0;
        foreach ($orderRequest as &$order) {
            $order['price'] = $priceList[$order['id']];
            $totalPrice = $totalPrice + $priceList[$order['id']] * $order['amount'];
        }
        unset($order);

        return $totalPrice;
    }
}

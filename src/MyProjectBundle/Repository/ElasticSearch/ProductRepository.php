<?php

namespace MyProjectBundle\Repository\ElasticSearch;

use MyProjectBundle\Document\Product;
use ONGR\ElasticsearchBundle\Service\Repository;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;

/**
 * Class ProductRepository
 * @package MyProjectBundle\Repository\ElasticSearch
 */
class ProductRepository extends Repository
{
    /**
     * @param array $orderRequest
     * @return float
     */
    public function calculatePrice(&$orderRequest)
    {
        $idList = array_column($orderRequest, 'id');
        $productList = $this->findBy(['id' => $idList]);
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

    /**
     * @param string $query
     * @return array
     */
    public function getProduct(string $query)
    {
        $queryString = new QueryStringQuery($query);
        $search = $this->createSearch();
        $search->addQuery($queryString);
        $products = $this->findDocuments($search);

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
}

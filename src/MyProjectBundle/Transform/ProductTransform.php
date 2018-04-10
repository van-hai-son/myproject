<?php

namespace MyProjectBundle\Transform;

/**
 * Class ProductTransform
 * @package MyProjectBundle\Transform
 */
class ProductTransform
{
    /**
     * @param array $products
     * @return array
     */
    public function transformProduct($products)
    {
        return $products;
    }

    /**
     * @param array $products
     * @return array
     */
    public function transformProductForAjax($products)
    {
        return $products;
    }
}

<?php

namespace MyProjectBundle\Validation;

/**
 * Class OrderValidation
 * @package MyProjectBundle\Validation
 */
class OrderValidation
{
    /**
     * @param array $param
     * @return bool
     */
    public function validCreateOrderRequest($param)
    {
        if (!isset($param['name']) || !isset($param['phone']) || !isset($param['address'])
            || !isset($param['date']) || !isset($param['email']) || !isset($param['order'])
        ) {
            return true;
        }

        return false;
    }
}

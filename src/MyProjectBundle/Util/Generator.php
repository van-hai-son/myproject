<?php

namespace MyProjectBundle\Util;

/**
 * Class Generator
 * @package MyProjectBundle\Util
 */
class Generator
{
    /**
     * @return string
     */
    public function generateOrderCode()
    {
        return strtoupper(bin2hex(random_bytes(4)));
    }
}

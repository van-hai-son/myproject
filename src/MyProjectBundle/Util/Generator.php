<?php

namespace MyProjectBundle\Util;

/**
 * Class Generator
 * @package MyProjectBundle\Util
 */
class Generator
{
    /**
     * @param int $length
     * @return string
     */
    public function generateOrderCode($length = 4)
    {
        return strtoupper(bin2hex(random_bytes($length)));
    }
}

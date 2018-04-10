<?php

namespace MyProjectBundle\Event;

/**
 * Class OrderEvent
 * @package MyProjectBundle\Event
 */
class OrderEvent
{
    /**
     * @var array
     */
    private $request;

    /**
     * @return array
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }
}

<?php

namespace QueueBundle;

/**
 * Interface QueueInterface
 * @package namespace QueueBundle
 */
interface QueueInterface
{
    /**
     * @param array $request
     */
    public function pushMessage(array $request);

    /**
     * @param array $request
     * @return array
     */
    public function receiveMessage(array $request);
}

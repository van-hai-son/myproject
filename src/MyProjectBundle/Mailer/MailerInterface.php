<?php

namespace MyProjectBundle\Mailer;

/**
 * Interface SenderInterface
 * @package namespace MyProjectBundle\Mailer
 */
interface MailerInterface
{
    /**
     * @param string $code
     * @param array $dynamic
     * @param array $attachment
     */
    public function send(string $code , $dynamic = [], $attachment = []);
}

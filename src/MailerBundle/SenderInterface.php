<?php

namespace MailerBundle;

/**
 * Interface SenderInterface
 * @package namespace MailerBundle
 */
interface SenderInterface
{
    /**
     * @param array $setting
     * @param array $attachment
     */
    public function send(array $setting, array $attachment = []);
}

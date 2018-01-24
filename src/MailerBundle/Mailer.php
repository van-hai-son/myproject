<?php

namespace MailerBundle;

use Monolog\Logger;

/**
 * Class Mailer
 * @package namespace MailerBundle
 */
class Mailer
{
    /**
     * @var SenderInterface
     */
    protected $sender;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var array
     */
    protected $configuration;

    /**
     * Mailer constructor.
     * @param SenderInterface $sender
     * @param Logger $logger
     * @param array $configuration
     */
    public function __construct(SenderInterface $sender, Logger $logger, array $configuration)
    {
        $this->sender = $sender;
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    /**
     * @param string $code
     * @param array $dynamic
     * @param array $attachment
     * @return bool
     */
    public function send(string $code, array $dynamic = [], array $attachment = [])
    {
        $setting = array_merge(
            $this->configuration['global'],
            $this->configuration[$code],
            $dynamic
        );

        $result = $this->sender->send($setting, $attachment);
        if ($result['status'] >202) {
            $this->logger->error('Send mail fail: ', $result);
           return false;
        } else {
            $this->logger->info('Send mail complete: ', $result);
            return true;
        }
    }
}

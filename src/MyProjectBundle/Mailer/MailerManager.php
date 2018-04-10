<?php

namespace MyProjectBundle\Mailer;

use Monolog\Logger;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MailerManager
 * @package namespace MyProjectBundle\Mailer
 */
class MailerManager
{
    /**
     * @var MailerInterface
     */
    protected $mailer;

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
     * @param MailerInterface $mailer
     * @param Logger $logger
     * @param array $configuration
     */
    public function __construct(MailerInterface $mailer, Logger $logger, $configuration = [])
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->configuration = $configuration;
    }

    /**
     * @param string $code
     * @param array $dynamic
     * @param array $attachment
     * @return bool
     */
    public function send(string $code, $dynamic = [], $attachment = [])
    {
        if (!isset($this->configuration[$code])) {
            $this->logger->error(sprintf('Mail type %s does not set', $code));
            return false;
        }
        $dynamic = array_merge(
            $this->configuration['global'],
            $this->configuration[$code],
            $dynamic
        );
        $result = $this->mailer->send($code, $dynamic, $attachment);
        if ($result['status'] > Response::HTTP_ACCEPTED) {
            $this->logger->error('Send mail fail: ', $result);
           return false;
        } else {
            $this->logger->info('Send mail complete: ', $result);
            return true;
        }
    }
}

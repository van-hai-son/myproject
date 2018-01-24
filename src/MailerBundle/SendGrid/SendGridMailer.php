<?php

namespace MailerBundle\SendGrid;

use MailerBundle\SenderInterface;
use SendGrid;
use SendGrid\Attachment;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;

/**
 * Class SendGridMailer
 * @package MailerBundle\SendGrid
 */
class SendGridMailer implements SenderInterface
{
    const MAIL_PROVIDER = 'Send Grid';

    /**
     * @var SendGrid
     */
    protected $provider;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * SendGridMailer constructor.
     * @param string $apiKey
     * @param \Twig_Environment $twig
     */
    public function __construct(string $apiKey, \Twig_Environment $twig)
    {
        $this->provider =  new SendGrid($apiKey);
        $this->twig = $twig;
    }

    /**
     * @param array $setting
     * @param array $attachment
     * @return array
     */
    public function send(array $setting, array $attachment = [])
    {
        $subject = $setting['subject'];
        $from = new Email($setting['contact'], $setting['contact_email']);
        $to = new Email($setting['user_name'], $setting['user_email']);
        $html = $this->twig->render(sprintf('@%s', $setting['template']), $setting);
        $content = new Content("text/plain", $html);
        $mail = new Mail($from, $subject, $to, $content);
        $attachments = [];
        if (!empty($attachments)) {
            foreach ($attachments as $attachFile) {
                $attachment = new Attachment();
                $attachment->setContent($attachFile['content']);
                $attachment->setType($attachFile['type']);
                $attachment->setFilename($attachFile['name']);
                $mail->addAttachment($attachment);
            }
        }

        $response = $this->provider->client->mail()->send()->post($mail);
        return [
            'mailer' => self::MAIL_PROVIDER,
            'status' => $response->statusCode(),
            'email' => $setting['user_email'],
            'template' => $setting['template']
        ];
    }
}
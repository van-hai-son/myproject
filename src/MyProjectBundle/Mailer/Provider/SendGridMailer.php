<?php

namespace MyProjectBundle\Mailer\Provider;

use MyProjectBundle\Mailer\MailerInterface;
use SendGrid;
use SendGrid\Attachment;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;

/**
 * Class SendGridMailer
 * @package MyProjectBundle\Mailer\Provider
 */
class SendGridMailer implements MailerInterface
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
     * @param string $code
     * @param array $dynamic
     * @param array $attachment
     * @return array
     */
    public function send(string $code, $dynamic = [], $attachment = [])
    {
        $subject = $dynamic['subject'];
        $from = new Email($dynamic['contact'], $dynamic['contact_email']);
        $to = new Email($dynamic['user_name'], $dynamic['user_email']);
        $html = $this->twig->render(sprintf('@%s', $dynamic['template']), $dynamic);
        $content = new Content("text/plain", $html);
        $mail = new Mail($from, $subject, $to, $content);
        if (!empty($attachment)) {
            foreach ($attachment as $attachFile) {
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
            'type' => $code,
            'status' => $response->statusCode(),
            'email' => $dynamic['user_email'],
            'template' => $dynamic['template']
        ];
    }
}

<?php

namespace Hautzi\SystemMailBundle\SystemMailer\Mailer;

use Hautzi\SystemMailBundle\SystemMailer\ParsedMessage;

class SwiftMailer implements MailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Sends out a ParsedMessage
     *
     * @param ParsedMessage $parsedMessage
     * @param callable      $messageModifier
     */
    public function send(ParsedMessage $parsedMessage, \Closure $messageModifier = null)
    {
        $message = $this->transformMessage($parsedMessage);

        if (null !== $messageModifier) {
            $messageModifier($message);
        }

        $this->mailer->send($message);
    }

    /**
     * Creates a swift message from a ParsedMessage, handles defaults
     *
     * @param ParsedMessage $parsedMessage
     *
     * @return \Swift_Message
     */
    protected function transformMessage(ParsedMessage $parsedMessage)
    {
        $message = new \Swift_Message();

        if ($from = $parsedMessage->getFrom()) {
            $message->setFrom($from);
        }

        // handle to with defaults
        if ($to = $parsedMessage->getTo()) {
            $message->setTo($to);
        }

        // handle cc with defaults
        if ($cc = $parsedMessage->getCc()) {
            $message->setCc($cc);
        }

        // handle bcc with defaults
        if ($bcc = $parsedMessage->getBcc()) {
            $message->setBcc($bcc);
        }

        // handle reply to with defaults
        if ($replyTo = $parsedMessage->getReplyTo()) {
            $message->setReplyTo($replyTo);
        }

        // handle subject with default
        if ($subject = $parsedMessage->getSubject()) {
            $message->setSubject($subject);
        }

        // handle body, no default values here
        $message->setBody($parsedMessage->getMessageText());
        if ($parsedMessage->getMessageHtml()) {
            $message->addPart($parsedMessage->getMessageHtml(), 'text/html');
        }

        return $message;
    }
}

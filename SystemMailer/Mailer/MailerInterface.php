<?php

namespace Hautzi\SystemMailBundle\SystemMailer\Mailer;

use Hautzi\SystemMailBundle\SystemMailer\ParsedMessage;

interface MailerInterface
{
    /**
     * Sends out ParsedMessage
     *
     * @param ParsedMessage $parsedMessage
     * @param callable      $messageModifier
     */
    public function send(ParsedMessage $parsedMessage, \Closure $messageModifier = null);
}

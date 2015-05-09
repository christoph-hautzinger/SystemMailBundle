<?php

namespace Hautzi\SystemMailBundle\SystemMailer;

use Hautzi\SystemMailBundle\SystemMailer\MailDefinition\ParserInterface;
use Hautzi\SystemMailBundle\SystemMailer\MailDefinition\ProviderInterface;
use Hautzi\SystemMailBundle\SystemMailer\Mailer\MailerInterface;

class SystemMailer
{
    /**
     * @var ProviderInterface
     */
    private $provider;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var array
     */
    private $defaults;

    /**
     * @param ProviderInterface $provider
     * @param ParserInterface   $parser
     * @param MailerInterface   $mailer
     * @param string            $locale
     * @param array             $defaults
     */
    public function __construct(ProviderInterface $provider, ParserInterface $parser, MailerInterface $mailer, $locale, $defaults = [])
    {
        $this->provider = $provider;
        $this->parser = $parser;
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->defaults = $defaults;
    }

    /**
     * Sends out the system message
     *
     * Examples:
     * <code>
     *  // sends out AppBundle/Resources/emails/registration/confirmUser.xml.twig
     *  $systemMailer->send('App:registration/confirmUser', ['user' => $user]);
     *
     *  // force locale of mail
     *  $systemMailer->send('App:info-mail', ['user' => $user], 'de');
     *
     *  // attach file to mail
     *  $systemMailer->send('App:message-with-pdf', [], null, function (\Swift_Message $message) {
     *     $message->attach(\Swift_Attachment::fromPath('my-document.pdf'))
     *  });
     * </code>
     *
     * @param string   $name            The email to send out, format: YourBundle:emailXmlTemplateName
     * @param array    $parameters      The parameters passed to your email template xml
     * @param string   $locale          Overwrite default locale of session
     * @param callable $messageModifier Pass Closure to modify message before it is send (to attach files i.e.)
     */
    public function send($name, $parameters = [], $locale = null, \Closure $messageModifier = null)
    {
        $mailDefinition = $this->provider->fetchMailDefinition($name, $parameters);
        $parsedMessage = $this->parser->parseMailDefinition($mailDefinition, $locale ?: $this->locale);

        $this->handleDefaults($parsedMessage);

        $this->mailer->send($parsedMessage, $messageModifier);
    }

    /**
     * Add default parameters when they are not provided from the MailDefinition
     *
     * @param ParsedMessage $parsedMessage
     */
    protected function handleDefaults(ParsedMessage $parsedMessage)
    {
        if (!$parsedMessage->getFrom() && $this->defaults['from']['email']) {
            $parsedMessage->setFrom($this->defaults['from']['email'], $this->defaults['from']['name']);
        }

        if (!$parsedMessage->getTo() && $this->defaults['to']['email']) {
            $parsedMessage->addTo($this->defaults['to']['email'], $this->defaults['to']['name']);
        }

        if (!$parsedMessage->getCc() && $this->defaults['cc']['email']) {
            $parsedMessage->addCC($this->defaults['cc']['email'], $this->defaults['cc']['name']);
        }

        if (!$parsedMessage->getBcc() && $this->defaults['bcc']['email']) {
            $parsedMessage->addBcc($this->defaults['bcc']['email'], $this->defaults['bcc']['name']);
        }

        if (!$parsedMessage->getReplyTo() && $this->defaults['replyTo']) {
            $parsedMessage->setReplyTo($this->defaults['replyTo']);
        }

        if (!$parsedMessage->getSubject() && $this->defaults['subject']) {
            $parsedMessage->setSubject($this->defaults['subject']);
        }
    }
}

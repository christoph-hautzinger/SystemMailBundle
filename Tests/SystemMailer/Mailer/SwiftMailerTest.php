<?php

namespace Hautzi\SystemMailBundle\Tests\SystemMailer\Mailer;

use Hautzi\SystemMailBundle\SystemMailer\Mailer\SwiftMailer;
use Hautzi\SystemMailBundle\SystemMailer\ParsedMessage;
use Prophecy\Argument;

class SwiftMailerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SwiftMailer
     */
    protected $fixture;

    /**
     * @var \Twig_Environment
     */
    protected $mailer;

    protected function setUp()
    {
        $this->mailer = $this->prophesize(\Swift_Mailer::class);

        $this->fixture = new SwiftMailer($this->mailer->reveal());
    }

    public function testSendingMailWorks()
    {
        $message = new ParsedMessage();
        $message->setFrom('from@addr.com', 'from');
        $message->setReplyTo('reply@addr.com');
        $message->addTo('to1@addr.com', 'to1');
        $message->addTo('to2@addr.com', 'to2');
        $message->addCc('cc1@addr.com', 'cc1');
        $message->addCc('cc2@addr.com', 'cc2');
        $message->addBcc('bcc1@addr.com', 'bcc1');
        $message->addBcc('bcc2@addr.com', 'bcc2');
        $message->setSubject('_subject');
        $message->setMessageText('_text_message');
        $message->setMessageHtml('_html_message');

        $this->mailer->send(Argument::type(\Swift_Message::class))->shouldBeCalled();

        $closureExceuted = false;
        $this->fixture->send($message, function (\Swift_Message $message) use (&$closureExceuted) {
            // from
            $this->assertEquals(['from@addr.com' => 'from'], $message->getFrom());
            // reply-to
            $this->assertEquals(['reply@addr.com' => null], $message->getReplyTo());
            // to
            $this->assertEquals(['to1@addr.com' => 'to1', 'to2@addr.com' => 'to2'], $message->getTo());
            // cc
            $this->assertEquals(['cc1@addr.com' => 'cc1', 'cc2@addr.com' => 'cc2'], $message->getCc());
            // bcc
            $this->assertEquals(['bcc1@addr.com' => 'bcc1', 'bcc2@addr.com' => 'bcc2'], $message->getBcc());

            $this->assertEquals('_subject', $message->getSubject());
            $this->assertEquals('_text_message', $message->getBody());

            $closureExceuted = true;
        });

        $this->assertTrue($closureExceuted, 'closure was not executed.');
    }
}

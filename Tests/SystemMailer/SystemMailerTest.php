<?php

namespace Hautzi\SystemMailBundle\Tests\SystemMailer;


use Hautzi\SystemMailBundle\SystemMailer\MailDefinition\ParserInterface;
use Hautzi\SystemMailBundle\SystemMailer\MailDefinition\ProviderInterface;
use Hautzi\SystemMailBundle\SystemMailer\Mailer\MailerInterface;
use Hautzi\SystemMailBundle\SystemMailer\ParsedMessage;
use Hautzi\SystemMailBundle\SystemMailer\SystemMailer;

class SystemMailerTest extends \PHPUnit_Framework_TestCase
{
    protected $fixture;

    /**
     * @var ProviderInterface
     */
    protected $provider;
    /**
     * @var ParserInterface
     */
    protected $parser;
    /**
     * @var MailerInterface
     */
    protected $mailer;


    public function testSendWithoutDefaults()
    {
        $fixture = $this->createFixture('de');

        $parsedMessage = $this->prophesize(ParsedMessage::class)->reveal();

        $this->provider->fetchMailDefinition('App:test', ['param1' => 'yay!'])->willReturn('<email></email>')->shouldBeCalled();
        $this->parser->parseMailDefinition('<email></email>', 'de')->willReturn($parsedMessage)->shouldBeCalled();
        $this->mailer->send($parsedMessage, null)->shouldBeCalled();

        $fixture->send('App:test', ['param1' => 'yay!']);
    }


    public function testSendDefaultsArePopulated()
    {
        $fixture = $this->createFixture('de', [
            'subject' => '_1',
            'replyTo' => '_2',
            'from' => [
                'email' => '_3',
                'name' => '_4',
            ],
            'to' => [
                'email' => '_5',
                'name' => '_6',
            ],
            'cc' => [
                'email' => '_7',
                'name' => '_8',
            ],
            'bcc' => [
                'email' => '_9',
                'name' => '_10',
            ]
        ]);

        $parsedMessage = $this->prophesize(ParsedMessage::class);
        $parsedMessage->getSubject()->willReturn(null);
        $parsedMessage->setSubject('_1')->shouldBeCalled();
        $parsedMessage->getReplyTo()->willReturn(null);
        $parsedMessage->setReplyTo('_2')->shouldBeCalled();
        $parsedMessage->getFrom()->willReturn([]);
        $parsedMessage->setFrom('_3', '_4')->shouldBeCalled();
        $parsedMessage->getTo()->willReturn([]);
        $parsedMessage->addTo('_5', '_6')->shouldBeCalled();
        $parsedMessage->getCc()->willReturn([]);
        $parsedMessage->addCc('_7', '_8')->shouldBeCalled();
        $parsedMessage->getBcc()->willReturn([]);
        $parsedMessage->addBcc('_9', '_10')->shouldBeCalled();

        $this->provider->fetchMailDefinition('App:test', ['param1' => 'yay!'])->willReturn('<email></email>')->shouldBeCalled();
        $this->parser->parseMailDefinition('<email></email>', 'de')->willReturn($parsedMessage)->shouldBeCalled();
        $this->mailer->send($parsedMessage, null)->shouldBeCalled();

        $fixture->send('App:test', ['param1' => 'yay!']);
    }


    /**
     * @param null  $locale
     * @param array $defaults
     *
     * @return SystemMailer
     */
    protected function createFixture($locale = null, $defaults = [])
    {
        $this->provider = $this->prophesize(ProviderInterface::class);
        $this->parser = $this->prophesize(ParserInterface::class);
        $this->mailer = $this->prophesize(MailerInterface::class);

        $defaults = $this->mergeDefaults($defaults);

        return new SystemMailer($this->provider->reveal(), $this->parser->reveal(), $this->mailer->reveal(), $locale, $defaults);
    }

    /**
     * @param array $defaults
     *
     * @return array
     */
    protected function mergeDefaults(array $defaults = [])
    {
        return array_replace_recursive([
            'subject' => '',
            'replyTo' => '',
            'from' => [
                'email' => '',
                'name' => '',
            ],
            'to' => [
                'email' => '',
                'name' => '',
            ],
            'cc' => [
                'email' => '',
                'name' => '',
            ],
            'bcc' => [
                'email' => '',
                'name' => '',
            ],
        ], $defaults);
    }
}

<?php

namespace Hautzi\SystemMailBundle\SystemMailer;

class ParsedMessageTest extends \PHPUnit_Framework_TestCase
{
    public function testGettersAndSetters()
    {
        $fixture = new ParsedMessage();

        $this->assertEmpty($fixture->getFrom());
        $fixture->setFrom('user@addr.com', 'user');
        $this->assertEquals(['user@addr.com' => 'user'], $fixture->getFrom());

        $this->assertEmpty($fixture->getTo());
        $fixture->addTo('user1@addr.com', 'user1');
        $this->assertEquals(['user1@addr.com' => 'user1'], $fixture->getTo());
        $fixture->addTo('user2@addr.com', 'user2');
        $this->assertEquals(['user1@addr.com' => 'user1', 'user2@addr.com' => 'user2',], $fixture->getTo());

        $this->assertEmpty($fixture->getCc());
        $fixture->addCc('user1@addr.com', 'user1');
        $this->assertEquals(['user1@addr.com' => 'user1'], $fixture->getCc());
        $fixture->addCc('user2@addr.com', 'user2');
        $this->assertEquals(['user1@addr.com' => 'user1', 'user2@addr.com' => 'user2',], $fixture->getCc());

        $this->assertEmpty($fixture->getBcc());
        $fixture->addBcc('user1@addr.com', 'user1');
        $this->assertEquals(['user1@addr.com' => 'user1'], $fixture->getBcc());
        $fixture->addBcc('user2@addr.com', 'user2');
        $this->assertEquals(['user1@addr.com' => 'user1', 'user2@addr.com' => 'user2',], $fixture->getBcc());

        $this->assertEmpty($fixture->getReplyTo());
        $fixture->setReplyTo('user@addr.com');
        $this->assertEquals('user@addr.com', $fixture->getReplyTo());

        $this->assertEmpty($fixture->getSubject());
        $fixture->setSubject('_subject');
        $this->assertEquals('_subject', $fixture->getSubject());

        $this->assertEmpty($fixture->getMessageText());
        $fixture->setMessageText('message');
        $this->assertEquals('message', $fixture->getMessageText());

        $this->assertEmpty($fixture->getMessageHtml());
        $fixture->setMessageHtml('message');
        $this->assertEquals('message', $fixture->getMessageHtml());
    }
}

<?php


namespace Hautzi\SystemMailBundle\Tests\SystemMailer\MailDefinition;

use Hautzi\SystemMailBundle\SystemMailer\MailDefinition\MailDefinitionParserXml;

class MailDefinitionParserXmlTest extends \PHPUnit_Framework_TestCase
{

    public function testParseXmlWithAllValidValuesNotLocalized()
    {
        $xml = <<<XML
<email>
    <from name="from_name">from@addr.com</from>
    <replyTo>reply@addr.com</replyTo>
    <to name="to_name1">to1@addr.com</to>
    <to name="to_name2">to2@addr.com</to>
    <to>nameonly_to@addr.com</to>
    <cc name="cc_name1">cc1@addr.com</cc>
    <cc name="cc_name2">cc2@addr.com</cc>
    <cc>nameonly_cc@addr.com</cc>
    <bcc name="bcc_name1">bcc1@addr.com</bcc>
    <bcc name="bcc_name2">bcc2@addr.com</bcc>
    <bcc>nameonly_bcc@addr.com</bcc>
    <subject>_subject</subject>
    <messageText>_text_message</messageText>
    <messageHtml><![CDATA[<p>_html_message</p>]]></messageHtml>
</email>
XML;

        $fixture = new MailDefinitionParserXml();
        $parsedMessage = $fixture->parseMailDefinition($xml);

        // from
        $this->assertEquals(['from@addr.com' => 'from_name'], $parsedMessage->getFrom());
        // reply-to
        $this->assertEquals('reply@addr.com', $parsedMessage->getReplyTo());
        // to
        $this->assertEquals([
            'to1@addr.com' => 'to_name1',
            'to2@addr.com' => 'to_name2',
            'nameonly_to@addr.com',
        ], $parsedMessage->getTo());
        // cc
        $this->assertEquals([
            'cc1@addr.com' => 'cc_name1',
            'cc2@addr.com' => 'cc_name2',
            'nameonly_cc@addr.com',
        ], $parsedMessage->getCc());
        // bcc
        $this->assertEquals([
            'bcc1@addr.com' => 'bcc_name1',
            'bcc2@addr.com' => 'bcc_name2',
            'nameonly_bcc@addr.com',
        ], $parsedMessage->getBcc());

        $this->assertEquals('_subject', $parsedMessage->getSubject());
        $this->assertEquals('_text_message', $parsedMessage->getMessageText());
        $this->assertEquals('<p>_html_message</p>', $parsedMessage->getMessageHtml());
    }

    /**
     * @dataProvider localizedSubjectMessageTextProvider()
     *
     * @param $_locale
     * @param $_subject
     * @param $_messageText
     * @param $_messageHtml
     */
    public function testParseXmlLocalizedFields($_locale, $_subject, $_messageText, $_messageHtml)
    {
        $xml = <<<XML
<email>
    <from name="from_name">from@addr.com</from>
    <to name="to_name1">to1@addr.com</to>
    <subject locale="de">_subject_de</subject>
    <subject locale="en">_subject_en</subject>
    <messageText locale="de">_text_message_de</messageText>
    <messageText locale="en">_text_message_en</messageText>
    <messageHtml locale="de"><![CDATA[<p>_html_message_de</p>]]></messageHtml>
    <messageHtml locale="en"><![CDATA[<p>_html_message_en</p>]]></messageHtml>
</email>
XML;
        $fixture = new MailDefinitionParserXml();
        $parsedMessage = $fixture->parseMailDefinition($xml, $_locale);

        $this->assertEquals($_subject, $parsedMessage->getSubject());
        $this->assertEquals($_messageText, $parsedMessage->getMessageText());
        $this->assertEquals($_messageHtml, $parsedMessage->getMessageHtml());
    }

    public function localizedSubjectMessageTextProvider()
    {
        return [
            ['de', '_subject_de', '_text_message_de', '<p>_html_message_de</p>'],
            ['en', '_subject_en', '_text_message_en', '<p>_html_message_en</p>'],
        ];
    }
}

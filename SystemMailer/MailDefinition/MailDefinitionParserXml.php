<?php

namespace Hautzi\SystemMailBundle\SystemMailer\MailDefinition;

use Hautzi\SystemMailBundle\SystemMailer\ParsedMessage;

class MailDefinitionParserXml implements ParserInterface
{
    /**
     * Parse XML Mail Definition string
     *
     * which looks something like this
     * <code>
     *   <email>
     *      <to name="yourname">your@mail.com</email>
     *      <subject locale="de">Your subject</subject>
     *      <messageText locale="de">Your Message</messageText>
     *   </email>
     * </code>
     *
     * @param string $xml
     * @param string $locale
     *
     * @return ParsedMessage
     *
     * @throws \Exception
     */
    public function parseMailDefinition($xml, $locale = null)
    {
        $parsed = simplexml_load_string($xml);

        $message = new ParsedMessage();

        $from = $parsed->from;
        if ($from->count() > 0) {
            $message->setFrom((string)$from, (string)$from->attributes()['name']);
        }

        $replyTo = $parsed->replyTo;
        if ($replyTo->count() > 0) {
            $message->setReplyTo((string)$replyTo);
        }

        foreach ($parsed->to as $to) {
            $message->addTo((string)$to, (string)$to->attributes()['name']);
        }
        foreach ($parsed->cc as $cc) {
            $message->addCc((string)$cc, (string)$cc->attributes()['name']);
        }
        foreach ($parsed->bcc as $bcc) {
            $message->addBcc((string)$bcc, (string)$bcc->attributes()['name']);
        }

        $message->setSubject($this->getLocalizedTagContent($parsed->subject, $locale));

        $message->setMessageHtml($this->getLocalizedTagContent($parsed->messageHtml, $locale));
        $message->setMessageText($this->getLocalizedTagContent($parsed->messageText, $locale));

        return $message;
    }

    /**
     * @param \SimpleXMLElement $element
     * @param string            $locale
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function getLocalizedTagContent(\SimpleXMLElement $element, $locale = null)
    {
        if (0 === $element->count()) {
            return '';
        }

        if (null == $locale) {
            return $this->multilineRemoveIndent((string)$element, 'true' === (string)$element->attributes()['removeIndent']);
        }

        foreach ($element as $node) {
            if ($locale == (string)$node->attributes()['locale']) {
                return $this->multilineRemoveIndent((string)$node, 'true' === (string)$node->attributes()['removeIndent']);
            }
        }

        $message = sprintf('Locale "%s" not available in node "%s".', $locale, $element->getName());
        throw new \Exception($message);
    }

    /**
     * @param string $string
     * @param bool   $removeThatShit
     *
     * @return mixed
     */
    protected function multilineRemoveIndent($string, $removeThatShit = false)
    {
        if (!$removeThatShit) {
            return $string;
        }

        return trim(preg_replace('@^ *@m', '', $string));
    }
}

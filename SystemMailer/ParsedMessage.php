<?php

namespace Hautzi\SystemMailBundle\SystemMailer;

class ParsedMessage
{
    private $from = [];
    private $replyTo;

    private $to  = [];
    private $cc  = [];
    private $bcc = [];

    private $subject;

    private $messageHtml;

    private $messageText;

    /**
     * @param string $email
     * @param string $name
     *
     * @return array
     */
    protected function transformEmailName($email, $name = '')
    {
        if (!$name) {
            return [$email];
        }

        return [$email => $name];
    }

    /**
     * Gets from
     *
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets from
     *
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function setFrom($email, $name = null)
    {
        $this->from = $this->transformEmailName($email, $name);

        return $this;
    }

    /**
     * Gets replyTo
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Sets replyTo
     *
     * @param mixed $replyTo
     *
     * @return static
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * Gets to
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets to
     *
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function addTo($email, $name = null)
    {
        $this->to = array_merge($this->to, $this->transformEmailName($email, $name));

        return $this;
    }

    /**
     * Gets cc
     *
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Sets cc
     *
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function addCc($email, $name = null)
    {
        $this->cc = array_merge($this->cc, $this->transformEmailName($email, $name));

        return $this;
    }


    /**
     * Gets bcc
     *
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Sets bcc
     *
     * @param string $email
     * @param string $name
     *
     * @return static
     */
    public function addBcc($email, $name = null)
    {
        $this->bcc = array_merge($this->bcc, $this->transformEmailName($email, $name));

        return $this;
    }

    /**
     * Gets subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets subject
     *
     * @param string $subject
     *
     * @return static
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Gets messageHtml
     *
     * @return string
     */
    public function getMessageHtml()
    {
        return $this->messageHtml;
    }

    /**
     * Sets messageHtml
     *
     * @param string $messageHtml
     *
     * @return static
     */
    public function setMessageHtml($messageHtml)
    {
        $this->messageHtml = $messageHtml;

        return $this;
    }

    /**
     * Gets messageText
     *
     * @return string
     */
    public function getMessageText()
    {
        return $this->messageText;
    }

    /**
     * Sets messageText
     *
     * @param string $messageText
     *
     * @return static
     */
    public function setMessageText($messageText)
    {
        $this->messageText = $messageText;

        return $this;
    }
}

<?php

namespace Fei\Service\Mailer\Entity;

use Fei\Entity\AbstractEntity;

/**
 * Class Mail
 */
class Mail extends AbstractEntity
{
    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $textBody;

    /**
     * @var string
     */
    protected $htmlBody;

    /**
     * @var array
     */
    protected $sender;

    /**
     * @var array
     */
    protected $recipients = array();

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextBody()
    {
        return $this->textBody;
    }

    /**
     * @param string $textBody
     *
     * @return $this
     */
    public function setTextBody($textBody)
    {
        $this->textBody = $textBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlBody()
    {
        return $this->htmlBody;
    }

    /**
     * @param string $htmlBody
     *
     * @return $this
     */
    public function setHtmlBody($htmlBody)
    {
        $this->htmlBody = $htmlBody;

        return $this;
    }

    /**
     * @return array
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string|array $sender
     *
     * @return $this
     */
    public function setSender($sender)
    {
        if (is_array($sender)) {
            $email = is_int(key($sender)) ? current($sender) : key($sender);
            $this->sender = [$email => current($sender)];
        } else {
            $this->sender = [$sender => $sender];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     *
     * @return $this
     */
    public function setRecipients(array $recipients)
    {
        $this->clearRecipients();

        foreach ($recipients as $recipient => $label) {
            if (is_int($recipient)) {
                $recipient = $label;
            }

            $this->addRecipient($recipient, $label);
        }

        return $this;
    }

    /**
     * @param  string $recipient Recipient email address
     * @param string $label Recipient full name (defaults to email address)
     *
     * @return $this
     */
    public function addRecipient($recipient, $label = '')
    {
        $label = $label ?: $recipient;
        $this->recipients[$recipient] = $label;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearRecipients()
    {
        $this->recipients = array();

        return $this;
    }
}

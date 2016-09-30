<?php

namespace Fei\Service\Mailer\Entity;

use Fei\Entity\AbstractEntity;

/**
 * Class Mail
 *
 * @package Fei\Service\Mailer\Entity
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
    protected $replyTo;

    /**
     * @var array
     */
    protected $recipients = array();

    /**
     * @var array
     */
    protected $cc = array();

    /**
     * @var array
     */
    protected $bcc = array();

    /**
     * @var array
     */
    protected $attachments = array();

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
        $this->sender = $this->formatAddress($sender);

        return $this;
    }

    /**
     * @return array
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * @param string|array $replyTo
     *
     * @return $this
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $this->formatAddress($replyTo);

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
        $this->initAddress($recipients, 'recipients');

        return $this;
    }

    /**
     * @param string $recipient Recipient email address
     * @param string $label Recipient full name (defaults to email address)
     *
     * @return $this
     */
    public function addRecipient($recipient, $label = '')
    {
        $this->addAddress($recipient, $label, 'recipients');

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

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param array $cc
     *
     * @return $this
     */
    public function setCc(array $cc)
    {
        $this->initAddress($cc, 'cc');

        return $this;
    }

    /**
     * @param string $cc
     * @param string $label
     *
     * @return $this
     */
    public function addCc($cc, $label)
    {
        $this->addAddress($cc, $label, 'cc');

        return $this;
    }

    /**
     * @return $this
     */
    public function clearCc()
    {
        $this->cc = array();

        return $this;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param array $bcc
     *
     * @return $this
     */
    public function setBcc(array $bcc)
    {
        $this->initAddress($bcc, 'bcc');

        return $this;
    }

    /**
     * @param string $bcc
     * @param string $label
     *
     * @return $this
     */
    public function addBcc($bcc, $label)
    {
        $this->addAddress($bcc, $label, 'bcc');

        return $this;
    }

    /**
     * @return $this
     */
    public function clearBcc()
    {
        $this->bcc = array();

        return $this;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param \SplFileObject[]|array $attachments
     */
    public function setAttachments(array $attachments)
    {
        $this->clearAttachments();

        foreach ($attachments as $attachment) {
            $this->addAttachment($attachment);
        }
    }

    /**
     * @param \SplFileObject|Attachment|array $attachment
     *
     * @return $this
     */
    public function addAttachment($attachment)
    {
        if ($attachment instanceof \SplFileObject && $attachment->isFile() && $attachment->isReadable()) {
            $file['filename'] = method_exists($attachment, 'getAttachmentFilename') ?
                $attachment->getAttachmentFilename() :
                $attachment->getBasename();

            $file['is_embedded'] = method_exists($attachment, 'getIsEmbedded') ? $attachment->getIsEmbedded() : false;
            $file['id'] = method_exists($attachment, 'getId') ? $attachment->getId() : null;

            if (method_exists($attachment, 'getMimeType')) {
                $file['mime_type'] = $attachment->getMimeType();
            } else {
                $resource = finfo_open(FILEINFO_MIME_TYPE);
                $file['mime_type'] = finfo_file($resource, $attachment->getRealPath());
            }

            $file['contents'] = '';
            while (!$attachment->eof()) {
                $file['contents'] .= $attachment->fgets();
            }
            $file['contents'] = base64_encode($file['contents']);

            $this->attachments[] = $file;
        } elseif (is_array($attachment)) {
            $this->attachments[] = $attachment;
        }

        return $this;
    }

    /**
     * Clear attachments
     *
     * @return $this
     */
    public function clearAttachments()
    {
        $this->attachments = array();

        return $this;
    }

    /**
     * Get mail context
     *
     * @return array
     */
    public function getContext()
    {
        return array_filter([
            'subject' => $this->getSubject(),
            'from' => $this->addressToString($this->getSender()),
            'to' => $this->addressToString($this->getRecipients()),
            'cc' => $this->addressToString($this->getCc()),
            'bcc' => $this->addressToString($this->getBcc())
        ]);
    }

    /**
     * Set address purpose property
     *
     * @param array  $address
     * @param string $field
     */
    protected function initAddress(array $address, $field)
    {
        $this->{$this->methodName('clear', $field)}();

        foreach ($address as $email => $label) {
            if (is_int($email)) {
                $email = $label;
            }

            $this->{$this->methodName('add', $field)}($email, $label);
        }
    }

    /**
     * Add a address in a array property
     *
     * @param string $address
     * @param string $label
     * @param string $field
     */
    protected function addAddress($address, $label, $field)
    {
        $label = $label ?: $address;
        $this->{$field}[$address] = $label;
    }

    /**
     * Format a address
     *
     * @param string|array $address
     *
     * @return array
     */
    protected function formatAddress($address)
    {
        if (is_array($address)) {
            $email = is_int(key($address)) ? current($address) : key($address);
            return array($email => current($address));
        }

        return array($address => $address);
    }

    /**
     * Convert address container to a string representation
     *
     * @param array $field
     *
     * @return string
     */
    protected function addressToString(array $field)
    {
        $address = [];
        foreach ($field as $key => $value) {
            $address[] = $key == $value ? $value : sprintf('%s <%s>', $value, $key);
        }

        return empty($address) ? null : implode(', ', $address);
    }

    /**
     * Returns the method name given a action and a field name
     *
     * @param $action
     * @param $field
     *
     * @return string
     */
    protected function methodName($action, $field)
    {
        if (substr($field, -1, 1) === 's' && $action != 'clear') {
            $field = substr($field, 0, -1);
        }

        return $action . ucfirst(strtolower($field));
    }
}

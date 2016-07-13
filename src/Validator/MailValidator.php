<?php

namespace Fei\Service\Mailer\Validator;

use Fei\Service\Mailer\Entity\Mail;

/**
 * Validate Mail instance
 *
 * @package Fei\Service\Mailer\Validator
 */
class MailValidator
{
    /**
     * @var array
     */
    protected $errors = array();

    /**
     * @param Mail $mail
     * @return bool
     */
    public function validate(Mail $mail)
    {
        $this->clearErrors();

        $this->validateSubject($mail->getSubject());
        $this->validateBody($mail->getTextBody(), $mail->getHtmlBody());
        $this->validateSender($mail->getSender());
        $this->validateRecipients($mail->getRecipients());

        return empty($this->getErrors());
    }

    /**
     * @param string $subject
     */
    public function validateSubject($subject)
    {
        if (empty($subject)) {
            $this->addError('subject', 'Subject is empty');
        }
    }

    /**
     * @param string $textBody
     * @param string $htmlBody
     */
    public function validateBody($textBody, $htmlBody)
    {
        if (empty($textBody) && empty($htmlBody)) {
            $this->addError('textBody', 'Text body is empty');
            $this->addError('htmlBody', 'HTML body is empty');
        }
    }

    /**
     * @param array $sender
     */
    public function validateSender($sender)
    {
        if (is_null($sender)) {
            $this->addError('sender', 'Sender is null');
            return;
        }

        if (false === filter_var(key($sender), FILTER_VALIDATE_EMAIL)) {
            $this->addError('sender', 'Sender recipient must be a valid email address');
        }
    }

    /**
     * @param array $recipients
     */
    public function validateRecipients($recipients)
    {
        if (empty($recipients)) {
            $this->addError('recipients', 'Recipients is empty');
            return;
        }

        foreach ($recipients as $recipient => $label) {
            if (false === filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                $this->addError(
                    'recipients',
                    sprintf('`%s` is not a valid email address for recipient `%s`', $recipient, $label)
                );
            }
        }
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->clearErrors();

        foreach ($errors as $attribute => $messages) {
            if (is_array($messages)) {
                foreach ($messages as $message) {
                    $this->addError($attribute, $message);
                }
            } else {
                $this->addError($attribute, $messages);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $attribute
     * @param string $message
     * @return $this
     */
    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;

        return $this;
    }

    /**
     * @return $this
     */
    public function clearErrors()
    {
        $this->errors = array();

        return $this;
    }
}

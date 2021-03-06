<?php

namespace Fei\Service\Mailer\Validator;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Fei\Entity\EntityInterface;
use Fei\Entity\Exception;
use Fei\Entity\Validator\AbstractValidator;
use Fei\Service\Mailer\Entity\Mail;

/**
 * Validate Mail instance
 *
 * @package Fei\Service\Mailer\Validator
 */
class MailValidator extends AbstractValidator
{
    /**
     * @param EntityInterface $mail
     *
     * @return bool
     *
     * @throws Exception
     */
    public function validate(EntityInterface $mail)
    {
        if (!$mail instanceof Mail) {
            throw new Exception('Entity to validate must be an instance of ' . Mail::class);
        }

        $this->clearErrors();

        $this->validateSubject($mail->getSubject());
        $this->validateSender($mail->getSender());
        $this->validateAddress($mail->getRecipients(), 'recipients');
        $this->validateAddress($mail->getCc(), 'cc', false);
        $this->validateAddress($mail->getBcc(), 'bcc', false);
        $this->validateAddress($mail->getDispositionNotificationTo(), 'dispositionNotificationTo', false);
        $this->validateAttachments($mail->getAttachments());

        $errors = $this->getErrors();

        return empty($errors);
    }

    /**
     * @param string $subject
     *
     * @return bool
     */
    public function validateSubject($subject)
    {
        if (empty($subject)) {
            $this->addError('subject', 'Subject is empty');

            return false;
        }

        return true;
    }

    /**
     * @param array $sender
     *
     * @return bool
     */
    public function validateSender($sender)
    {
        if (is_null($sender)) {
            $this->addError('sender', 'Sender is null');
            return false;
        }

        if (false === filter_var(key($sender), FILTER_VALIDATE_EMAIL)) {
            $this->addError('sender', sprintf('Sender recipient `%s` must be a valid email address', key($sender)));

            return false;
        }

        return true;
    }

    /**
     * Validate address fields
     *
     * @param array  $address
     * @param string $field
     * @param bool   $isRequired
     *
     * @return bool
     */
    public function validateAddress($address, $field, $isRequired = true)
    {
        if ($isRequired && empty($address)) {
            $this->addError($field, sprintf('%s is empty', ucfirst($field)));
            return false;
        }

        $validator = new EmailValidator();
        $validation = new RFCValidation();

        $success = true;
        foreach ($address as $email => $label) {
            if (!is_scalar($label)) {
                $this->addError(
                    $field,
                    sprintf('Label for %s is not scalar, `%s` given', $field, gettype($label))
                );
                $label = gettype($label);
                $success = false;
            }

            if ($validator->isValid($email, $validation) == false) {
                $this->addError(
                    $field,
                    sprintf('`%s` is not a valid email address for %s `%s`', $email, $field, $label)
                );
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param array $attachments
     *
     * @return bool
     */
    public function validateAttachments(array $attachments)
    {
        $success = true;
        foreach ($attachments as $attachment) {
            if (!isset($attachment['filename']) || empty($attachment['filename'])) {
                $this->addError('attachments', 'Attachment must have a filename');
                $success = false;
            }

            if (!isset($attachment['mime_type']) || empty($attachment['mime_type'])) {
                $this->addError('attachments', 'Attachment must have a MIME type');
                $success = false;
            }

            if (!isset($attachment['contents'])) {
                $this->addError('attachments', 'Attachment must have contents');
                $success = false;
            }
        }

        return $success;
    }
}

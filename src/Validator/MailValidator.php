<?php

    namespace Fei\Service\Mailer\Validator;

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
         * @param Mail $mail
         *
         * @return bool
         */
        public function validate(EntityInterface $mail)
        {

            if(!$mail instanceof Mail)
            {
                throw new Exception('Entity to validate must be an instance of ' . Mail::class);
            }

            $this->clearErrors();

            $this->validateSubject($mail->getSubject());
            $this->validateBody($mail->getTextBody(), $mail->getHtmlBody());
            $this->validateSender($mail->getSender());
            $this->validateAddress($mail->getRecipients(), 'recipients');
            $this->validateAddress($mail->getCc(), 'cc', false);
            $this->validateAddress($mail->getBcc(), 'bcc', false);

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
            if (empty($subject))
            {
                $this->addError('subject', 'Subject is empty');

                return false;
            }

            return true;
        }

        /**
         * @param string $textBody
         * @param string $htmlBody
         *
         * @return bool
         */
        public function validateBody($textBody, $htmlBody)
        {
            if (empty($textBody) && empty($htmlBody))
            {
                $this->addError('body', 'Both text and html bodies are empty');
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
            if (is_null($sender))
            {
                $this->addError('sender', 'Sender is null');
                return false;
            }

            if (false === filter_var(key($sender), FILTER_VALIDATE_EMAIL))
            {
                $this->addError('sender', 'Sender recipient must be a valid email address');

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
            if ($isRequired && empty($address))
            {
                $this->addError($field, sprintf('%s is empty', ucfirst($field)));
                return false;
            }

            $success = true;
            foreach ($address as $email => $label)
            {
                if (!is_scalar($label)) {
                    $this->addError(
                        $field,
                        sprintf('Label for %s is not scalar, `%s` given', $field, gettype($label))
                    );
                    $label = gettype($label);
                    $success = false;
                }

                if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->addError(
                        $field,
                        sprintf('`%s` is not a valid email address for %s `%s`', $email, $field, $label)
                    );
                    $success = false;
                }
            }

            return $success;
        }
    }

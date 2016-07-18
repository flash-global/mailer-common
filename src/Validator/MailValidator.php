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
            $this->validateRecipients($mail->getRecipients());
            
            return empty($this->getErrors());
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
         * @param array $recipients
         *
         * @return bool
         */
        public function validateRecipients($recipients)
        {
            if (empty($recipients))
            {
                $this->addError('recipients', 'Recipients is empty');
                return false;
            }
            
            $success = true;
            foreach ($recipients as $recipient => $label)
            {
                if (false === filter_var($recipient, FILTER_VALIDATE_EMAIL))
                {
                    $this->addError(
                        'recipients',
                        sprintf('`%s` is not a valid email address for recipient `%s`', $recipient, $label)
                    );
                    $success = false;
                }
            }
            
            return $success;
        }
        
        
        
    }

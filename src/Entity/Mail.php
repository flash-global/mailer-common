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
         * @var string
         */
        protected $sender;

        /**
         * @var array
         */
        protected $recipients = array();

        /**
         * @return mixed
         */
        public function getSubject()
        {
            return $this->subject;
        }

        /**
         * @param mixed $subject
         *
         * @return $this
         */
        public function setSubject($subject)
        {
            $this->subject = $subject;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getTextBody()
        {
            return $this->textBody;
        }

        /**
         * @param mixed $textBody
         *
         * @return $this
         */
        public function setTextBody($textBody)
        {
            $this->textBody = $textBody;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getHtmlBody()
        {
            return $this->htmlBody;
        }

        /**
         * @param mixed $htmlBody
         *
         * @return $this
         */
        public function setHtmlBody($htmlBody)
        {
            $this->htmlBody = $htmlBody;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getSender()
        {
            return $this->sender;
        }

        /**
         * @param mixed $sender
         *
         * @return $this
         */
        public function setSender($sender)
        {
            $this->sender = $sender;

            return $this;
        }

        /**
         * @return mixed
         */
        public function getRecipients()
        {
            return $this->recipients;
        }

        /**
         * @param mixed $recipients
         *
         * @return $this
         */
        public function setRecipients(array $recipients)
        {
            $this->clearRecipients();

            foreach ($recipients as $recipient => $label)
            {

                if(is_int($recipient)) $recipient = $label;

                $this->addRecipient($recipient, $label);
            }

            return $this;
        }

        /**
         * @param  string $recipient    Recipient email address
         * @param string $label         Recipient full name (defaults to email address)
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

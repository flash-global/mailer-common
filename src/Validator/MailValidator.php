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
        return empty($this->getErrors());
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

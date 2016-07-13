<?php

namespace Tests\Fei\Service\Mailer\Validator;

use Codeception\Test\Unit;
use Fei\Service\Mailer\Entity\Mail;
use Fei\Service\Mailer\Validator\MailValidator;

class MailValidatorTest extends Unit
{
    public function testValidate()
    {
        $validator = new MailValidator();

        $mail = new Mail();
        $mail->setSubject('Subject')
            ->setHtmlBody('HtmlBody')
            ->setSender('sender@test.com')
            ->setRecipients(['another@test.com' => 'another@test.com', 'other@test.com' => 'Recipient Name']);

        $result = $validator->validate($mail);

        $this->assertTrue($result);
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateSubject()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $validator->validateSubject($mail->getSubject());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(['subject' => ['Subject is empty']], $validator->getErrors());

        $mail->setSubject('Subject');
        $validator->clearErrors();
        $validator->validateSubject($mail->getSubject());
        $this->assertEmpty($validator->getErrors());
    }

    public function testBody()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $validator->validateBody($mail->getTextBody(), $mail->getHtmlBody());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(
            ['textBody' => ['Text body is empty'], 'htmlBody' => ['HTML body is empty']],
            $validator->getErrors()
        );
    }

    public function testValidateSender()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $validator->validateSender($mail->getSender());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(['sender' => ['Sender is null']], $validator->getErrors());

        $mail->setSender('sender@test.com');
        $validator->clearErrors();
        $validator->validateSender($mail->getSender());
        $this->assertEmpty($validator->getErrors());

        $mail->setSender(['sender@test.com' => 'Name']);
        $validator->validateSender($mail->getSender());
        $this->assertEmpty($validator->getErrors());

        $mail->setSender('not a email');
        $validator->validateSender($mail->getSender());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(['sender' => ['Sender recipient must be a valid email address']], $validator->getErrors());
    }

    public function testValidateRecipients()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $validator->validateRecipients($mail->getRecipients());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(['recipients' => ['Recipients is empty']], $validator->getErrors());

        $mail->addRecipient('mail@address.com');
        $validator->clearErrors();
        $validator->validateRecipients($mail->getRecipients());
        $this->assertEmpty($validator->getErrors());

        $mail->addRecipient('not a email address', 'first label');
        $mail->addRecipient('another not a email address', 'second label');
        $validator->validateRecipients($mail->getRecipients());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(
            ['recipients' => [
                '`not a email address` is not a valid email address for recipient `first label`',
                '`another not a email address` is not a valid email address for recipient `second label`'
            ]],
            $validator->getErrors()
        );
    }

    public function testErrorsAccessors()
    {
        $validator = new MailValidator();
        
        $validator->setErrors(['attribute' => 'message']);
        $this->assertAttributeEquals(['attribute' => ['message']], 'errors', $validator);
        $this->assertEquals(['attribute' => ['message']], $validator->getErrors());

        $validator->setErrors(
            ['attribute' => 'message', 'otherAttribute' => ['otherMessage', 'anotherMessage']]
        );
        $this->assertEquals(
            ['attribute' => ['message'], 'otherAttribute' => ['otherMessage', 'anotherMessage']],
            $validator->getErrors()
        );
    }
}

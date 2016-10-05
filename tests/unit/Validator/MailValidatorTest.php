<?php

namespace Tests\Fei\Service\Mailer\Validator;

use Codeception\Test\Unit;
use Fei\Entity\EntityInterface;
use Fei\Entity\Exception;
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
            ->setRecipients(['another@test.com' => 'another@test.com', 'other@test.com' => 'Recipient Name'])
            ->addAttachment(new \SplFileObject(__DIR__ . '/../../_data/php.pdf'));

        $result = $validator->validate($mail);

        $this->assertTrue($result);
        $this->assertEmpty($validator->getErrors());
    }

    public function testValidateException()
    {
        $this->expectException(Exception::class);

        $validator = new MailValidator();

        $validator->validate(
            new class implements EntityInterface
            {
                public function hydrate($data)
                {
                }

                public function toArray()
                {
                }
            }
        );
    }

    public function testValidateSubject()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $result = $validator->validateSubject($mail->getSubject());
        $this->assertFalse($result);
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(['subject' => ['Subject is empty']], $validator->getErrors());

        $mail->setSubject('Subject');
        $validator = new MailValidator();
        $result = $validator->validateSubject($mail->getSubject());
        $this->assertTrue($result);
        $this->assertEmpty($validator->getErrors());
    }

    public function testBodyValidation()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $result = $validator->validateBody($mail->getTextBody(), $mail->getHtmlBody());
        $this->assertFalse($result);
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(
            ['body' => ['Both text and html bodies are empty']],
            $validator->getErrors()
        );
    }

    public function testValidateSender()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $result = $validator->validateSender($mail->getSender());
        $this->assertFalse($result);
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(['sender' => ['Sender is null']], $validator->getErrors());

        $mail->setSender('sender@test.com');
        $validator = new MailValidator();
        $result = $validator->validateSender($mail->getSender());
        $this->assertTrue($result);
        $this->assertEmpty($validator->getErrors());

        $mail->setSender(['sender@test.com' => 'Name']);
        $result = $validator->validateSender($mail->getSender());
        $this->assertTrue($result);
        $this->assertEmpty($validator->getErrors());

        $mail->setSender('not a email');
        $result = $validator->validateSender($mail->getSender());
        $this->assertNotEmpty($validator->getErrors());
        $this->assertFalse($result);
        $this->assertEquals(['sender' => ['Sender recipient must be a valid email address']], $validator->getErrors());
    }

    public function testValidateAddress()
    {
        $validator = new MailValidator();
        $mail = new Mail();

        $result = $validator->validateAddress($mail->getRecipients(), 'recipients');
        $this->assertNotEmpty($validator->getErrors());
        $this->assertFalse($result);
        $this->assertEquals(['recipients' => ['Recipients is empty']], $validator->getErrors());

        $mail->addRecipient('mail@address.com');
        $validator = new MailValidator();
        $result = $validator->validateAddress($mail->getRecipients(), 'recipients');
        $this->assertEmpty($validator->getErrors());
        $this->assertTrue($result);

        $mail->addRecipient('not a email address', 'first label');
        $mail->addRecipient('another not a email address', 'second label');
        $result = $validator->validateAddress($mail->getRecipients(), 'recipients');
        $this->assertNotEmpty($validator->getErrors());
        $this->assertEquals(
            [
                'recipients' => [
                    '`not a email address` is not a valid email address for recipients `first label`',
                    '`another not a email address` is not a valid email address for recipients `second label`'
                ]
            ],
            $validator->getErrors()
        );
        $this->assertFalse($result);

        $mail->setRecipients(['test' => ['test1', 'test2']]);
        $validator = new MailValidator();
        $result = $validator->validateAddress($mail->getRecipients(), 'recipients');

        $this->assertEquals(
            [
                'recipients' => [
                    'Label for recipients is not scalar, `array` given',
                    '`test` is not a valid email address for recipients `array`',
                ]
            ],
            $validator->getErrors()
        );
        $this->assertFalse($result);

        $mail->setRecipients([' mail@mailer.com']);

        $validator = new MailValidator();
        $result = $validator->validateAddress($mail->getRecipients(), 'recipients');

        $this->assertTrue($result);

        $mail->setRecipients(['', 'toto@email.com']);

        $validator = new MailValidator();
        $result = $validator->validateAddress($mail->getRecipients(), 'recipients');

        $this->assertTrue($result);
    }

    public function testValidateAttachments()
    {
        $validator = new MailValidator();

        $this->assertTrue($validator->validateAttachments([]));

        $mail = new Mail();
        $mail->setAttachments([
            new \SplFileObject(__DIR__ . '/../../_data/with-composer.png'),
            new \SplFileObject(__DIR__ . '/../../_data/php.pdf'),
            ['a array']
        ]);

        $this->assertFalse($validator->validateAttachments($mail->getAttachments()));
        $this->assertEquals(
            [
                'attachments' => [
                    'Attachment must have a filename',
                    'Attachment must have a MIME type',
                    'Attachment must have contents',
                ]
            ],
            $validator->getErrors()
        );

        $mail->setAttachments([
            new \SplFileObject(__DIR__ . '/../../_data/with-composer.png'),
            new \SplFileObject(__DIR__ . '/../../_data/php.pdf')
        ]);
        $validator = new MailValidator();
        $this->assertTrue($validator->validateAttachments($mail->getAttachments()));
        $this->assertEmpty($validator->getErrors());
    }
}

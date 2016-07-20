<?php

    namespace Tests\Fei\Service\Mailer\Entity;


    use Codeception\Test\Unit;
    use Fei\Service\Mailer\Entity\Mail;

    class MailTest extends Unit
    {

        public function testSubjectAccessors()
        {
            $mail = new Mail();
            $mail->setSubject('test');

            $this->assertAttributeEquals('test', 'subject', $mail);
            $this->assertEquals('test', $mail->getSubject());
        }

        public function testTextBodyAccessors()
        {
            $mail = new Mail();
            $mail->setTextBody('test');

            $this->assertAttributeEquals('test', 'textBody', $mail);
            $this->assertEquals('test', $mail->getTextBody());
        }

        public function testHtmlBodyAccessors()
        {
            $mail = new Mail();
            $mail->setHtmlBody('test');

            $this->assertAttributeEquals('test', 'htmlBody', $mail);
            $this->assertEquals('test', $mail->getHtmlBody());
        }

        public function testSenderAccessors()
        {
            $mail = new Mail();
            $mail->setSender('test');

            $this->assertAttributeEquals(['test' => 'test'], 'sender', $mail);
            $this->assertEquals(['test' => 'test'], $mail->getSender());

            $mail->setSender('email@email.com');
            $this->assertEquals(['email@email.com' => 'email@email.com'], $mail->getSender());

            $mail->setSender(['email@email.com']);
            $this->assertEquals(['email@email.com' => 'email@email.com'], $mail->getSender());

            $mail->setSender(['email@email.com' => 'Name']);
            $this->assertEquals(['email@email.com' => 'Name'], $mail->getSender());
        }

        public function testRecipientsAccessors()
        {
            $mail = new Mail();

            $mail->setRecipients(['test@test.com']);
            $this->assertAttributeEquals(['test@test.com' => 'test@test.com'], 'recipients', $mail);
            $this->assertEquals(['test@test.com' => 'test@test.com'], $mail->getRecipients());

            $mail->setRecipients(['another@test.com', 'other@test.com' => 'Recipient Name']);
            $this->assertEquals(['another@test.com' => 'another@test.com', 'other@test.com' => 'Recipient Name'], $mail->getRecipients());
        }

        public function testCcAccessors()
        {
            $mail = new Mail();

            $mail->setCc(['test@test.com']);
            $this->assertAttributeEquals(['test@test.com' => 'test@test.com'], 'cc', $mail);
            $this->assertEquals(['test@test.com' => 'test@test.com'], $mail->getCc());

            $mail->setCc(['another@test.com', 'other@test.com' => 'Recipient Name']);
            $this->assertEquals(['another@test.com' => 'another@test.com', 'other@test.com' => 'Recipient Name'], $mail->getCc());
        }

        public function testBccAccessors()
        {
            $mail = new Mail();

            $mail->setBcc(['test@test.com']);
            $this->assertAttributeEquals(['test@test.com' => 'test@test.com'], 'bcc', $mail);
            $this->assertEquals(['test@test.com' => 'test@test.com'], $mail->getBcc());

            $mail->setBcc(['another@test.com', 'other@test.com' => 'Recipient Name']);
            $this->assertEquals(['another@test.com' => 'another@test.com', 'other@test.com' => 'Recipient Name'], $mail->getBcc());
        }
    }

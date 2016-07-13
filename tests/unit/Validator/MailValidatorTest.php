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

        $result = $validator->validate(new Mail);

        $this->assertTrue($result);
        $this->assertEmpty($validator->getErrors());
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

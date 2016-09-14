<?php

namespace Tests\Fei\Service\Mailer\Entity;

use Codeception\Test\Unit;
use Fei\Service\Mailer\Entity\Attachment;

class AttachmentTest extends Unit
{
    public function testAttachmentFilenameAccessors()
    {
        $attachment = new Attachment(__DIR__ . '/../../_data/dump.sql');

        $this->assertEquals($attachment->getFilename(), $attachment->getAttachmentFilename());
        $this->assertAttributeEquals($attachment->getAttachmentFilename(), 'attachmentFilename', $attachment);

        $attachment->setAttachmentFilename('test.sql');

        $this->assertEquals('test.sql', $attachment->getAttachmentFilename());
        $this->assertAttributeEquals($attachment->getAttachmentFilename(), 'attachmentFilename', $attachment);
    }

    public function testMimeTypeAccessors()
    {
        $attachment = new Attachment(__DIR__ . '/../../_data/dump.sql');

        $this->assertEquals('text/plain', $attachment->getMimeType());

        $attachment->setMimeType('application/pdf');

        $this->assertEquals('application/pdf', $attachment->getMimeType());
        $this->assertAttributeEquals($attachment->getMimeType(), 'mimeType', $attachment);
    }
}

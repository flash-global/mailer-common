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

    public function testIsEmbeddedAccessors()
    {
        $attachment = new Attachment(__DIR__ . '/../../_data/dump.sql');

        $this->assertFalse($attachment->getIsEmbedded());

        $attachment->setIsEmbedded(true);
        $this->assertEquals(true, $attachment->getIsEmbedded());
        $this->assertAttributeEquals($attachment->getIsEmbedded(), 'isEmbedded', $attachment);
    }

    public function testIdAccessors()
    {
        $attachment = new Attachment(__DIR__ . '/../../_data/dump.sql');

        $this->assertRegExp('/^(.{32})@mailer.generated$/', $attachment->getId());

        $attachment->setId('test');
        $this->assertEquals('test', $attachment->getId());
        $this->assertAttributeEquals($attachment->getId(), 'id', $attachment);

        $this->assertEquals('cid:' . $attachment->getId(), $attachment->getCid());
    }
}

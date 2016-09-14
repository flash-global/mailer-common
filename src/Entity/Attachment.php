<?php

namespace Fei\Service\Mailer\Entity;

class Attachment extends \SplFileObject
{
    /**
     * @var string
     */
    protected $attachmentFilename;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * Attachment constructor.
     *
     * @param string $file_name
     */
    public function __construct($file_name)
    {
        parent::__construct($file_name);

        $this->setAttachmentFilename($this->getBasename());
    }

    /**
     * Get attachment filename
     *
     * @return string
     */
    public function getAttachmentFilename()
    {
        return $this->attachmentFilename;
    }

    /**
     * Set attachment filename
     *
     * @param string $attachmentFilename
     *
     * @return $this
     */
    public function setAttachmentFilename($attachmentFilename)
    {
        $this->attachmentFilename = $attachmentFilename;

        return $this;
    }

    /**
     * Get Mime Type
     *
     * @return string
     */
    public function getMimeType()
    {
        if (empty($this->mimeType)) {
            $resource = finfo_open(FILEINFO_MIME_TYPE);
            $this->mimeType = finfo_file($resource, $this->getRealPath());
        }

        return $this->mimeType;
    }

    /**
     * Set Mime Type
     *
     * @param string $mimeType
     *
     * @return $this
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }
}

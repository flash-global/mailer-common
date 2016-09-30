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
     * @var bool
     */
    protected $isEmbedded = false;

    /**
     * @var string
     */
    protected $id;

    /**
     * Attachment constructor.
     *
     * @param string $filename
     * @param bool   $isEmbedded
     */
    public function __construct($filename, $isEmbedded = false)
    {
        parent::__construct($filename);

        $this->setAttachmentFilename($this->getBasename());
        $this->setIsEmbedded($isEmbedded);
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
     * Tells if attachment is embedded
     *
     * @return boolean
     */
    public function getIsEmbedded()
    {
        return $this->isEmbedded;
    }

    /**
     * Set if attachment is embedded
     *
     * @param boolean $isEmbedded
     */
    public function setIsEmbedded($isEmbedded)
    {
        $this->isEmbedded = $isEmbedded;
    }

    /**
     * Get attachment ID
     *
     * @return string
     */
    public function getId()
    {
        if (is_null($this->id)) {
            $this->id = md5(getmypid().'.'.time().'.'.uniqid(mt_rand(), true)) . '@mailer.generated';
        }

        return $this->id;
    }

    /**
     * Set attachment ID
     *
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get attachment CID
     *
     * @return string
     */
    public function getCid()
    {
        return 'cid:' . $this->getId();
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

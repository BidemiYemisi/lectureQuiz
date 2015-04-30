<?php

namespace Quiz\LectureQuizBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class Photo{



    /**
     * @var integer
     */
    private $id;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    private $temp;
    private static $uploadDir = 'web/img/quiz';
    private static $uploadRootDir = '%kernel.root_dir%/../web/img/quiz';



    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null == $this->getFile()) {
           // throw new \RuntimeException("Trying to upload non-existent file");
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(self::$uploadRootDir . '/' . $this->id,
            $this->id . '.original.jpg');

        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->id
            ? null
            : self::$uploadRootDir . '/' . $this->id . '/' . $this->id . '.original.jpg';
    }

    public function getFileRootPath()
    {
        return null === $this->id
            ? null
            : self::$uploadRootDir . '/' . $this->id;
    }

    public function getWebPath($type = 'original')
    {
        return null === $this->id
            ? null
            : self::$uploadDir . '/' . $this->id . '/' . $this->id . '.' . $type . '.jpg';
    }

    public function getResizedWebPath(){return $this->getWebPath('resized');}

}

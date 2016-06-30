<?php

namespace CMS\Bundle\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="CMS\Bundle\MediaBundle\Entity\Repository\MediaRepository")
 */
class Media implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var array
     *
     * @ORM\Column(name="metas", type="array", nullable=true)
     */
    private $metas;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    public function getAbsolutePath() {
    return null === $this->path ? null : $this->getUploadRootDir() . $this->path;

    return null;
  }



    public function getWebPath() {
    if ( isset( $this->path ) ) {
      return ( null === $this->path ) ? null : $this->getUploadDir() . $this->path;
    }

    return null;
  }

  public function getWebPathList() {
    if ( isset( $this->path ) ) {
      return ( null === $this->path ) ? null : '/uploads/square/' . basename($this->path);
    }

    return null;
  }

  protected function getUploadRootDir() {
    // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
    return __DIR__ . '/../../../../../web' . $this->getUploadDir();
  }

  protected function getUploadDir() {
    // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
    // le document/image dans la vue.
    return '/uploads/thumbs/';
  }

  /**
   * @ORM\PrePersist()
   * @ORM\PreUpdate()
   */
  public function preUpload() {
    if ( null !== $this->getFile() ) {
      // do whatever you want to generate a unique name
      $filename   = sha1( uniqid( mt_rand(), true ) );
      $this->path = date( 'Y' ) . '/' . date( 'm' ) . '/' . $filename . '.' . $this->getFile()->guessExtension();
    }
  }

  /**
   * @ORM\PostPersist()
   * @ORM\PostUpdate()
   */
  public function upload() {
    if ( null === $this->getFile() ) {
      return;
    }

    // if there is an error when moving the file, an exception will
    // be automatically thrown by move(). This will properly prevent
    // the entity from being persisted to the database on error
    $this->getFile()->move( $this->getUploadRootDir() . date( 'Y' ) . '/' . date( 'm' ), $this->path );

    // check if we have an old image
    if ( isset( $this->temp ) ) {
      // delete the old image
      unlink( $this->getUploadRootDir() . '/' . $this->temp );
      // clear the temp image path
      $this->temp = null;
    }
    $this->file = null;
  }

  /**
   * @ORM\PostRemove()
   */
  public function removeUpload() {
    $file = $this->getAbsolutePath();
    if ( $file ) {
      unlink( $file );
    }
  }

  /**
   * Sets file.
   *
   * @param UploadedFile $file
   */
  public function setFile( UploadedFile $file = null ) {
    $this->file = $file;
    // check if we have an old image path
    if ( isset( $this->path ) ) {
      // store the old name to delete after the update
      $this->temp = $this->path;
      $this->path = null;
    } else {
      $this->path = 'initial';
    }
  }

  /**
   * Get file.
   *
   * @return UploadedFile
   */
  public function getFile() {
    return $this->file;
  }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return Media
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Set metas
     *
     * @param array $metas
     *
     * @return Media
     */
    public function setMetas($metas)
    {
        $this->metas = $metas;

        return $this;
    }

    /**
     * Get metas
     *
     * @return array
     */
    public function getMetas()
    {
        return $this->metas;
    }

    public function JsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}


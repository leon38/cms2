<?php
namespace CMS\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="CMS\Bundle\CoreBundle\Entity\Repository\LanguageRepository")
 * @ORM\Table(name="language")
 */
class Language
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(name="code_local", type="string", length=5)
     */
    private $code_local;

    /**
     * @ORM\Column(name="code_lang", type="string", length=5)
     */
    private $code_lang;

    /**
     * @ORM\Column(name="sens_ecriture", type="string", length=255)
     */
    private $sens_ecriture = 'ltr';

    /**
     * @ORM\Column(name="ordre", type="integer", length=2)
     */
    private $ordre = 0;

    /**
     * @ORM\Column(name="default_lang", type="boolean")
     */
     private $default = false;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Language
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code_local
     *
     * @param string $codeLocal
     * @return Language
     */
    public function setCodeLocal($codeLocal)
    {
        $this->code_local = $codeLocal;

        return $this;
    }

    /**
     * Get code_local
     *
     * @return string
     */
    public function getCodeLocal()
    {
        return $this->code_local;
    }

    /**
     * Set code_lang
     *
     * @param string $codeLang
     * @return Language
     */
    public function setCodeLang($codeLang)
    {
        $this->code_lang = $codeLang;

        return $this;
    }

    /**
     * Get code_lang
     *
     * @return string
     */
    public function getCodeLang()
    {
        return $this->code_lang;
    }

    /**
     * Set sens_ecriture
     *
     * @param string $sensEcriture
     * @return Language
     */
    public function setSensEcriture($sensEcriture)
    {
        $this->sens_ecriture = $sensEcriture;

        return $this;
    }

    /**
     * Get sens_ecriture
     *
     * @return string
     */
    public function getSensEcriture()
    {
        return $this->sens_ecriture;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Language
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    public function __toString()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
        $this->contents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set default
     *
     * @param boolean $default
     * @return Language
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return boolean
     */
    public function getDefault()
    {
        return $this->default;
    }
}

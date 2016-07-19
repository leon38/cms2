<?php

namespace CMS\Bundle\ContentBundle\Manager;

use CMS\ Bundle\ContentBundle\Entity\Content;
use Doctrine\ORM\EntityManager;
use CMS\Bundle\ContentBundle\Entity\FieldValue;

class ContentManager
{
  
  private $em;
  
  public function __construct(EntityManager $em)
  {
    $this->em = $em;
  }
  
  
  /**
   * Sauvegarde les catégories et les valeurs des métas
   * @param  Content $content Contenu à sauvegarder
   * @return boolean
   */
  public function save(Content $content)
  {
    
    $content->setTaxonomy($content->getTaxonomy());
    $content->setReferenceContent($content);
    if (empty($content->getThumbnail())) {
      $content->setThumbnail(null);
    }
    
   
    if (!empty($content->getFieldValuesTemp())) {
      foreach ($content->getFieldValuesTemp() as $fieldname => $value) {
        $field = $this->em->getRepository('ContentBundle:Field')->findOneBy(array('name' => $fieldname));
        $fieldvalue = $this->em->getRepository('ContentBundle:FieldValue')->findOneBy(array('content' => $content, 'field' => $field));
        if ($fieldvalue !== null) {
          $fieldvalue->setValue($value);
        } else {
          $fieldvalue = new FieldValue();
          $fieldvalue->setContent($content);
          $fieldvalue->setField($field);
          $fieldvalue->setValue($value);
          $content->addFieldValue($fieldvalue);
        }
        $this->em->persist($fieldvalue);
        $this->em->flush();
      }
    }
  
    
    if (!empty($content->getMetaValuesTemp())) {
      foreach ($content->getMetaValuesTemp() as $metavalue) {
        $metavalue->setContent($content);
        $content->addMetavalue($metavalue);
        $this->em->persist($metavalue);
        $this->em->flush();
      }
    }
    $this->em->persist($content);
    $this->em->flush();
    
    return true;
  }
  
  public function update(Content $content)
  {
    if (empty($content->getThumbnail())) {
      $content->setThumbnail('');
    }
    $this->em->persist($content);
    $this->em->flush();
  }
}
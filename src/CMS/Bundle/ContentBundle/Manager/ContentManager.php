<?php

namespace CMS\Bundle\ContentBundle\Manager;

use CMS\ Bundle\ContentBundle\Entity\Content;
use Doctrine\ORM\EntityManager;

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
            $content->setThumbnail('');
        }
        $this->em->persist($content);
        $this->em->flush();

        if (!empty($content->getFieldValuesTemp())) {
            foreach($content->getFieldValuesTemp() as $fieldvalue) {
                $fieldvalue->setContent($content);
                $content->addFieldvalue($fieldvalue);
                $this->em->persist($fieldvalue);
            }
        }

        if (!empty($content->getMetaValuesTemp())) {
            foreach($content->getMetaValuesTemp() as $metavalue) {
                $metavalue->setContent($content);
                $content->addMetavalue($metavalue);
                $this->em->persist($metavalue);
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
<?php
/**
 * User: DCA
 * Date: 19/08/2016
 * Time: 09:01
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;
use CMS\Bundle\ContentBundle\Form\Type\GalleryType;

class GalleryField extends Fields
{
    
    public function getTypeField()
    {
        return GalleryType::class;
    }
    
    public function getName()
    {
        return 'Champ galerie photo';
    }
    
    public function getClassName()
    {
        return 'GalleryField';
    }
    
    public function display()
    {
        return $this->templating->render('ContentBundle:Fields:gallery.html.twig', array('params' => $this->params));
    }
    
}
<?php
/**
 * User: DCA
 * Date: 26/09/2016
 * Time: 10:33
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class KMLField extends Fields
{
    
    public function getTypeField()
    {
        return FileType::class;
    }
    
    public function getName()
    {
        return 'Fichier KML';
    }
    
    public function getClassName()
    {
        return 'KMLField';
    }
}
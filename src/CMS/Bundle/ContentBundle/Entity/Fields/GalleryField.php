<?php
/**
 * User: DCA
 * Date: 19/08/2016
 * Time: 09:01
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;

class GalleryField extends Fields
{
    /**
     * @var string Type du champ
     */
    private $type;
    
    /**
     * @var array paramètres du champs
     */
    private $params;
    
    
    public function __construct()
    {
        
        $this->type = $this->getTypeField();
        $this->params = array();
    }
    
    public function getTypeField()
    {
        return 'gallery';
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
    
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    
    public function getOptions()
    {
        return array();
    }
    
    public function getParams()
    {
        return $this->params;
    }
    
}
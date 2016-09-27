<?php
/**
 * User: DCA
 * Date: 26/09/2016
 * Time: 10:33
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


class KMLField
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
        return 'kml';
    }
    
    public function getName()
    {
        return 'Fichier KML';
    }
    
    public function getClassName()
    {
        return 'KMLField';
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
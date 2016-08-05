<?php
/**
 * User: DCA
 * Date: 04/08/2016
 * Time: 17:21
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


class WeatherField
{
    
    // Requête : select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="Grenoble, France")
    
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
        return 'weather';
    }
    
    public function getName()
    {
        return 'Champ météo';
    }
    
    public function getClassName()
    {
        return 'WeatherField';
    }
    
    public function display()
    {
        return $this->templating->render('ContentBundle:Fields:weather.html.twig', array('params' => $this->params));
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
<?php
/**
 * User: DCA
 * Date: 08/08/2016
 * Time: 17:04
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;

class MusicField extends Fields
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
        return 'music';
    }
    
    public function getName()
    {
        return 'Musique';
    }
    
    public function getClassName()
    {
        return 'MusicField';
    }
    
    public function display()
    {
        return $this->templating->render('ContentBundle:Fields:music.html.twig', array('params' => $this->params));
    }
    
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    
    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $options['api'] = array('type' => 'choice', 'name' => 'api', 'value' => $this->getParamsValue($this->params, 'api'), 'choices' => array('deezer' => 'Deezer', 'spotify' => 'spotify'));
        return $options;
    }
    
    public function getParams()
    {
        return $this->params;
    }
}
<?php
/**
 * User: DCA
 * Date: 08/08/2016
 * Time: 17:04
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MusicField extends Fields
{
    
    public function __construct()
    {
        
        $this->type = $this->getTypeField();
        $this->params = array();
    }
    
    public function getTypeField()
    {
        return TextType::class;
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
    
    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $this->options['api'] = array('type' => 'choice', 'name' => 'api', 'value' => $this->getParamsValue($this->params, 'api'), 'choices' => array('deezer' => 'Deezer', 'spotify' => 'spotify'));
        return $this->options;
    }
}
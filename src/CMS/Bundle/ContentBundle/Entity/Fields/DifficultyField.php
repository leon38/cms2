<?php
/**
 * User: DCA
 * Date: 13/10/2016
 * Time: 14:26
 * cms2
 */

namespace CMS\Bundle\ContentBundle\Entity\Fields;


use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DifficultyField extends Fields
{

    
    
    public function __construct()
    {
        
        $this->type = $this->getTypeField();
        $this->params = array('attr' => array('max' => 5, 'min' => 0, 'step' => 0.5, 'class' => 'rating'));
    }
    
    public function getTypeField()
    {
        return TextType::class;
    }
    
    public function getName()
    {
        return 'Difficulty';
    }
    
    public function getClassName()
    {
        return 'DifficultyField';
    }
    
    
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }
    
    public function getOptions()
    {
        $this->options['max'] = array('type' => TextType::class, 'name' => 'max', 'value' => $this->getParamsValue($this->params, 'max'));
        return $this->options;
    }
}
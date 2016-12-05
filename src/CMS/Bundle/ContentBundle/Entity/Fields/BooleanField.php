<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\BooleanField
 *
 */
class BooleanField extends Fields
{

    public function __construct()
    {
        $this->type = $this->getTypeField();
        $this->params = array();
    }

    public function getTypeField()
    {
        return CheckboxType::class;
    }

    public function getName()
    {
        return 'Oui / Non';
    }

    public function getClassName()
    {
        return 'BooleanField';
    }
    
    /**
     * @return array
     */
    public function getOptions()
    {
        $this->options['Options'] = array('type' => 'textarea' ,'name' => 'options', 'value' => $this->getParamsValue($this->params, 'options'));
        $this->options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));
        $this->options['Multiple'] = array('type' => 'choice', 'name' => 'multiple','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'multiple'));
        return $this->options;
    }
}
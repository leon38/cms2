<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\SelectField
 *
 */
class SelectField extends Fields
{
    

    public function getTypeField()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return 'Liste déroulante';
    }

    public function getClassName()
    {
        return 'SelectField';
    }

    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $this->options['Options'] = array('type' => 'textarea' ,'name' => 'options', 'value' => $this->getParamsValue($this->params, 'options'));
        $this->options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));
        $this->options['Multiple'] = array('type' => 'choice', 'name' => 'multiple','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'multiple'));
        return $this->options;
    }
}
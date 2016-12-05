<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextAreaField
 *
 */
class TextAreaField extends Fields
{

    public function getTypeField()
    {
        return TextareaType::class;
    }

    public function getName()
    {
        return 'Zone de texte';
    }

    public function getClassName()
    {
        return 'TextAreaField';
    }

    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $this->options['Colonne'] = array('type' => 'text', 'name' => 'cols', 'value' => $this->getParamsValue($this->params, 'cols'));
        $this->options['Ligne'] = array('type' => 'text', 'name' => 'rows', 'value' => $this->getParamsValue($this->params, 'rows'));
        $this->options['Default Value'] = array('type' => 'text', 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'cols'));
        $this->options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));
        $this->options['Editeur'] = array('type' => 'choice', 'name' => 'editor','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));

        return $this->options;
    }
}
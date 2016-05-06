<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\SelectField
 *
 */
class SelectField extends Fields
{
    private $html;

    private $type;

    private $params;

    public function __construct()
    {
        $this->type = $this->getTypeField();
        $this->params = array();
    }

    public function getTypeField()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'Select field type';
    }

    public function getClassName()
    {
        return 'SelectField';
    }

    public function displayfield($field, $value=null)
    {
        $values = $this->getOptionsSelect($value);
        $options = $field->getField()->getParams();
        $html = '<div class="control-group"><div class="control-label">'.$field->getTitle().'</div>';
        if ($options['multiple']) {
            $html .= '<div class="controls"><select name="'.$field->getName().'[]" multiple>'.$values.'</select></div></div>';
        } else {
            $html .= '<div class="controls"><select name="'.$field->getName().'">'.$values.'</select></div></div>';
        }

        return $html;
    }

    private function getOptionsSelect($value)
    {
        $options = $this->params['options'];
        $multiple = is_array($value);
        $options = explode('%%', $options);
        $html = '';
        //var_dump($options); die;
        foreach ($options as $key => $option) {
            $option = explode('::', trim($option));
            if ($multiple) {
                $isselected = (in_array($option[0],$value))?'selected="selected"':'';
            } else {
                $isselected = ($option[0]==$value)?'selected="selected"':'';
            }
            $html .= '<option value="'.$option[0].'" '.$isselected.'>'.html_entity_decode($option[1]).'</option>';
        }

        return $html;
    }

    public function display($value)
    {
        $start = null;
        $end = null;

        switch ($this->params['displaytype']) {
            case 'div':
                $start = '<div>';
                $end = '</div>';
            break;
            case 'span':
                $start = '<span>';
                $end = '</span>';
            break;
            case 'p':
                $start = '<p>';
                $end = '</p>';
            break;
        }

        $html = $start.' '.$value.' '.$end;

        return $html;
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getParams() {
        return $this->params;
    }

    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $options['Options'] = array('type' => 'textarea' ,'name' => 'options', 'value' => $this->getParamsValue($this->params, 'options'));
        $options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));
        $options['Multiple'] = array('type' => 'choice', 'name' => 'multiple','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'multiple'));
        return $options;
    }
}
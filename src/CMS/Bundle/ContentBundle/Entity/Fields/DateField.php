<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\Dateield
 *
 */
class DateField extends Fields
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
        return 'Date';
    }

    public function getName()
    {
        return 'Date field type';
    }

    public function getClassName()
    {
        return 'DateField';
    }

    public function displayfield($field, $value=null)
    {
        $html = '<div class="control-group"><div class="control-label">'.$field->getTitle().'</div>';
        $html .= '<div class="controls"><input type="text" name="'.$field->getName().'" value="'.$value.'" size="'.$this->getParamsValue($this->params, 'size').'" class="datepicker-field" /></div></div>';

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

    // faire une classe avec les display des differents types: checkbox, select, input etc.
    public function getOptions()
    {
        $options['Size'] = array('type' => 'text' ,'name' => 'size', 'value' => $this->getParamsValue($this->params, 'size'));
        //$options['Default Value'] = array('type' => 'text', 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'size'));
        $options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));
        $options['Format de la date'] = array('type' => 'text' ,'name' => 'size', 'value' => $this->getParamsValue($this->params, 'format'));

        return $options;
    }
}
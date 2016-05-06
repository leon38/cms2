<?php
namespace CMS\Bundle\ContentBundle\Entity\Fields;
use CMS\Bundle\ContentBundle\Classes\Fields;
/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextField
 *
 */
class TextField extends Fields
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
        return 'text';
    }
    public function getName()
    {
        return 'Text field type';
    }
    public function getClassName()
    {
        return 'TextField';
    }
    public function displayfield($field, $value=null)
    {
        $html = '<div class="form-group">';
        if(!is_array($value))
            $html .= '<input type="text" class="form-control" name="'.$field->getName().'" value="'.htmlentities($value).'" size="'.$this->getParamsValue($this->params, 'size').'" />';
        else
            $html .= '<input type="text" class="form-control" name="'.$field->getName().'" value="'.implode(' ',$value).'" size="'.$this->getParamsValue($this->params, 'size').'" />';
        $html .= '<label class="control-label">'.$field->getTitle().'</label>';
        $html .= '</div>';
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
            default:
                $start = '';
                $end = '';
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
        $options['Default Value'] = array('type' => 'text', 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'size'));
        $options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));

        return $options;
    }
}
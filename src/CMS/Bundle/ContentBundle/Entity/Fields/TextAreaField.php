<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextAreaField
 *
 */
class TextAreaField extends Fields
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
        return 'textarea';
    }

    public function getName()
    {
        return 'Textarea field type';
    }

    public function getClassName()
    {
        return 'TextAreaField';
    }

    public function displayfield($field, $value=null)
    {
        $useeditor = $this->params['useeditor'];
        $html = '<div class="control-group"><div class="control-label">'.$field->getTitle().'</div>';
        $name = $field->getName();
        /*if ($useeditor) {
            $name = "ckeditor_".$field->getName();
            $html .= '
                    <script type="text/javascript" src="/ContentBundle/web/bundles/trsteelckeditor/ckeditor.js"></script>
                    <script type="text/javascript">
                        CKEDITOR.replace("'.$name.'",{width: "100%",height: "320",language: "en-au",uiColor: "#fff",toolbar: [{"name":"document","items":["Source","-","Save","-","Templates"]},{"name":"basicstyles","items":["Bold","Italic","Underline","Strike","Subscript","Superscript","-","RemoveFormat"]}]});
                    </script>
                    ';
        }*/
        $html .= '<div class="controls"><textarea id="'.$name.'" name="'.$name.'" rows="'.$this->getParamsValue($this->params, 'rows').'" cols="'.$this->getParamsValue($this->params, 'cols').'">'.$value.'</textarea></div></div>';

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
        $options['Colonne'] = array('type' => 'text', 'name' => 'cols', 'value' => $this->getParamsValue($this->params, 'cols'));
        $options['Ligne'] = array('type' => 'text', 'name' => 'rows', 'value' => $this->getParamsValue($this->params, 'rows'));
        $options['Default Value'] = array('type' => 'text', 'name' => 'defaultvalue', 'value' => $this->getParamsValue($this->params, 'cols'));
        $options['Required'] = array('type' => 'choice', 'name' => 'required','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));
        $options['Editeur'] = array('type' => 'choice', 'name' => 'editor','choices' => array(0 => 'No', 1 => 'Yes'), 'value' => $this->getParamsValue($this->params, 'required'));

        return $options;
    }
}
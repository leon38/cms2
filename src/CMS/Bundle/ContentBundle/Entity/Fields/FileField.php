<?php

namespace CMS\Bundle\ContentBundle\Entity\Fields;

use CMS\Bundle\ContentBundle\Classes\Fields;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * CMS\Bundle\ContentBundle\Entity\Fields\TextField
 *
 */
class FileField extends Fields
{

	public function getTypeField()
    {
        return FileType::class;
    }

    public function getName()
    {
        return 'Fichier';
    }

    public function getClassName()
    {
        return 'FileField';
    }

    public function displayfield($field, $value=null)
    {
    	$html = '';
        if($value != '') {
            $html = '<div class="control-group"><div class="control-label"></div>';
            $html .= '<div class="controls"><a href="'.$value.'" target="_blank"><i class="li_note"></i></a></div></div>';
        }
        $html .= '<div class="control-group"><div class="control-label">'.$field->getTitle().'</div>';
        $html .= '<div class="controls"><input type="file" name="'.$field->getName().'" /></div></div>';

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
        $html = '';
        if($value != '')
            $html = $start.'<a href="'.$value.'" target="_blank"><i class="li_note"></i></a>'.$end;

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
    	return array();
    }
    
    public function getParams()
    {
        return $this->params;
    }
}
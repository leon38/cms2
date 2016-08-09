<?php
namespace CMS\Bundle\ContentBundle\Classes;

use Symfony\Component\Templating\EngineInterface;

class Fields
{
  
    protected $templating;
  
  
    public function getParamsValue($params, $name, $type="default", $option=null)
    {
        switch ($type) {
            case 'default':
                if (is_array($params) && array_key_exists($name, $params)) {
                    return $params[$name];
                } else {
                    return '';
                }
                break;
            case 'select':
                if (is_array($params) && array_key_exists($name, $params)) {
                    if($option == $params[$name])
                        return ' selected="selected" ';
                    else
                        return '';
                } else {
                    return '';
                }
                break;
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
    
    public function setTemplating(EngineInterface $templating) {
      $this->templating = $templating;
    }
}
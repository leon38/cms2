<?php
namespace CMS\Bundle\ContentBundle\Classes;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Templating\EngineInterface;

class Fields
{
    /**
     * @var EngineInterface $templating
     */
    protected $templating;

    /**
     * @var array $params
     */
    protected $params;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * @var string $type
     */
    protected $type;

    public function __construct()
    {
        $this->type = TextType::class;
        $this->params = array();
    }



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

    public function getOptions()
    {
        return $this->options;
    }

    public function setTemplating(EngineInterface $templating) {
      $this->templating = $templating;
    }

    /**
     * @return string
     */
    public function getType()
    {
        $short_name = (new \ReflectionClass($this))->getShortName();
        return strtolower(str_replace('Field', '', $short_name));
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }


}
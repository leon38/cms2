<?php
/**
 * User: DCA
 * Date: 05/08/2016
 * Time: 11:50
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;


use Symfony\Component\Templating\EngineInterface;

class Widget
{
    
    private $_templating;
    
    private $_params;
    
    private $_name;
    
    public function __construct(EngineInterface $templating)
    {
        $this->_templating = $templating;
        $this->_params = array();
    }
    
    public function setName($name)
    {
        $this->_name = $name;
    }
    
    public function setParams($params)
    {
        $this->_params = $params;
    }
    
    public function display()
    {
        return $this->_templating->render('CoreBundle:Widget:'.$this->_name.'.html.twig');
    }
    
}
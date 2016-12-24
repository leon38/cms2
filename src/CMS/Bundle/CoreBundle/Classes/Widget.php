<?php
/**
 * User: DCA
 * Date: 05/08/2016
 * Time: 11:50
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Dump\Container;
use Symfony\Component\Templating\EngineInterface;

class Widget
{
    
    private $_templating;
    
    private $_em;
    
    private $_params;
    
    private $_container;
    
    public function __construct(EngineInterface $templating, EntityManager $em, ContainerInterface $container)
    {
        $this->_templating = $templating;
        $this->_em = $em;
        $this->_params = array();
        $this->_container = $container;
    }
    
    public function getTemplating()
    {
        return $this->_templating;
    }
    
    public function setTemplating($templating)
    {
        $this->_templating = $templating;
    }
    
    public function getEntityManager()
    {
        return $this->_em;
    }
    
    public function setEntityManager($em)
    {
        $this->_em = $em;
    }
    
    public function getName()
    {
        return get_class($this);
    }
    
    public function setName($name)
    {
        $this->_name = $name;
    }
    
    public function setParams($params)
    {
        $this->_params = $params;
    }
    
    public function getParams()
    {
        return $this->_params;
    }
    
    public function getContainer()
    {
        return $this->_container;
    }
    
    public function setContainer($container)
    {
        $this->_container = $container;
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
            case 'choice':
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
    
    public function getOptions()
    {
        return array();
    }
    
    public function addScript($path)
    {
        $session = $this->getContainer()->get('session');
        $scripts = $session->get('scripts');
        $scripts[] = $path;
        $scripts = array_unique($scripts, SORT_STRING);
        $session->set('scripts', $scripts);
    }
    
}
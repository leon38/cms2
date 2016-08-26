<?php
/**
 * User: DCA
 * Date: 18/08/2016
 * Time: 15:37
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Classes;

use CMS\Bundle\CoreBundle\CoreBundle;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

class WidgetFactory
{
    private $_templating;
    
    private $_em;
    
    private $_container;
    
    public function __construct(EngineInterface $templating, EntityManager $em, ContainerInterface $container)
    {
        $this->_templating = $templating;
        $this->_em = $em;
        $this->_container = $container;
    }
    
    public function createWidget($nameClass)
    {
        $nameClass = 'CMS\Bundle\CoreBundle\Classes\Widgets\\'.$nameClass;
        $widget = new $nameClass($this->_templating, $this->_em, $this->_container);
        return $widget;
    }
}
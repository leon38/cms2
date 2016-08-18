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
use Symfony\Component\Templating\EngineInterface;

class WidgetFactory
{
    private $_templating;
    
    private $_em;
    
    public function __construct(EngineInterface $templating, EntityManager $em)
    {
        $this->_templating = $templating;
        $this->_em = $em;
    }
    
    public function createWidget($nameClass)
    {
        $nameClass = 'CMS\Bundle\CoreBundle\Classes\Widgets\\'.$nameClass;
        $widget = new $nameClass($this->_templating, $this->_em);
        return $widget;
    }
}
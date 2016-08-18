<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

use CMS\Bundle\CoreBundle\Entity\Repository\WidgetRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;

class WidgetExtension extends \Twig_Extension
{
    private $_widgetRepo;
    private $container;
    
    public function __construct(WidgetRepository $widgetRepo, ContainerInterface $container)
    {
        $this->_widgetRepo = $widgetRepo;
        $this->container = $container;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('widget', array($this, 'renderWidget'), array('is_safe' => array('html'))),
        );
    }
    
    public function renderWidget($name, $param1)
    {
        $widgetEntity = $this->_widgetRepo->findOneBy(array('name' => $name));
        $widget = $widgetEntity->getWidget();
        $widget->setTemplating($this->container->get('templating'));
        $widget->setEntityManager($this->container->get('doctrine')->getManager());
        return $widget->display(array('content' => $param1, 'title' => $widgetEntity->getTitle()));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'widget';
    }
}

<?php
namespace CMS\Bundle\MenuBundle\Twig;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RenderMenuExtension extends \Twig_Extension
{
    private $_menuTaxRepository;
    private $_container;
    
    public function __construct(EntityRepository $repository, ContainerInterface $container)
    {
        $this->_menuTaxRepository = $repository;
        $this->_container = $container;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_menu', array($this, 'renderMenu'), array('is_safe' => array('html'))),
        );
    }
    
    public function renderMenu($position, $class = 'list-unstyled', $id = '')
    {
        $menuTax = $this->_menuTaxRepository->findOneBy(array('position' => $position));
        if ($menuTax == null) {
            return '';
        }
        $entries = $menuTax->getEntries();
        $id = ($id != '') ? $id : $position;
        $class = ($class != '') ? 'menu '.$class : 'menu';
        
        return $this->_container->get('templating')->render(
            'MenuBundle:Twig:menu.html.twig',
            array('entries' => $entries, 'id' => $id, 'class' => $class)
        );
    }
    
    public function getName()
    {
        return 'render_menu';
    }
}
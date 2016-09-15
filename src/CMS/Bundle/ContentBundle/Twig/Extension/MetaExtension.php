<?php
namespace CMS\Bundle\ContentBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * User: DCA
 * Date: 14/09/2016
 * Time: 16:54
 * cms2
 */
class MetaExtension extends \Twig_Extension
{
    private $_container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
        
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('metas', array($this, 'renderMetas'), array('is_safe' => array('html'))),
        );
    }
    
    public function renderMetas($entity)
    {
        return $this->_container->get('templating')->render(
            'ContentBundle:Twig:metas.html.twig',
            array('metavalues' => $entity->getMetavalues())
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'metas';
    }
}
<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

class WidgetExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('instagram_images', array($this, 'renderInstagramImages'), array('is_safe' => array('html'))),
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'widget';
    }
}

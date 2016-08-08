<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class SocialExtension extends \Twig_Extension
{
    
    private $_container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->_container = $container;
        
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('social_buttons', array($this, 'renderSocialButtons'), array('is_safe' => array('html'))),
        );
    }
    
    public function renderSocialButtons()
    {
        $request = $this->_container->get('request');
        $url = $request->getSchemeAndHttpHost().$request->getRequestUri();
        
        
        return $this->_container->get('templating')->render(
            'CoreBundle:Twig:social.html.twig',
            array('url' => urlencode($url))
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'social';
    }
}

<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;


use Symfony\Component\HttpFoundation\RequestStack;

class SocialExtension extends \Twig_Extension
{
    
    private $_requestStack;
    private $_templating;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->_requestStack = $requestStack;
        
    }
    
    public function setTemplating(\Twig_Environment $templating)
    {
        $this->_templating = $templating;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('social_buttons', array($this, 'renderSocialButtons'), array('is_safe' => array('html'))),
        );
    }
    
    public function renderSocialButtons($url = '')
    {
        $request = $this->_requestStack->getCurrentRequest();
        if ($url == '') {
            $url = $request->getSchemeAndHttpHost().$request->getRequestUri();
        }
        
        
        return $this->_templating->render(
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

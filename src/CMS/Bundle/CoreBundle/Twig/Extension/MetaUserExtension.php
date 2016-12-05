<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class MetaUserExtension extends \Twig_Extension
{

    public function __construct(ContainerInterface $container, AuthorizationChecker $context) {
        $this->doctrine = $container->get('doctrine');
        $this->context = $context;
    }


	public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('meta_user', array($this, 'metaUserFilter')),
        );
    }

    public function metaUserFilter($value)
    {
        return ($value) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';
    }


    public function getName()
    {
    	return 'booleanicon';
    }
}
<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;

class BooleanIconExtension extends \Twig_Extension
{

	public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('booleanicon', array($this, 'booleanFilter')),
        );
    }

    public function booleanFilter($value)
    {
        return ($value) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-remove"></i>';
    }


    public function getName()
    {
    	return 'booleanicon';
    }
}
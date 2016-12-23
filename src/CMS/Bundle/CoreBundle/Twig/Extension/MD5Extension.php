<?php
/**
 * User: DCA
 * Date: 19/12/2016
 * Time: 11:55
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Twig\Extension;


class MD5Extension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('md5', array($this,'md5Filter'))
        );
    }

    public function md5Filter($value)
    {
        return md5($value);
    }

    public function getName()
    {
        return 'md5_extension';
    }
}
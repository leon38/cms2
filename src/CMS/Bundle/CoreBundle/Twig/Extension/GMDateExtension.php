<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

class GMDateExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('gmdate', array($this, 'GMDateFilter')),
        );
    }

    public function GMDateFilter($value, $format = 'i:s')
    {
        if (is_object($value)) {
            $value = $value->getTimestamp();
        }
        return gmdate($format, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'gmdate';
    }
}

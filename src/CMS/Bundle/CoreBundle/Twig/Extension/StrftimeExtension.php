<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

class StrftimeExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('strftime', array($this, 'StrftimeFilter')),
        );
    }

    public function StrftimeFilter($value, $format = 'i:s')
    {
        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        }
        setlocale (LC_TIME, 'fr_FR','fra');
        return strftime('%e %B %Y', $value);
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'strftime';
    }
}

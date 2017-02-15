<?php

namespace CMS\Bundle\CoreBundle\Twig\Extension;

class HighlightSearchWordExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('highlight', array($this, 'highlightSearchFilter')),
        );
    }

    public function highlightSearchFilter($value, $searched)
    {
        if ($searched != '') {
            preg_match_all('~\w+~', $searched, $m);
            if(!$m)
                return $value;
            $pattern = '~(' . implode('|', $m[0]) . ')~';
            return preg_replace($pattern, '<span class="highlight">$0</span>', $value);
        }
        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'highlight_search_word';
    }
}

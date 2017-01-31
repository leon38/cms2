<?php

namespace CMS\Bundle\ContentBundle\Twig\Extension;

use CMS\Bundle\ContentBundle\Entity\Repository\ContentRepository;

class QueryExtension extends \Twig_Extension
{

    private $_repo;

    public function __construct(ContentRepository $repo)
    {
        $this->_repo = $repo;

    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('Query', array($this, 'queryAction')),
        );
    }

    public function queryAction(array $params)
    {
        $contents = $this->_repo->getContentsBy($params);
        return $contents;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'query';
    }
}

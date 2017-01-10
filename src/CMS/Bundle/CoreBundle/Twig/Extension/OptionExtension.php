<?php
/**
 * User: DCA
 * Date: 26/07/2016
 * Time: 14:44
 * cms2
 */

namespace CMS\Bundle\CoreBundle\Twig\Extension;


use CMS\Bundle\CoreBundle\Entity\Repository\OptionRepository;

class OptionExtension extends \Twig_Extension
{
    
    private $_optionRepo;
    
    public function __construct(OptionRepository $optionRepo)
    {
        $this->_optionRepo = $optionRepo;
    }
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getOption', array($this, 'getOption'), array('is_safe' => array('html'))),
        );
    }
    
    public function getOption($name)
    {
        $value = $this->_optionRepo->findOptionByName($name);
        return $value;
    }
    
    
    public function getName()
    {
        return 'option';
    }
}
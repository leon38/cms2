<?php

namespace CMS\Bundle\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * OptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OptionRepository extends EntityRepository
{
    
    public function get($option_name)
    {
        $value = $this->_em
            ->createQueryBuilder('o')
            ->select('o')
            ->from('CoreBundle:Option', 'o')
            ->where('o.option_name = :option_name')
            ->setParameter('option_name', $option_name)
            ->getQuery()
            ->getOneOrNullResult();
        
        return ($value !== null) ? $value->getOptionValue() : $value;
    }
    
    public function getGeneralOptions()
    {
        return $this->_em
            ->createQueryBuilder('o')
            ->select('o')
            ->from('CoreBundle:Option', 'o')
            ->where('o.general = 1')
            ->getQuery()
            ->getResult();
    }
    
    public function findOptionByName($name)
    {
        $option = $this->_em->createQueryBuilder('o')
            ->select('o')
            ->from('CoreBundle:Option', 'o')
            ->where('o.option_name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
        if ($option != null) {
            if ($option->getType() == 'image') {
                $image = $this->_em->createQueryBuilder('m')
                    ->select('m')
                    ->from('MediaBundle:Media', 'm')
                    ->where('m.id = :id')
                    ->setParameter('id', $option->getOptionValue())
                    ->getQuery()
                    ->getOneOrNullResult();
                if ($image != null) {
                    return $image->getWebPath();
                }
            }
            return $option->getOptionValue();
        }
        return '';
    }
}

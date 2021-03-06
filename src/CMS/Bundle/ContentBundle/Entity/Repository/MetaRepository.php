<?php
namespace CMS\Bundle\ContentBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MetaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MetaRepository extends EntityRepository
{

    public function findByIndexed()
    {
        return $this->_em
            ->createQueryBuilder()
            ->select('m')
            ->from('ContentBundle:Meta', 'm', 'm.name')
            ->where('m.published = 1')
            ->orderBy('m.order')
            ->getQuery()
            ->getResult();
    }
}

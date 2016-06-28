<?php

namespace CMS\Bundle\MediaBundle\Entity\Repository;

/**
 * MediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MediaRepository extends \Doctrine\ORM\EntityRepository
{

  public function getNbMedia()
  {
    return $this->_em
      ->createQueryBuilder('m')
      ->select('COUNT(m)')
      ->from('MediaBundle:Media', 'm')
      ->getQuery()
      ->getArrayResult();
  }
}

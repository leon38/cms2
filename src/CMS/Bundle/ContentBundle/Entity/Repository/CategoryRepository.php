<?php

namespace CMS\Bundle\ContentBundle\Entity\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends NestedTreeRepository
{

  public function findAll()
  {
    return $this->_em
      ->createQueryBuilder('c')
      ->select('c')
      ->from('ContentBundle:Category', 'c')
      ->where('c.level > 0')
      ->orderby('c.lft', 'asc')
      ->getQuery()
      ->getResult();
  }



  public function getSiblings($id)
  {
    if ($id == 0) {
      $this->_em
        ->createQueryBuilder('c')
        ->select('c')
        ->from('ContentBundle:Category', 'c')
        ->where('c.level > 0')
        ->orderBy('c.lft', 'ASC');
    }

    return $this->_em
      ->createQueryBuilder('c')
      ->select('c')
      ->from('ContentBundle:Category', 'c')
      ->where('c.id != :id')
      ->setParameter('id', $id)
      ->orderBy('c.lft', 'ASC');
  }

  public function getAll($notEmpty = false)
  {
    $query = $this->_em
      ->createQueryBuilder('c')
      ->select('c')
      ->from('ContentBundle:Category', 'c');
    if ($notEmpty) {
      $query->innerJoin('c.contents', 'co');
      $query->groupBy('c.id');
      $query->having('COUNT(co.id) > 0');
    }

    return $query->getQuery()->getResult();
  }

  public function getCategoriesLinks()
  {
    return $this->_em
      ->createQueryBuilder('c')
      ->select('c')
      ->from('ContentBundle:Category', 'c')
      ->where('c.level > 0')
      ->orderBy('c.language, c.lft', 'ASC')
      ->addOrderBy('c.lft', 'ASC')
      ->getQuery()
      ->getResult();
  }


}

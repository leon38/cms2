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
			        ->where('c.level > 0');
		}

		return $this->_em
					->createQueryBuilder('c')
					->select('c')
					->from('ContentBundle:Category', 'c')
					->where('c.id != :id')
					->setParameter('id', $id);
	}
	
	public function getAll($notEmpty = false)
  {
    $query =  $this->_em
      ->createQueryBuilder('c')
      ->select('c')
      ->from('ContentBundle:Category', 'c');
      if ($notEmpty) {
          $query->groupBy('c.id');
          $query->having('COUNT(c.contents) > 0');
      }
      $query->getQuery()->getResult();
  }

}

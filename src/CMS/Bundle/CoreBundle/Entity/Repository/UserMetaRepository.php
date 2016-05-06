<?php

namespace CMS\Bundle\CoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use CMS\Bundle\CoreBundle\Entity\User;

/**
 * UserMetaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserMetaRepository extends EntityRepository
{

	public function get($meta_key, User $user)
	{
		$value = $this->_em
					->createQueryBuilder('um')
					->select('um')
					->from('CoreBundle:UserMeta', 'um')
					->where('um.meta_key = :meta_key')
					->setParameter('meta_key', $meta_key)
					->andWhere('um.user = :user')
					->setParameter('user', $user)
					->getQuery()
					->getOneOrNullResult();
		return ($value !== null) ? $value->getMetaValue() : $value;
	}
}

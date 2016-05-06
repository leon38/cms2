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
}

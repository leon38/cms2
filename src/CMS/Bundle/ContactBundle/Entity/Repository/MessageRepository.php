<?php

namespace CMS\Bundle\ContactBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{

	/**
	 * Récupère le dernier message reçu par date de reception
	 * @return Message $message le dernier message ou null s'il n'y en a pas
	 */
	public function findLastMessage()
	{
		$result = $this->_em
			->createQueryBuilder('m')
			->select('m')
			->from('ContactBundle:Message', 'm')
			->orderBy('m.sent_date', 'DESC')
			->getQuery()
			->getResult();
		return current($result);
	}
}

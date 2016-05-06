<?php
namespace CMS\Bundle\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;

use CMS\Bundle\CoreBundle\Entity\User;
use CMS\Bundle\CoreBundle\Entity\UserMeta;
use CMS\Bundle\CoreBundle\Entity\Repository\UserMetaRepository;

class UserMetaManager
{

	protected $em;
	protected $repo;

	public function __construct(EntityManager $em, UserMetaRepository $repo)
	{
		$this->em = $em;
		$this->repo = $repo;
	}

	public function addMeta($meta_key, User $user, $type = "text")
	{
		$metas = $user->getMetas();
		if($user->getId() !== null) {
			$meta = $this->repo->get($meta_key, $user);
			if ($meta === null) {
				$meta = new UserMeta();
				$meta->setMetaKey($meta_key);
				$meta->setMetaValue('');
				$user->addMeta($meta);
				$meta->setUser($user);
				$meta->setType($type);
				$this->em->persist($user);
				$this->em->flush();
			} else {
				$meta = $this->repo->findOneBy(array('meta_key' => $meta_key, 'user' => $user));
				$meta->setType($type);
				$user->addMeta($meta);
				$meta->setUser($user);
				$this->em->persist($user);
				$this->em->flush();
			}
		} else {
			$meta = new UserMeta();
			$meta->setType($type);
			$meta->setMetaKey($meta_key);
			$meta->setMetaValue('');
			$user->addMeta($meta);
		}
		return $meta;
	}
}
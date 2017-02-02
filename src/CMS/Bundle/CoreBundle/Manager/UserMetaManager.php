<?php
namespace CMS\Bundle\CoreBundle\Manager;

use CMS\Bundle\CoreBundle\Entity\Repository\UserRepository;
use Doctrine\ORM\EntityManager;

use CMS\Bundle\CoreBundle\Entity\User;
use CMS\Bundle\CoreBundle\Entity\UserMeta;
use CMS\Bundle\CoreBundle\Entity\Repository\UserMetaRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserMetaManager
{

  protected $em;
  protected $repo;
  protected $user_repo;

  public function __construct(EntityManager $em, UserMetaRepository $repo, UserRepository $user_repo)
  {
    $this->em = $em;
    $this->repo = $repo;
    $this->user_repo = $user_repo;
  }


  public function addMeta($meta_key, User $user = null, $type = TextType::class)
  {
    if ((!is_null($user) && $user->getId() === null) || is_null($user)) {
      $meta = new UserMeta();
      $meta->setType($type);
      $meta->setMetaKey($meta_key);
      $meta->setMetaValue('');
      $meta->setUser($user);
      $user->addMeta($meta);
    } else {
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
    }
    return $meta;
  }
}
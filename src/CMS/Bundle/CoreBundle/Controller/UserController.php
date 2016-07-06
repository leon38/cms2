<?php
namespace CMS\Bundle\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use CMS\Bundle\CoreBundle\Form\ProfileType;
use CMS\Bundle\CoreBundle\Form\UserType;
use CMS\Bundle\CoreBundle\Entity\User;
use CMS\Bundle\CoreBundle\Entity\UserMeta;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin")
 */
class UserController extends Controller
{

    private $avatar;

    /**
     * @Route("/user/profile", name="admin_user_profile")
     */
    public function profileAction()
    {
    	$user = $this->get('security.token_storage')->getToken()->getUser();
		$metaManager = $this->get('cms.core.meta_manager');
		$metaManager->addMeta('firstname', $user);
		$metaManager->addMeta('lastname', $user);
		$metaManager->addMeta('id_twitter', $user);
        $metaManager->addMeta('facebook_url', $user);
		$metaManager->addMeta('gplus_url', $user);
        $metaManager->addMeta('about_me', $user, 'textarea');

    	$form = $this->createProfileForm($user);

    	return $this->render('CoreBundle:User:profile.html.twig', array('form' => $form->createView()));
    }


    /**
    * Crée le formulaire du profil de l'utilisateur courant
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createProfileForm(User $user, $url = 'admin_user_profile_update')
    {
    	$securityContext = $this->container->get('security.context');
        $form = $this->createForm(new ProfileType($securityContext), $user, array(
            'user_id' => $user->getId(),
            'action' => $this->generateUrl($url, array('user' => $user->getId())),
            'method' => 'PUT',
            'user'   => $user
        ));

        return $form;
    }

    /**
    * Crée le formulaire du profil de l'utilisateur courant
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createNewUserForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('admin_user_create'),
            'method' => 'POST',
            'user'   => $user,
        ));

        return $form;
    }

    /**
     * Met à jour le profil de l'utilisateur
     *
     * @param User user utilisateur pour lequel on met à jour le profil
     * @return array
     *
     * @Route("/user/profile/update/{user}", name="admin_user_profile_update")
     * @Method("PUT")
     * @Template()
     */
    public function updateProfileAction(User $user, Request $request)
    {
    	$form = $this->createProfileForm($user);
    	if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if($form->isValid()) {
            	$em = $this->getDoctrine()->getManager();
            	$data = $form->getData();

                $newPassword = $user->getPlainPassword();
            	if ( !empty($newPassword)) {
                    $encoder = $this->container->get('security.password_encoder');
	                $user->setUserPass($encoder->encodePassword($user, $newPassword));
            	}

                if ( empty($user->getAvatar()) ) {
                    $user->setAvatar(null);
                }

            	$em->persist($user);
            	$em->flush();

            	$this->get('session')->getFlashBag()->add(
                    'success',
                    'cms.user.profile_updated.success'
                );
            }
        }
        return $this->redirect($this->generateUrl('admin_user_profile'));
    }

    /**
     * Affiche la liste des utilisateurs
     * @return array
     *
     * @Route("/users", name="admin_users")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('CoreBundle:User')->findAll();

        return $this->render('CoreBundle:User:index.html.twig', array(
            'entities' => $users,
            'url' => 'admin_user_delete'
        ));
    }

    /**
     * Affiche le formulaire d'ajout d'un utilisateur
     * et l'ajoute à la base
     * @return array
     *
     * @Route("/user/new", name="admin_user_create")
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $metaManager = $this->get('cms.core.meta_manager');
        $metaManager->addMeta('firstname', $user);
        $metaManager->addMeta('lastname', $user);
        $metaManager->addMeta('id_twitter', $user);
        $metaManager->addMeta('facebook_url', $user);
        $form = $this->createNewUserForm($user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $ser->setUserPass($encoder->encodePassword($user->getUserPass(), $user->getSalt()));

                foreach ($user->getMetas() as $meta) {
                    $user->addMeta($meta);
                    $meta->setUser($user);
                }

                foreach ($user->getRoles() as $role) {
                    $user->addRole($role);
                    $role->addUser($user);
                }

                $em->persist($user);
                $em->flush();

                $mailer = $this->get('cms.core.mailer');
                $mailer->sendConfirmationUser($user);

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'cms.user.profile_created.success'
                );
                return $this->redirect($this->generateUrl('admin_users'));
            }
        }
        return $this->render('CoreBundle:User:new.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    /**
     * Affiche le formulaire d'édition de l'utilisateur passé en paramètre
     * et le met à jour
     * @param  User    $user    utilisateur à éditer
     * @param  Request $request paramètres passés dans la requête
     * @return array
     *
     * @Route("/user/edit/{user}", name="admin_user_edit")
     * @Template()
     */
    public function editAction(User $user, Request $request)
    {
        $form = $this->createProfileForm($user, "admin_user_edit");
        if ($request->isMethod('PUT')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                $factory = $this->container->get('security.encoder_factory');
                $encoder = $factory->getEncoder($user);
                $user->setUserPass($encoder->encodePassword($data->getUserPass(), $user->getSalt()));
                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'cms.user.profile_updated.success'
                );
                return $this->redirect($this->generateUrl('admin_users'));
            }
        }
        return $this->render('CoreBundle:User:edit.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    /**
     * Supprime l'utilisateur passé en paramètre
     * @param  User   $user  Utilisateur à supprimer
     * @return view
     *
     * @Route("/user/delete/{id}", name="admin_user_delete")
     */
    public function deleteAction(User $id, Request $request)
    {
        $user = $id;
        if ($user === null) {
            $this->get('session')->getFlashBag()->add(
                'error',
                'cms.user.user_not_found'
            );
            return $this->redirect($this->generateUrl('admin_users'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add(
                'success',
                'cms.user.user_deleted'
        );
        return $this->redirect($this->generateUrl('admin_users'));
    }


    /**
     * Upload l'avatar de l'utilisateur
     * @return boolean
     *
     * @Route("/upload-avatar/{user}", name="admin_user_upload_avatar")
     */
    public function uploadAvatarAction(User $user, Request $request)
    {
        $file = $request->files->get('file');
        $fs = new Filesystem();
        $fileName = $file->getClientOriginalName();
        if ($user != null && $user->getId() != null) {
            $avatarDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/avatars/'.$user->getId();
        } else {
            $avatarDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/avatars';
        }

        if (!$fs->exists($avatarDir)) {
            $fs->mkdir($avatarDir);
        }
        if ($file->move($avatarDir, $fileName)) {
            $em = $this->getDoctrine()->getManager();
            $user->setAvatar($fileName);
            $em->persist($user);
            $em->flush();
        }



        $response = new JsonResponse();
        $response->setData(array('status' => true));
        return $response;
    }

}
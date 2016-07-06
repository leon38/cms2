<?php

namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use CMS\Bundle\CoreBundle\Classes\Install;
use CMS\Bundle\CoreBundle\Form\InstallType;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Gère l'initialisation d'un nouveau site
 *
 * @package scms
 * @subpackage Core
 * @since 0.1
 */
class InstallController extends Controller
{
    /**
     * Affichage du formulaire de création du site et de l'utilisateur
     * qui sera l'administrateur du site
     *
     * @param Request $request Informations envoyées par le formulaire
     *
     * @Route("/admin/install", name="admin_install")
     * @Template("CoreBundle:Install:install.html.twig")
     */
    public function indexAction(Request $request)
    {
        $error = '';
        $install = new Install();
        $form = $this->createForm(new InstallType(), $install);

        $em = $this->getDoctrine()->getManager();
        $role = $em->getRepository('CoreBundle:Role')->findOneBy(array('role_nicename' => 'Super Administrator'));
        if ($role === null) {
            $kernel = $this->get('kernel');
            $application = new Application($kernel);
            $application->setAutoExit(false);

            $input = new ArrayInput(array(
               'command' => 'scms:init'
            ));
            // You can use NullOutput() if you don't need the output
            $output = new NullOutput();
            $application->run($input, $output);
        }

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
            	$user = $install->setUser();
                $option = $install->getOption();
            	$factory = $this->container->get('security.encoder_factory');
            	$encoder = $factory->getEncoder($user);
            	$user->setUserPass($encoder->encodePassword($user->getUserPass(), $user->getSalt()));
              $user->setUserStatus(1);
                if ($role !== null) {
                    $role->addUser($user);
                    $user->addRole($role);
                    $em->persist($role);
                    $em->persist($user);
                    $em->persist($option);
                    $em->flush();
                    return $this->redirect($this->generateUrl('admin_install_message'));
                } else {
                    return array('title' => 'Installation du CMS 3c','form' => $form->createView(), 'error' => $error);
                }
            }
        }
        return $this->render('CoreBundle:Install:install.html.twig', array('title' => 'Installation du CMS 3c','form' => $form->createView(), 'error' => $error));
    }

    /**
     * Affiche le message de félicitations et envoie le mail de confirmation
     * @return array
     *
     * @Route("/admin/install/congratulations", name="admin_install_message")
     * @Template("CoreBundle:Install:message.html.twig")
     */
    public function messageAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository('CoreBundle:User')->find(1);
        $this->get('cms.core.mailer')->sendConfirmationUser($user);

    	return array('user' => $user);
    }
}

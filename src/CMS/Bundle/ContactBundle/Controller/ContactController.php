<?php

namespace CMS\Bundle\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

use Symfony\Component\HttpFoundation\JsonResponse;

use CMS\Bundle\ContactBundle\Entity\Message;
use CMS\Bundle\ContactBundle\Form\MessageType;

/**
 * @Route("/admin/contacts")
 */
class ContactController extends Controller
{
    const TRASH = 0;
    const INBOX = 1;
    const SENT  = 2;

    private $folders = array('inbox' => 'fa fa-inbox', 'sent' => 'fa fa-paper-plane', 'trash' => 'fa fa-trash');

    /**
     * Affiche tous les messages d'un répertoire
     * @param String $folder dossier de messages à afficher
     * @return array         tous les messages du dossier
     *
     * @Route("/messages/{folder}", name="admin_messages", defaults={"folder" = "inbox"})
     */
    public function indexAction($folder)
    {
    	$em = $this->getDoctrine()->getManager();
    	$messages = $em->getRepository('ContactBundle:Message')->findBy(array('status' => constant('self::'.strtoupper($folder))), array('sent_date' => 'DESC'));
    	$em = $this->getDoctrine()->getManager();
    	$last_message = $em->getRepository('ContactBundle:Message')->findLastMessage();
        return $this->render('ContactBundle:Contact:index.html.twig', array(
        	'messages' => $messages,
        	'last_message' => $last_message,
        	'bright_style' => true,
            'folder' => $folder,
            'folders' => $this->folders
        ));
    }

    /**
     * Affiche les détails d'un message
     * @param  Message $message message à afficher
     * @return array
     *
     * @Route("/show/{message}", name="admin_message_show", defaults={"message": null})
     */
    public function showAction(Message $message)
    {
    	if ($message === null) {
    		$em = $this->getDoctrine()->getManager();
    		$message = $em->getRepository('ContactBundle:Message')->findLastMessage();
    	}
    	$encoders = array(new JsonEncoder());
    	$normalizers = array(new GetSetMethodNormalizer());
    	$serializer = new Serializer($normalizers, $encoders);
    	return new JsonResponse($serializer->serialize($message, 'json'));
    }

    /**
     * Affiche le formulaire de création d'un nouveau message
     * @param  Request $request Infos envoyées par le formulaire
     * @return array
     *
     * @Route("/compose", name="admin_contact_compose")
     * @Template()
     */
    public function composeAction(Request $request)
    {
        $message = new Message();
        $form = $this->createCreateForm($message);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $message->setSender($em->getRepository('CoreBundle:Option')->get('email_admin'));
                $em->persist($message);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    'tc.user.profile_created.success'
                );

                return $this->redirect($this->generateUrl('admin_messages', array('folder' => 'sent')));
            }
        }
        return array(
            'form' => $form->createView(),
            'folders' => $this->folders,
            'folder' => 'sent',
            'bright_style' => true,
        );
    }

    private function createCreateForm(Message $message)
    {
        $form = $this->createForm(new MessageType(), $message, array(
            'action' => $this->generateUrl('admin_contact_compose'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary pull-right')));

        return $form;
    }
}
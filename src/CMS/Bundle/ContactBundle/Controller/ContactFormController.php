<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 01/07/2016
 * Time: 08:41
 */

namespace CMS\Bundle\ContactBundle\Controller;


use CMS\Bundle\ContactBundle\Entity\ContactForm;
use CMS\Bundle\ContactBundle\Entity\Message;
use CMS\Bundle\ContactBundle\Form\ContactFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContactFormController
 * @package CMS\Bundle\ContactBundle\Controller
 *
 */
class ContactFormController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/contactform/", name="admin_contactform_index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $forms = $em->getRepository('ContactBundle:ContactForm')->findAll();
        $option = $em->getRepository('CoreBundle:Option')->findOneBy(array('option_name' => 'date_format'));
        return $this->render('ContactBundle:ContactForm:index.html.twig', array(
            'forms' => $forms,
            'url' => 'admin_contactform_delete',
            'date_format' => $option->getOptionValue(),
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/contactform/new", name="admin_contactform_new")
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact_form = new ContactForm();
        $form = $this->createCreateForm($contact_form);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $contact_form->setTag('['.$contact_form->getAlias().']');
                $em->persist($contact_form);
                $em->flush();
                $this->addFlash("success", "cms.contact.contact_form.added");
                return $this->redirectToRoute("admin_contactform_index");
            }
        }
        return $this->render("ContactBundle:ContactForm:new.html.twig", array(
            'form' => $form->createView(),
        ));
    }


    private function createCreateForm(ContactForm $contactForm)
    {
        $form = $this->createForm(ContactFormType::class, $contactForm, array(
            'action' => $this->generateUrl('admin_contactform_new'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')));

        return $form;
    }

    /**
     * @param ContactForm $contactForm
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/admin/contactform/edit/{contactForm}", name="admin_contactform_edit")
     */
    public function editAction(ContactForm $contactForm, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createEditForm($contactForm);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $contactForm->setTag('['.$contactForm->getAlias().']');
                $em->persist($contactForm);
                $em->flush();
                $this->addFlash("success", "cms.contact.contact_form.edited");
                return $this->redirectToRoute("admin_contactform_index");
            }
        }
        return $this->render("ContactBundle:ContactForm:new.html.twig", array(
            'form' => $form->createView(),
        ));
    }

    private function createEditForm(ContactForm $contactForm)
    {
        $form = $this->createForm(ContactFormType::class, $contactForm, array(
            'action' => $this->generateUrl('admin_contactform_edit', array('contactForm' => $contactForm->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')));

        return $form;
    }

    /**
     * @param ContactForm $contactForm
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/admin/contactform/delete/{contactForm}", name="admin_contactform_delete")
     */
    public function deleteAction(ContactForm $contactForm)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($contactForm);
        $em->flush();
        return $this->redirectToRoute('admin_contactform_index');
    }

    /**
     * @param ContactForm $contactForm
     * @param Request $request
     *
     *
     * @Route("/contactform/add/{contactForm}", name="front_contactform_add")
     */
    public function createContactMessageAction(ContactForm $contactForm, Request $request)
    {
        $message = new Message();
        $receiver = $contactForm->getReceiver();
        $contact_info = $request->request->get('contact');
        preg_match_all('#\[([a-zA-Z0-9\-]+)]#', $receiver, $fields);
        $fields_tag = $fields[0];
        $fields_name = $fields[1];
        for($i=0;$i < count($fields_name); $i++) {
            $info = isset($contact_info[$fields_name[$i]]) ? $contact_info[$fields_name[$i]] : "";
            $receiver = str_replace($fields_tag[$i], $info, $receiver);
        }
        $message->setSender($receiver);

        $subject = $contactForm->getSubject();
        preg_match_all('#\[([a-zA-Z0-9\-]+)]#', $subject, $fields);
        $fields_tag = $fields[0];
        $fields_name = $fields[1];
        for($i=0;$i < count($fields_name); $i++) {
            $info = isset($contact_info[$fields_name[$i]]) ? $contact_info[$fields_name[$i]] : "";
            $subject = str_replace($fields_tag[$i], $info, $subject);
        }
        $message->setSubject($subject);


        $message_mail = $contactForm->getHtmlMessage();
        preg_match_all('#\[([a-zA-Z0-9\-]+)]#', $message_mail, $fields);
        $fields_tag = $fields[0];
        $fields_name = $fields[1];
        for($i=0;$i < count($fields_name); $i++) {
            $info = isset($contact_info[$fields_name[$i]]) ? $contact_info[$fields_name[$i]] : "";
            $message_mail = str_replace($fields_tag[$i], $info, $message_mail);
        }
        $message->setMessage($message_mail);
        $email_admin = $this->getDoctrine()->getRepository('CoreBundle:Option')->findOptionByName('email_admin');
        $message->setReceivers(array($email_admin));
        $message->setStatus(1);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            $this->addFlash("success", $contactForm->getTranslations()['message_mail_sent_ok']);
        } catch(\Exception $e) {
            $this->addFlash("error", $contactForm->getTranslations()['message_mail_sent_nok']);
        }
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
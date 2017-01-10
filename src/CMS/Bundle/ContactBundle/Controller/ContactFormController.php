<?php
/**
 * Created by PhpStorm.
 * User: DCA
 * Date: 01/07/2016
 * Time: 08:41
 */

namespace CMS\Bundle\ContactBundle\Controller;


use CMS\Bundle\ContactBundle\Entity\ContactForm;
use CMS\Bundle\ContactBundle\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ContactFormController
 * @package CMS\Bundle\ContactBundle\Controller
 *
 * @Route("/admin/contactform")
 */
class ContactFormController extends Controller
{
  /**
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @Route("/", name="admin_contactform_index")
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
   * @Route("/new", name="admin_contactform_new")
   */
  public function newAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager();
    $contact_form = new ContactForm();
    $form = $this->createForm(new ContactFormType(), $contact_form);
    if ($request->getMethod() == 'POST') {
      $form->handleRequest($request);
      if ($form->isValid()) {
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



}
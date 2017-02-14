<?php

namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\Bundle\CoreBundle\Entity\Language;
use CMS\Bundle\CoreBundle\Form\LanguageType;

/**
 * Language controller.
 *
 * @Route("/admin/languages")
 */
class LanguageController extends Controller
{

    /**
     * Lists all Language entities.
     *
     * @Route("/", name="admin_languages")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreBundle:Language')->findAll();

        return $this->render('CoreBundle:Language:index.html.twig', array(
            'entities' => $entities,
            'url' => 'admin_languages_delete'
        ));
    }
    /**
     * Creates a new Language entity.
     *
     * @Route("/", name="admin_languages_create")
     * @Method("POST")
     * @Template("CoreBundle:Language:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Language();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_languages'));
        }

        return $this->render('CoreBundle:Language:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Language entity.
     *
     * @param Language $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Language $entity)
    {
        $form = $this->createForm(LanguageType::class, $entity, array(
            'action' => $this->generateUrl('admin_languages_create'),
            'method' => 'POST',
            'attr'   => array('class' => 'form'),
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary pull-right')));

        return $form;
    }

    /**
     * Displays a form to create a new Language entity.
     *
     * @Route("/new", name="admin_languages_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Language();
        $form   = $this->createCreateForm($entity);

        return $this->render('CoreBundle:Language:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Language entity.
     *
     * @Route("/{id}/edit", name="admin_languages_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('CoreBundle:Language:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Language entity.
    *
    * @param Language $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Language $entity)
    {
        $form = $this->createForm(LanguageType::class, $entity, array(
            'action' => $this->generateUrl('admin_languages_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'attr'   => array('class' => 'form'),
            'language' => $entity->getCodeLang(),
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary pull-right')));

        return $form;
    }
    /**
     * Edits an existing Language entity.
     *
     * @Route("/{id}", name="admin_languages_update")
     * @Method("PUT")
     * @Template("CoreBundle:Language:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_languages'));
        }

        return $this->render('CoreBundle:Language:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
    /**
     * Deletes a Language entity.
     *
     * @Route("/delete/{id}", name="admin_languages_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreBundle:Language')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Language entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_languages'));
    }

    /**
     * Change la langue par défaut du site
     * @param  Language $language langue par défaut
     * @return JsonResponse
     *
     * @Route("/default/{language}", name="admin_language_default")
     */
    public function changeDefaultAction(Language $language)
    {
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('CoreBundle:Language')->changeDefault();
        $language->setDefault(true);
        $em->persist($language);
        $em->flush();
        $response = new Response();
        $response->setStatusCode(200);
        return $response;
    }
}

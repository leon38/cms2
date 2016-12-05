<?php

namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\Bundle\ContentBundle\Entity\ContentTaxonomy;
use CMS\Bundle\ContentBundle\Form\ContentTaxonomyType;

/**
 * ContentTaxonomy controller.
 *
 * @Route("/admin/content-taxonomy")
 */
class ContentTaxonomyController extends Controller
{

    /**
     * Lists all ContentTaxonomy entities.
     *
     * @Route("/", name="admin_content-taxonomy")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ContentBundle:ContentTaxonomy')->findAll();

        return $this->render('ContentBundle:ContentTaxonomy:index.html.twig',
            array(
            'entities' => $entities,
            'url' => 'admin_content-taxonomy_delete'
        ));
    }
    /**
     * Creates a new ContentTaxonomy entity.
     *
     * @Route("/", name="admin_content-taxonomy_create")
     * @Method("POST")
     * @Template("ContentBundle:ContentTaxonomy:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ContentTaxonomy();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_content-taxonomy'));
        }

        return $this->render('ContentBundle:ContentTaxonomy:new.html.twig',
        array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a ContentTaxonomy entity.
     *
     * @param ContentTaxonomy $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ContentTaxonomy $entity)
    {
        $form = $this->createForm(ContentTaxonomyType::class, $entity, array(
            'action' => $this->generateUrl('admin_content-taxonomy_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')));

        return $form;
    }

    /**
     * Displays a form to create a new ContentTaxonomy entity.
     *
     * @Route("/new", name="admin_content-taxonomy_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ContentTaxonomy();
        $form   = $this->createCreateForm($entity);

        return $this->render('ContentBundle:ContentTaxonomy:new.html.twig',
        array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ContentTaxonomy entity.
     *
     * @Route("/{id}/edit", name="admin_content-taxonomy_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContentBundle:ContentTaxonomy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentTaxonomy entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('ContentBundle:ContentTaxonomy:edit.html.twig',
        array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a ContentTaxonomy entity.
    *
    * @param ContentTaxonomy $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(ContentTaxonomy $entity)
    {
        $form = $this->createForm(ContentTaxonomyType::class, $entity, array(
            'action' => $this->generateUrl('admin_content-taxonomy_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')));

        return $form;
    }
    /**
     * Edits an existing ContentTaxonomy entity.
     *
     * @Route("/{id}", name="admin_content-taxonomy_update")
     * @Method("PUT")
     * @Template("ContentBundle:ContentTaxonomy:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContentBundle:ContentTaxonomy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentTaxonomy entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_content-taxonomy'));
        }

        return $this->render('ContentBundle:ContentTaxonomy:edit.html.twig',
        array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a ContentTaxonomy entity.
     *
     * @Route("/delete/{id}", name="admin_content-taxonomy_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ContentBundle:ContentTaxonomy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ContentTaxonomy entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_content-taxonomy'));
    }

}

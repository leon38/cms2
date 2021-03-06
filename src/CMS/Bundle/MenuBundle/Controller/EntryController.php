<?php

namespace CMS\Bundle\MenuBundle\Controller;

use CMS\Bundle\MenuBundle\Entity\MenuTaxonomy;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\Bundle\MenuBundle\Entity\Entry;
use CMS\Bundle\MenuBundle\Form\EntryType;

/**
 * Entry controller.
 *
 * @Route("/admin/entry")
 */
class EntryController extends Controller
{

    /**
     * Lists all Entry entities.
     *
     * @Route("/{slug}", name="admin_entry")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MenuBundle:Entry')->findByMenuTaxonomy($slug);

        return $this->render('MenuBundle:Entry:index.html.twig',
            array(
            'url' => 'admin_entry_delete',
            'entities' => $entities,
            'slug' => $slug
        ));
    }
    /**
     * Creates a new Entry entity.
     *
     * @Route("/{slug}", name="admin_entry_create")
     * @Method("POST")
     * @Template("MenuBundle:Entry:new.html.twig")
     */
    public function createAction($slug, Request $request)
    {
        $entity = new Entry();
        $menu_taxonomy = $this->getDoctrine()->getRepository('MenuBundle:MenuTaxonomy')->findOneBy(array('slug' => $slug));
        $entity->setMenuTaxonomy($menu_taxonomy);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ( is_null($entity->getParent()) ) {
                $root = $em->getRepository('MenuBundle:Entry')->findOneBy(array('lft' => 1, 'menu_taxonomy' => $entity->getMenuTaxonomy()));
                $entity->setParent($root);
            }

            if ($entity->getOrdre() != null) {
                $em->getRepository('Entry')->persistAsNextSiblingOf($entity, $entity->getOrdre());
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_entry', array('slug' => $slug)));
        }

        return $this->render('MenuBundle:Entry:new.html.twig',
            array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Entry entity.
     *
     * @param Entry $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Entry $entity)
    {
        $form = $this->createForm(EntryType::class, $entity, array(
            'action' => $this->generateUrl('admin_entry_create', array('slug' => $entity->getMenuTaxonomy()->getSlug())),
            'method' => 'POST',
            'entry'  => $entity
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')));

        return $form;
    }

    /**
     * Displays a form to create a new Entry entity.
     *
     * @Route("/new/{slug}", name="admin_entry_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($slug)
    {
        $entity = new Entry();
        $menu_taxonomy = $this->getDoctrine()->getRepository('MenuBundle:MenuTaxonomy')->findOneBy(array('slug' => $slug));
        $entity->setMenuTaxonomy($menu_taxonomy);

        $form   = $this->createCreateForm($entity);

        return $this->render('MenuBundle:Entry:new.html.twig',
            array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Entry entity.
     *
     * @Route("/{id}/edit", name="admin_entry_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MenuBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('MenuBundle:Entry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Entry entity.
    *
    * @param Entry $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Entry $entity)
    {
        $form = $this->createForm(EntryType::class, $entity, array(
            'action' => $this->generateUrl('admin_entry_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'entry' => $entity,
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Update', 'attr' => array('class' => 'btn btn-fill btn-info pull-right')));

        return $form;
    }
    /**
     * Edits an existing Entry entity.
     *
     * @Route("/{id}", name="admin_entry_update")
     * @Method("PUT")
     * @Template("MenuBundle:Entry:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MenuBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ( is_null($entity->getParent()) ) {
                $root = $em->getRepository('MenuBundle:Entry')->findOneBy(array('lft' => 1, 'menu_taxonomy' => $entity->getMenuTaxonomy()));
                $entity->setParent($root);
            }
            if ($entity->getOrdre() != null) {
                $em->getRepository('MenuBundle:Entry')->persistAsNextSiblingOf($entity, $entity->getOrdre());
            }
            $em->flush();

            return $this->redirect($this->generateUrl('admin_entry', array('slug' => $entity->getMenuTaxonomy()->getSlug())));
        }

        return $this->render('MenuBundle:Entry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a Entry entity.
     *
     * @Route("/delete/{id}", name="admin_entry_delete")
     */
    public function deleteAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MenuBundle:Entry')->find($id);
        $menu_taxonomy = $entity->getMenuTaxonomy();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $em->remove($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('admin_entry', array('slug' => $menu_taxonomy->getSlug())));
    }


}

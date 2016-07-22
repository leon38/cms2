<?php

namespace CMS\Bundle\MenuBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\Bundle\MenuBundle\Entity\MenuTaxonomy;
use CMS\Bundle\MenuBundle\Entity\Entry;
use CMS\Bundle\MenuBundle\Form\MenuTaxonomyType;

/**
 * MenuTaxonomy controller.
 *
 * @Route("/admin/menutaxonomy")
 */
class MenuTaxonomyController extends Controller
{

    /**
     * Lists all MenuTaxonomy entities.
     *
     * @Route("/", name="admin_menutaxonomy")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MenuBundle:MenuTaxonomy')->findAll();

        return $this->render('MenuBundle:MenuTaxonomy:index.html.twig', array(
            'url' => 'admin_menutaxonomy_delete',
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new MenuTaxonomy entity.
     *
     * @Route("/", name="admin_menutaxonomy_create")
     * @Method("POST")
     * @Template("MenuBundle:MenuTaxonomy:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new MenuTaxonomy();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $entry = new Entry();
            $entry->setTitle("Root ".$entity->getTitle());
            $entry->setRoot($entry);
            $entry->setStatus(0);
            $entry->setMenuTaxonomy($entity);
            $em->persist($entry);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_menutaxonomy', array('id' => $entity->getId())));
        }

        return $this->render('MenuBundle:MenuTaxonomy:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a MenuTaxonomy entity.
     *
     * @param MenuTaxonomy $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(MenuTaxonomy $entity)
    {
        $form = $this->createForm(new MenuTaxonomyType(), $entity, array(
            'action' => $this->generateUrl('admin_menutaxonomy_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));

        return $form;
    }

    /**
     * Displays a form to create a new MenuTaxonomy entity.
     *
     * @Route("/new", name="admin_menutaxonomy_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new MenuTaxonomy();
        $form   = $this->createCreateForm($entity);

        return $this->render('MenuBundle:MenuTaxonomy:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing MenuTaxonomy entity.
     *
     * @Route("/{id}/edit", name="admin_menutaxonomy_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MenuBundle:MenuTaxonomy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuTaxonomy entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('MenuBundle:MenuTaxonomy:edit.html.twig', array(
            'entity' => $entity,
            'form'   => $editForm->createView()
        ));
    }

    /**
    * Creates a form to edit a MenuTaxonomy entity.
    *
    * @param MenuTaxonomy $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(MenuTaxonomy $entity)
    {
        $form = $this->createForm(new MenuTaxonomyType(), $entity, array(
            'action' => $this->generateUrl('admin_menutaxonomy_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill')));

        return $form;
    }
    /**
     * Edits an existing MenuTaxonomy entity.
     *
     * @Route("/{id}", name="admin_menutaxonomy_update")
     * @Method("PUT")
     * @Template("MenuBundle:MenuTaxonomy:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MenuBundle:MenuTaxonomy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuTaxonomy entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.menu.menu_updated.success'
            );

            return $this->redirect($this->generateUrl('admin_menutaxonomy'));
        }

        return $this->render('MenuBundle:MenuTaxonomy:edit.html.twig', array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
        ));
    }
    /**
     * Deletes a MenuTaxonomy entity.
     *
     * @Route("/delete/{id}", name="admin_menutaxonomy_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MenuBundle:MenuTaxonomy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find MenuTaxonomy entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_menutaxonomy'));
    }


}

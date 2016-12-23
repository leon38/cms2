<?php

namespace CMS\Bundle\ContentBundle\Controller;

use CMS\Bundle\ContentBundle\Classes\ExtraMetas;
use CMS\Bundle\ContentBundle\Entity\Category;
use CMS\Bundle\ContentBundle\Entity\Meta;
use CMS\Bundle\ContentBundle\Entity\MetaValue;
use CMS\Bundle\ContentBundle\Event\CategorySavedEvent;
use CMS\Bundle\ContentBundle\Event\CategorySubscriber;
use CMS\Bundle\ContentBundle\Form\CategoryType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Category controller.
 *
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin_category")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ContentBundle:Category')->findAll();

        return $this->render(
            'ContentBundle:Category:index.html.twig',
            array(
                'entities' => $entities,
                'url' => 'admin_content_delete',
            )
        );
    }

    /**
     * Creates a new Category entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="admin_category_create")
     * @Method("POST")
     * @Template("ContentBundle:Category:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Category();
        $metas = ExtraMetas::loadMetas($this->getDoctrine()->getRepository('ContentBundle:Meta'));
        $form = $this->createCreateForm($entity, $metas);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($entity->getOrdre() != null) {
                $em->getRepository('ContentBundle:Category')->persistAsNextSiblingOf($entity, $entity->getOrdre());
                $entity->setOrdre($entity->getOrdre()->getOrdre() + 1);
            }
            $dispatcher = new EventDispatcher();
            $settings = array('base_url' => $request->getSchemeAndHttpHost());
            $event = new CategorySavedEvent($entity, $settings);
            $subscriber = new CategorySubscriber($em, $this->get('router'), $this->get('assets.packages'));
            $dispatcher->addSubscriber($subscriber);
            $dispatcher->dispatch(CategorySavedEvent::NAME, $event);
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.category_created.success'
            );

            return $this->redirect($this->generateUrl('admin_category'));
        }

        return $this->render(
            'ContentBundle:Category:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a form to create a Category entity.
     *
     * @param Category $entity The entity
     * @param ArrayCollection|Meta[] $metas
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Category $entity, $metas)
    {
        $form = $this->createForm(
            CategoryType::class,
            $entity,
            array(
                'action' => $this->generateUrl('admin_category_create'),
                'method' => 'POST',
                'attr' => array('class' => 'form'),
                'metas' => $metas,
            )
        );

        $form->add(
            'submit',
            SubmitType::class,
            array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        )->add(
            'submit_seo',
            SubmitType::class,
            array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        );

        return $form;
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="admin_category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Category();
        $metas = ExtraMetas::loadMetas($this->getDoctrine()->getRepository('ContentBundle:Meta'));
        $form = $this->createCreateForm($entity, $metas);

        return $this->render(
            'ContentBundle:Category:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Category entity.
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}/edit", name="admin_category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContentBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $metavalues = $em->getRepository('ContentBundle:MetaValue')->findMetavalueByCategory($entity);
        $metas = $em->getRepository('ContentBundle:Meta')->findByIndexed();
        $editForm = $this->createEditForm($entity, $metas, $metavalues);

        return $this->render(
            'ContentBundle:Category:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Creates a form to edit a Category entity.
     *
     * @param Category $entity The entity
     *
     * @param ArrayCollection|Meta[] $metas
     * @param ArrayCollection|MetaValue[] $metavalues
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Category $entity, $metas, $metavalues)
    {
        $form = $this->createForm(
            CategoryType::class,
            $entity,
            array(
                'action' => $this->generateUrl('admin_category_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'id' => $entity->getId(),
                'metas' => $metas,
                'metavalues' => $metavalues
            )
        );

        $form->add(
            'submit',
            SubmitType::class,
            array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        );
        $form->add(
            'submit_seo',
            SubmitType::class,
            array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        );

        return $form;
    }

    /**
     * Edits an existing Category entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}", name="admin_category_update")
     * @Method("PUT")
     * @Template("ContentBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ContentBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $metavalues = $em->getRepository('ContentBundle:MetaValue')->findMetavalueByCategory($entity);
        $metas = $em->getRepository('ContentBundle:Meta')->findByIndexed();

        $editForm = $this->createEditForm($entity, $metas, $metavalues);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($entity->getOrdre() != null) {
                $em->getRepository('ContentBundle:Category')->persistAsNextSiblingOf($entity, $entity->getOrdre());
                $entity->setOrdre($entity->getOrdre()->getOrdre() + 1);
            }
            $dispatcher = new EventDispatcher();
            $settings = array('base_url' => $request->getSchemeAndHttpHost());
            $event = new CategorySavedEvent($entity, $settings);
            $subscriber = new CategorySubscriber($em, $this->get('router'), $this->get('assets.packages'));
            $dispatcher->addSubscriber($subscriber);
            $dispatcher->dispatch(CategorySavedEvent::NAME, $event);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.content_updated.success'
            );

            return $this->redirect($this->generateUrl('admin_category'));
        } else {
            dump($editForm->getErrors(true, false)); die;
        }

        return $this->render(
            'ContentBundle:Category:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Category entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/delete/{id}", name="admin_category_delete")
     */
    public function deleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ContentBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_category'));
    }
}

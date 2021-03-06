<?php

namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CMS\Bundle\ContentBundle\Entity\Meta;
use CMS\Bundle\ContentBundle\Form\MetaType;

/**
 * Meta controller.
 *
 * @Route("/admin/meta")
 */
class MetaController extends Controller
{
    /**
     * Lists all Meta entities.
     *
     * @Route("/", name="admin_meta_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $metas = $em->getRepository('ContentBundle:Meta')->findBy(array(), array('order' => 'ASC'));

        return $this->render('ContentBundle:Meta:index.html.twig', array(
            'metas' => $metas,
        ));
    }
    
    /**
     * Creates a new Meta entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new", name="admin_meta_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $metum = new Meta();
        $form = $this->createForm(MetaType::class, $metum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $last = $this->getDoctrine()->getRepository('ContentBundle:Meta')->findOneBy(array(), array('order' => 'DESC'));
            $last_order = $last->getOrder() + 1;
            $metum->setOrder($last_order);
            $em->persist($metum);
            $em->flush();

            return $this->redirectToRoute('admin_meta_index');
        }

        return $this->render('ContentBundle:Meta:new.html.twig', array(
            'metum' => $metum,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Displays a form to edit an existing Meta entity.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \CMS\Bundle\ContentBundle\Entity\Meta $metum
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}/edit", name="admin_meta_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Meta $metum)
    {
        $editForm = $this->createForm(MetaType::class, $metum);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($metum);
            $em->flush();

            return $this->redirectToRoute('admin_meta_index');
        }

        return $this->render('ContentBundle:Meta:edit.html.twig', array(
            'metum' => $metum,
            'edit_form' => $editForm->createView(),
        ));
    }
    
    /**
     * Deletes a Meta entity.
     *
     * @param \CMS\Bundle\ContentBundle\Entity\Meta $metum
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/delete/{id}", name="admin_meta_delete")
     */
    public function deleteAction(Meta $metum)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($metum);
        $em->flush();

        return $this->redirectToRoute('admin_meta_index');
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/order/update", name="admin_meta_order")
     * @Method("POST")
     */
    public function updateOrder(Request $request)
    {
        $ids = $request->request->get('order');
        $ids = explode(",", $ids);
        $order = 1;
        $em = $this->getDoctrine()->getManager();
        foreach($ids as $id) {
            $meta = $this->getDoctrine()->getRepository('ContentBundle:Meta')->find($id);
            dump($meta);
            if ($meta == null) {
                $this->get('session')->getFlashBag()->add(
                    'error',
                    'cms.content.meta.order.error'
                );
                return $this->redirectToRoute('admin_meta_index');
            }
            $meta->setOrder($order);
            $em->persist($meta);
            $order++;
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add(
            'success',
            'cms.content.meta.order.success'
        );
        return $this->redirectToRoute('admin_meta_index');
    }
}

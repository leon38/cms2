<?php

namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\Bundle\ContentBundle\Entity\Content;
use CMS\Bundle\ContentBundle\Entity\Meta;
use CMS\Bundle\ContentBundle\Form\ContentType;
use CMS\Bundle\ContentBundle\Classes\ExtraFields;
use CMS\Bundle\ContentBundle\Classes\ExtraMetas;
use CMS\Bundle\CoreBundle\Entity\Language;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Content controller.
 *
 * @Route("/admin/content")
 */
class ContentController extends Controller
{
    
    private $content;
    
    /**
     * Lists all Content entities.
     *
     * @Route("/{nb_elem}/{page}", name="admin_content", requirements={"nb_elem": "\d+", "page": "\d+"}, defaults={"page": 1, "nb_elem": 10})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction($page, $nb_elem)
    {
        $em = $this->getDoctrine()->getManager();
        
        $offset = ($page - 1) * $nb_elem;
        
        $query = $em->getRepository('ContentBundle:Content')->findByQuery(array(), array('id' => 'DESC'), $nb_elem, $offset);
    
        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            $page/*page number*/,
            $nb_elem/*limit per page*/
        );
        
        $languages = $em->getRepository('CoreBundle:Language')->findAll();
        $taxonomies = $em->getRepository('ContentBundle:ContentTaxonomy')->findAll();
        
        $option = $em->getRepository('CoreBundle:Option')->findOneBy(array('option_name' => 'date_format'));
        
        return $this->render(
            'ContentBundle:Content:index.html.twig',
            array(
                'entities' => $entities,
                'url' => 'admin_content_delete',
                'languages' => $languages,
                'taxonomies' => $taxonomies,
                'date_format' => $option->getOptionValue(),
            )
        );
    }
    
    /**
     * Creates a new Content entity.
     *
     * @Route("/", name="admin_content_create")
     * @Method("POST")
     * @Template("ContentBundle:Content:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $this->content = new Content();
        
        $data = $request->request->get('tc_bundle_contentbundle_content');
        $taxonomy = $data['taxonomy'];
        $taxonomy = $this->getDoctrine()->getRepository('ContentBundle:ContentTaxonomy')->find($taxonomy);
        $this->content->setTaxonomy($taxonomy);
        $metas = ExtraMetas::loadMetas($this);
        
        $form = $this->createCreateForm($this->content, $taxonomy->getFields(), $metas);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $current_user = $this->get('security.context')->getToken()->getUser();
            $this->content->setAuthor($current_user);
            if (is_null($this->content->getLanguage())) {
                $this->content->setLanguage($em->getRepository('CoreBundle:Language')->find(1));
            }
            $this->get('cms.content.content_manager')->save($this->content);
            
            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.content_created.success'
            );
            if ($form->get('submit_stay')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_content_edit', array('id' => $this->content->getId())));
            }
            return $this->redirect($this->generateUrl('admin_content'));
        }
        
        return $this->render(
            'ContentBundle:Content:new.html.twig',
            array(
                'entity' => $this->content,
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * Ajoute une traduction au contenu
     * @param  Content $content Contenu à traduire
     * @return mixed
     *
     * @Route("/translate/{content}/{language}", name="admin_translation_create")
     * @Method("POST")
     * @Template("ContentBundle:Content:new.html.twig")
     */
    public function createTranslationAction(Content $content, Language $language)
    {
        $this->content = $content;
        $entity = new Content();
        $entity->setReferenceContent($this->content);
        $entity->setLanguage($language);
        $entity->setTaxonomy($this->content->getTaxonomy());
        $fields = $entity->getTaxonomy()->getFields();
        $metas = ExtraMetas::loadMetas($this);
        
        $form = $this->createTranslationForm($entity, $fields, $metas);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $current_user = $this->get('security.context')->getToken()->getUser();
            $entity->setAuthor($current_user);
            
            $this->get('cms.content.content_manager')->update($entity);
            
            
            $this->get('session')->getFlashBag()->add(
                'success',
                'tc.content.content_created.success'
            );
            
            return $this->redirect($this->generateUrl('admin_content'));
        }
        
        return $this->render(
            'ContentBundle:Content:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * Creates a form to create a Content entity.
     *
     * @param Content $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Content $entity, $fields = array(), $metas = array())
    {
        $form = $this->createForm(
            new ContentType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_content_create'),
                'method' => 'POST',
                'attr' => array('class' => 'form'),
                'fields' => $fields,
                'metas' => $metas,
                'user' => $this->getUser(),
            )
        );
        $form->add('submit_stay', 'submit', array('label' => 'Create and stay', 'attr' => array('class' => 'btn btn-info btn-fill')));
        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));
        
        return $form;
    }
    
    /**
     * Creates a form to create a translation of a Content entity
     *
     * @param Content $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createTranslationForm(Content $entity, $fields = array(), $metas = array())
    {
        $form = $this->createForm(
            new ContentType(),
            $entity,
            array(
                'action' => $this->generateUrl(
                    'admin_translation_create',
                    array(
                        'content' => $entity->getReferenceContent()->getId(),
                        'language' => $entity->getLanguage()->getId(),
                    )
                ),
                'method' => 'POST',
                'attr' => array('class' => 'form'),
                'fields' => $fields,
                'metas' => $metas,
                'metavalues' => array(),
            )
        );
        
        $form->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));
        
        return $form;
    }
    
    /**
     * Displays a form to create a new Content entity.
     *
     * @Route("/new/{contenttaxonomy}", name="admin_content_new", defaults={"contenttaxonomy": ""})
     * @Method("GET")
     * @Template()
     */
    public function newAction($contenttaxonomy)
    {
        $em = $this->getDoctrine()->getManager();
        $this->content = new Content();
        $contenttaxonomy = $em->getRepository('ContentBundle:ContentTaxonomy')->findOneBy(
            array('alias' => $contenttaxonomy)
        );
        $this->content->setTaxonomy($contenttaxonomy);
        $current_user = $this->get('security.context')->getToken()->getUser();
        $this->content->setAuthor($current_user);
        $fields = $this->content->getTaxonomy()->getFields();
        $metas = ExtraMetas::loadMetas($this);
        
        $form = $this->createCreateForm($this->content, $fields, $metas);
        
        //$metas = ExtraMetas::loadMetas($this);
        return $this->render(
            'ContentBundle:Content:new.html.twig',
            array(
                'entity' => $this->content,
                'contenttaxonomy' => $contenttaxonomy,
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * Displays a form to create a new Content entity.
     *
     * @Route("/new/{content}/{language}", name="admin_translation_new")
     * @Method("GET")
     * @Template("ContentBundle:Content:new.html.twig")
     */
    public function newTranslationAction(Content $content, Language $language)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Content();
        $entity->setReferenceContent($content);
        $entity->setLanguage($language);
        $entity->setTaxonomy($content->getTaxonomy());
        $current_user = $this->get('security.context')->getToken()->getUser();
        $entity->setAuthor($current_user);
        $fields = $entity->getTaxonomy()->getFields();
        $metas = ExtraMetas::loadMetas($this);
        $form = $this->createTranslationForm($entity, $fields, $metas);
        
        //
        return $this->render(
            'ContentBundle:Content:new.html.twig',
            array(
                'entity' => $entity,
                'contenttaxonomy' => $content->getTaxonomy(),
                'form' => $form->createView(),
            )
        );
    }
    
    /**
     * Displays a form to edit an existing Content entity.
     *
     * @Route("/{id}/edit", name="admin_content_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('ContentBundle:Content')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        
        $fieldvalues = $em->getRepository('ContentBundle:FieldValue')->findBy(array('content' => $entity));
        $fields = $em->getRepository('ContentBundle:Field')->findByTaxonomyIndexed($entity->getTaxonomy());
        
        $metavalues = $em->getRepository('ContentBundle:MetaValue')->findMetavalueByContent($entity);
        $metas = $em->getRepository('ContentBundle:Meta')->findByIndexed();
        
        $editForm = $this->createEditForm($entity, $fields, $fieldvalues, $metas, $metavalues);
        
        return $this->render(
            'ContentBundle:Content:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }
    
    /**
     * Creates a form to edit a Content entity.
     *
     * @param Content $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Content $entity, $fields, $fieldvalues, $metas, $metavalues)
    {
        $form = $this->createForm(
            new ContentType(),
            $entity,
            array(
                'action' => $this->generateUrl('admin_content_update', array('id' => $entity->getId())),
                'method' => 'PUT',
                'attr' => array('class' => 'form'),
                'user' => $this->getUser(),
                'fields' => $fields,
                'fieldvalues' => $fieldvalues,
                'metas' => $metas,
                'metavalues' => $metavalues,
                'content_id' => $entity->getId(),
            )
        );
        
        $form->add(
            'submit',
            'submit',
            array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        );
    
        $form->add(
            'submit_stay',
            'submit',
            array('label' => 'Update and stay', 'attr' => array('class' => 'btn btn-info pull-right'))
        );
        
        return $form;
    }
    
    /**
     * Edits an existing Content entity.
     *
     * @Route("/{id}", name="admin_content_update")
     * @Method("PUT")
     * @Template("ContentBundle:Content:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        
        $entity = $em->getRepository('ContentBundle:Content')->find($id);
        $originalCategories = $entity->getCategories();
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        
        $fieldvalues = $em->getRepository('ContentBundle:FieldValue')->findFielvalueByContent($entity);
        $fields = $em->getRepository('ContentBundle:Field')->findByTaxonomyIndexed($entity->getTaxonomy());
    
        $metavalues = $em->getRepository('ContentBundle:MetaValue')->findMetavalueByContent($entity);
        $metas = $em->getRepository('ContentBundle:Meta')->findByIndexed();
        
        
        
        $editForm = $this->createEditForm($entity, $fields, $fieldvalues, $metas, $metavalues);
        $editForm->handleRequest($request);
        
        if ($editForm->isValid()) {
            $current_user = $this->get('security.context')->getToken()->getUser();
            $entity->setAuthor($current_user);
            
            $this->get('cms.content.content_manager')->save($entity);
            $this->get('cms.content.content_manager')->saveMeta($entity);
            
            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.content_updated.success'
            );
    
            if ($editForm->get('submit_stay')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_content_edit', array('id' => $entity->getId())));
            }
            
            return $this->redirect($this->generateUrl('admin_content'));
        }
        
        return $this->render(
            'ContentBundle:Content:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
            )
        );
    }
    
    /**
     * Deletes a Content entity.
     *
     * @Route("/delete/{id}", name="admin_content_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ContentBundle:Content')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        
        $em->remove($entity);
        $em->flush();
        
        return $this->redirect($this->generateUrl('admin_content'));
    }
    
    /**
     * Upload l'avatar de l'utilisateur
     * @return boolean
     *
     * @Route("/upload-thumbnail/{content}", name="admin_content_upload_thumbnail")
     */
    public function uploadThumbnailAction(Content $content, Request $request)
    {
        $file = $request->files->get('file');
        $fs = new Filesystem();
        $fileName = $file->getClientOriginalName();
        
        $thumbDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/thumbs/'.date('Y').'/'.date('m');
        
        
        if (!$fs->exists($thumbDir)) {
            $fs->mkdir($thumbDir);
        }
        if ($file->move($thumbDir, $fileName)) {
            $em = $this->getDoctrine()->getManager();
            $content->setThumbnail(date('Y').'/'.date('m').'/'.$fileName);
            $em->persist($content);
            $em->flush();
        }
        
        
        $response = new JsonResponse();
        $response->setData(array('status' => true));
        
        return $response;
    }
    
    /**
     * Récupère les tous les types de lien pour les afficher
     * dans la popup de l'éditeur
     * @return Template
     *
     * @Route("/get-links", name="admin_content_get_links")
     * @Method("GET")
     */
    public function getLinksAction()
    {
        $contents = $this->getDoctrine()->getRepository('ContentBundle:Content')->findBy(
            array(),
            array('language' => 'ASC', 'title' => 'ASC')
        );
        $categories = $this->getDoctrine()->getRepository('ContentBundle:Category')->getCategoriesLinks();
        
        return $this->render(
            'ContentBundle:Content:getLinks.html.twig',
            array('contents' => $contents, 'categories' => $categories)
        );
    }
    
    /**
     * @Route("/stats_type", name="admin_content_stats_type")
     */
    public function getStatsTypeAction()
    {
        $contents = $this->getDoctrine()->getRepository('ContentBundle:Content')->getCountByTaxonomy();
        $labels = array();
        $data   = array();
        foreach($contents as $content) {
            $labels[] = $content['title'];
            $data[] = $content['total'];
        }
        return new JsonResponse(array('labels' => $labels, 'data' => $data));
    }
    
    
    /**
     * @Route("/stats_content", name="admin_content_stats_content")
     */
    public function getStatsContentAction()
    {
        $contents = $this->getDoctrine()->getRepository('ContentBundle:Content')->getCountByMonth();
        $labels = array();
        $data   = array();
        foreach($contents as $content) {
            $labels[] = $content['month_created'].'/'.$content['year_created'];
            $data[] = $content['total'];
        }
        return new JsonResponse(array('labels' => $labels, 'data' => $data));
    }
}

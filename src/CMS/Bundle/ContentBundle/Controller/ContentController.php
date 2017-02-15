<?php

namespace CMS\Bundle\ContentBundle\Controller;

use CMS\Bundle\ContentBundle\Entity\Field;
use CMS\Bundle\ContentBundle\Entity\FieldValue;
use CMS\Bundle\ContentBundle\Entity\MetaValue;
use CMS\Bundle\ContentBundle\Event\ContentSavedEvent;
use CMS\Bundle\ContentBundle\Event\ContentSubscriber;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CMS\Bundle\ContentBundle\Entity\Content;
use CMS\Bundle\ContentBundle\Entity\Meta;
use CMS\Bundle\ContentBundle\Form\ContentType;
use CMS\Bundle\ContentBundle\Classes\ExtraMetas;
use CMS\Bundle\CoreBundle\Entity\Language;

use Symfony\Component\Filesystem\Filesystem;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Content controller.
 *
 * @Route("/admin/content")
 */
class ContentController extends Controller
{
    /**
     * Lists all Content entities.
     * @param $page
     * @param $nb_elem
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{nb_elem}/{page}", name="admin_content", requirements={"nb_elem": "\d+", "page": "\d+"}, defaults={"page": 1, "nb_elem": 10})
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function indexAction($nb_elem, $page, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $search = $request->get('q', '');

        $query = $em->getRepository('ContentBundle:Content')->getAllContentsNotTrashedQuery($search);

        $paginator  = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            $page,
            $nb_elem
        );

        $query_trash = $em->getRepository('ContentBundle:Content')->getAllContentsTrashedQuery($search);
        $page_trash = $request->query->get('page_trash', 1);
        $nb_elem_trash = $request->query->get('nb_elem_trash', 10);

        $paginator_trash  = $this->get('knp_paginator');
        $entities_trashed = $paginator_trash->paginate(
            $query_trash,
            $page_trash,
            $nb_elem_trash
        );

        $languages = $em->getRepository('CoreBundle:Language')->findAll();
        $taxonomies = $em->getRepository('ContentBundle:ContentTaxonomy')->findAll();

        $option = $em->getRepository('CoreBundle:Option')->findOneBy(array('option_name' => 'date_format'));

        $route_referer = $this->getRefererRoute($request);
        $notification = false;
        $lastPost = null;
        if ($route_referer == 'admin_content_new') {
            $lastPost = $this->getDoctrine()->getRepository('ContentBundle:Content')->findOneBy(array(), array('id' => 'desc'));
            if ($lastPost->getPublished()) {
                $notification = true;
            }
        }

        $template = $this->get('cms.core.option_manager')->get('theme', '');

        return $this->render(
            'ContentBundle:Content:index.html.twig',
            array(
                'entities' => $entities,
                'entities_trashed' => $entities_trashed,
                'url' => 'admin_content_delete',
                'languages' => $languages,
                'taxonomies' => $taxonomies,
                'date_format' => $option->getOptionValue(),
                'notification' => $notification,
                'lastPost' => $lastPost,
                'template' => $template
            )
        );
    }

    private function getRefererRoute(Request $request)
    {
        //look for the referer route
        $referer = $request->server->get('HTTP_REFERER');
        $path = substr($referer, strpos($referer, $request->getSchemeAndHttpHost()));
        $path = str_replace($request->getSchemeAndHttpHost(), '', $path);

        $matcher = $this->get('router')->getMatcher();
        try {
            $parameters = $matcher->match($path);
            $route = $parameters['_route'];
            return $route;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Creates a new Content entity.
     *
     * @Route("/", name="admin_content_create")
     * @Method("POST")
     * @Template("ContentBundle:Content:new.html.twig")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $content = new Content();

        $data = $request->request->get('content');
        $taxonomy = $data['taxonomy'];
        $taxonomy = $this->getDoctrine()->getRepository('ContentBundle:ContentTaxonomy')->find($taxonomy);
        $content->setTaxonomy($taxonomy);
        $metas = ExtraMetas::loadMetas($this->getDoctrine()->getRepository('ContentBundle:Meta'));

        $form = $this->createCreateForm($content, $taxonomy->getFields(), $metas);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $current_user = $this->get('security.token_storage')->getToken()->getUser();
            $content->setAuthor($current_user);
            if (is_null($content->getLanguage())) {
                $content->setLanguage($em->getRepository('CoreBundle:Language')->find(1));
            }

            $dispatcher = new EventDispatcher();
            $settings = array(
                'oauth_access_token' => $this->getParameter('content.access_token'),
                'oauth_access_token_secret' => $this->getParameter('content.access_token_secret'),
                'consumer_key' => $this->getParameter('content.consumer_key'),
                'consumer_secret' => $this->getParameter('content.consumer_secret'),
                'base_url' => $request->getSchemeAndHttpHost())
            ;
            $event = new ContentSavedEvent($content, $settings);
            $subscriber = new ContentSubscriber();
            $dispatcher->addSubscriber($subscriber);
            $dispatcher->dispatch(ContentSavedEvent::NAME, $event);


            $this->get('cms.content.content_manager')->save($content);
            $this->get('cms.content.content_manager')->saveMeta($this, $content, $request);

            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.content_created.success'
            );
            if ($form->get('submit_stay')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_content_edit', array('id' => $content->getId())));
            }
            return $this->redirect($this->generateUrl('admin_content'));
        }

        return $this->render(
            'ContentBundle:Content:new.html.twig',
            array(
                'entity' => $content,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Ajoute une traduction au contenu
     * @param  Content $content Contenu à traduire
     * @param \CMS\Bundle\CoreBundle\Entity\Language $language
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return mixed
     *
     * @Route("/translate/{content}/{language}", name="admin_translation_create")
     * @Method("POST")
     * @Template("ContentBundle:Content:new.html.twig")
     */
    public function createTranslationAction(Content $content, Language $language, Request $request)
    {
        $entity = new Content();
        $entity->setReferenceContent($content);
        $entity->setLanguage($language);
        $entity->setTaxonomy($content->getTaxonomy());
        $fields = $entity->getTaxonomy()->getFields();
        $metas = ExtraMetas::loadMetas($this);

        $form = $this->createTranslationForm($entity, $fields, $metas);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $current_user = $this->get('security.token_storage')->getToken()->getUser();
            $entity->setAuthor($current_user);

            $dispatcher = new EventDispatcher();
            $settings = array(
                'oauth_access_token' => $this->getParameter('content.access_token'),
                'oauth_access_token_secret' => $this->getParameter('content.access_token_secret'),
                'consumer_key' => $this->getParameter('content.consumer_key'),
                'consumer_secret' => $this->getParameter('content.consumer_secret'),
                'base_url' => $request->getSchemeAndHttpHost())
            ;
            $event = new ContentSavedEvent($content, $settings);
            $subscriber = new ContentSubscriber();
            $dispatcher->addSubscriber($subscriber);
            $dispatcher->dispatch(ContentSavedEvent::NAME, $event);
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
     * @param array $fields
     * @param array $metas
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Content $entity, $fields = array(), $metas = array())
    {
        $form = $this->createForm(
            ContentType::class,
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
        $form->add('submit_stay', SubmitType::class, array('label' => 'Create and stay', 'attr' => array('class' => 'btn btn-info btn-fill')));
        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));
        $form->add('submit_seo', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));

        return $form;
    }

    /**
     * Creates a form to create a translation of a Content entity
     *
     * @param Content $entity The entity
     * @param array $fields
     * @param array $metas
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

        $form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));

        return $form;
    }

    /**
     * Displays a form to create a new Content entity.
     * @param string $contenttaxonomy
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new/{contenttaxonomy}", name="admin_content_new", defaults={"contenttaxonomy": ""})
     * @Method("GET")
     * @Template()
     */
    public function newAction($contenttaxonomy)
    {
        $em = $this->getDoctrine()->getManager();
        $content = new Content();
        $contenttaxonomy = $em->getRepository('ContentBundle:ContentTaxonomy')->findOneBy(
            array('alias' => $contenttaxonomy)
        );
        $content->setTaxonomy($contenttaxonomy);
        $current_user = $this->get('security.token_storage')->getToken()->getUser();
        $content->setAuthor($current_user);
        $fields = $content->getTaxonomy()->getFields();
        $metas = ExtraMetas::loadMetas($this->getDoctrine()->getRepository('ContentBundle:Meta'));

        $form = $this->createCreateForm($content, $fields, $metas);

        return $this->render(
            'ContentBundle:Content:new.html.twig',
            array(
                'entity' => $content,
                'contenttaxonomy' => $contenttaxonomy,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new Content entity.
     * @param \CMS\Bundle\ContentBundle\Entity\Content $content
     * @param \CMS\Bundle\CoreBundle\Entity\Language $language
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/new/{content}/{language}", name="admin_translation_new")
     * @Method("GET")
     * @Template("ContentBundle:Content:new.html.twig")
     */
    public function newTranslationAction(Content $content, Language $language)
    {
        $entity = new Content();
        $entity->setReferenceContent($content);
        $entity->setLanguage($language);
        $entity->setTaxonomy($content->getTaxonomy());
        $current_user = $this->get('security.token_storage')->getToken()->getUser();
        $entity->setAuthor($current_user);
        $fields = $entity->getTaxonomy()->getFields();
        $metas = ExtraMetas::loadMetas($this);
        $form = $this->createTranslationForm($entity, $fields, $metas);

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
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @param ArrayCollection|Field[] $fields
     * @param ArrayCollection|FieldValue[] $fieldvalues
     * @param ArrayCollection|Meta[] $metas
     * @param ArrayCollection|MetaValue[] $metavalues
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Content $entity, $fields, $fieldvalues, $metas, $metavalues)
    {
        $form = $this->createForm(
            ContentType::class,
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
            SubmitType::class,
            array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        );

        $form->add(
            'submit_seo',
            SubmitType::class,
            array('label' => 'Update', 'attr' => array('class' => 'btn btn-info btn-fill pull-right'))
        );

        $form->add(
            'submit_stay',
            SubmitType::class,
            array('label' => 'Update and stay', 'attr' => array('class' => 'btn btn-info pull-right'))
        );

        return $form;
    }

    /**
     * Edits an existing Content entity.
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}", name="admin_content_update")
     * @Method("PUT")
     * @Template("ContentBundle:Content:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('ContentBundle:Content')->find($id);

        if (!$content) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $fieldvalues = $em->getRepository('ContentBundle:FieldValue')->findFielvalueByContent($content);
        $fields = $em->getRepository('ContentBundle:Field')->findByTaxonomyIndexed($content->getTaxonomy());

        $metavalues = $em->getRepository('ContentBundle:MetaValue')->findMetavalueByContent($content);
        $metas = $em->getRepository('ContentBundle:Meta')->findByIndexed();

        $editForm = $this->createEditForm($content, $fields, $fieldvalues, $metas, $metavalues);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $current_user = $this->get('security.token_storage')->getToken()->getUser();
            $content->setAuthor($current_user);

            $dispatcher = new EventDispatcher();
            $settings = array(
                'oauth_access_token' => $this->getParameter('content.access_token'),
                'oauth_access_token_secret' => $this->getParameter('content.access_token_secret'),
                'consumer_key' => $this->getParameter('content.consumer_key'),
                'consumer_secret' => $this->getParameter('content.consumer_secret'),
                'base_url' => $request->getSchemeAndHttpHost())
            ;
            $event = new ContentSavedEvent($content, $settings);
            $subscriber = new ContentSubscriber();
            $dispatcher->addSubscriber($subscriber);
            $dispatcher->dispatch(ContentSavedEvent::NAME, $event);

            $this->get('cms.content.content_manager')->save($content);
            $this->get('cms.content.content_manager')->saveMeta($this, $content, $request);



            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.content_updated.success'
            );

            if ($editForm->get('submit_stay')->isClicked()) {
                return $this->redirect($this->generateUrl('admin_content_edit', array('id' => $content->getId())));
            }

            return $this->redirect($this->generateUrl('admin_content'));
        }

        return $this->render(
            'ContentBundle:Content:edit.html.twig',
            array(
                'entity' => $content,
                'edit_form' => $editForm->createView(),
            )
        );
    }

    /**
     * Deletes a Content entity.
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/delete/{id}", name="admin_content_delete")
     */
    public function deleteAction($id)
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
     * @param \CMS\Bundle\ContentBundle\Entity\Content $content
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @Route("/upload-thumbnail/{content}", name="admin_content_upload_thumbnail")
     */
    public function uploadThumbnailAction(Content $content, Request $request)
    {
        $file = $request->files->get('file');
        $fs = new Filesystem();
        $fileName = $file->getClientOriginalName();

        $thumbDir = $this->getParameter('kernel.root_dir').'/../web/uploads/thumbs/'.date('Y').'/'.date('m');


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

    /**
     * Exécute une action sur tous les contenus sélectionnés
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/bulk", name="admin_content_bulk_action")
     * @Method("POST")
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->request->get('contents');
        $action = $request->request->get('action');

        switch($action)
        {
            case 'trash':
                $done = $this->getDoctrine()->getRepository('ContentBundle:Content')->updateStatus($ids, 5);
                if ($done) {
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'cms.content.contents_trashed.success'
                    );
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'cms.content.contents_trashed.error'
                    );
                }
                break;
            case 'delete':
                $done = $this->getDoctrine()->getRepository('ContentBundle:Content')->deleteTotally($ids);
                if ($done) {
                    $this->get('session')->getFlashBag()->add(
                        'success',
                        'cms.content.contents_deleted.success'
                    );
                } else {
                    $this->get('session')->getFlashBag()->add(
                        'error',
                        'cms.content.contents_deleted.error'
                    );
                }
                break;
        }
        return $this->redirectToRoute('admin_content');
    }
}

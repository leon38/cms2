<?php

namespace CMS\Bundle\FrontBundle\Controller;

use CMS\Bundle\ContentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FrontController extends Controller
{
    private $_theme;
    private $_title;
    private $_date_format;
    private $_parameters;

    public function init()
    {
        $this->_theme = $this->get('cms.core.option_manager')->get('theme', '');
        $this->_title = $this->get('cms.core.option_manager')->get('sitename', '');
        $this->_date_format = $this->get('cms.core.option_manager')->get('date_format', '');
        $this->_parameters = array('sitename' => $this->_title, 'date_format' => $this->_date_format);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="home_index")
     * @Route("/index.{_format}", name="home", defaults={"_format": "html"})
     */
    public function indexAction()
    {
        $this->init();
        $em = $this->getDoctrine()->getManager();
        $contents = $em->getRepository('ContentBundle:Content')->findBy(
            array('published' => true),
            array('created' => 'DESC')
        );

        $featured = $em->getRepository('ContentBundle:Content')->findBy(array('published' => 1, 'featured' => true));

        $categories = $em->getRepository('ContentBundle:Category')->getAll(true);
        $parameters = array_merge(
            $this->_parameters,
            array(
                'title' => $this->_title,
                'theme' => $this->_theme,
                'contents' => $contents,
                'categories' => $categories,
                'featured' => $featured,
                'tiny_url' => $this->get('cms.front.tools')->getTinyUrl($this->generateUrl("home", array(), true))
            )
        );
        if ($this->get('templating')->exists('@cms/'.$this->_theme.'/home.html.twig')) {
            return $this->render('@cms/'.$this->_theme.'/home.html.twig', $parameters);
        }
        return $this->render('@cms/'.$this->_theme.'/category.html.twig', $parameters);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/recherche.{_format}", name="front_search", defaults={"_format": "html"})
     */
    public function searchAction(Request $request)
    {
        $this->init();
        $contents = $this->getDoctrine()->getRepository('ContentBundle:Content')->search($request->get('q'));
        $parameters = array_merge(
            $this->_parameters,
            array(
                'title' => $this->_title,
                'theme' => $this->_theme,
                'contents' => $contents
            )
        );
        if ($this->get('templating')->exists('@cms/'.$this->_theme.'/search.html.twig')) {
            return $this->render('@cms/'.$this->_theme.'/search.html.twig', $parameters);
        }
    }

    /**
     * @param $categoryName
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/category/{categoryName}.{_format}", name="front_category", defaults={"_format": "html"})
     */
    public function categoryAction($categoryName)
    {
        $this->init();
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ContentBundle:Category')->findOneBy(array('url' => $categoryName));
        if ($category !== null) {
            $parameters = array_merge(
                $this->_parameters,
                array(
                    'title' => $category->getTitle(),
                    'theme' => $this->_theme,
                    'contents' => $category->getContents(),
                    'tiny_url' => $this->get('cms.front.tools')->getTinyUrl($this->generateUrl("front_single", array("categoryName" => $categoryName), true))
                )
            );

            return $this->render('@cms/'.$this->_theme.'/category.html.twig', $parameters);
        }
        throw new NotFoundHttpException("La catégorie n'existe pas");
    }

    public function createCreateForm($entity)
    {
        $comment_form = $this->createForm(
            CommentType::class,
            $entity,
            array(
                'action' => $this->generateUrl('add_comment'),
                'method' => 'POST',
                'attr' => array('class' => 'form'),
            )
        );

        $comment_form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill')));
        return $comment_form;
    }

    /**
     * @param Request $request
     * @Route("/add/comment", name="add_comment")
     */
    public function addCommentAction(Request $request)
    {
        $comment = new Comment();
        $comment_form = $this->createCreateForm($comment);
        $comment_form->handleRequest($request);

        if ($comment_form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('front_single');
    }

    /**
     * Affiche le post ou les posts de la catégorie
     * @param  String $alias Alias d'une catégorie ou d'un post
     * @return Response Renvoie la vue avec le bon template selon l'alias
     *
     * @Route("/{alias}.{_format}", name="front_single", defaults={"_format": "html"},)
     */
    public function singleAction($alias, Request $request)
    {
        $this->init();
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('ContentBundle:Content')->findOneBy(array('url' => $alias));
        if (is_null($content)) {
            $category = $em->getRepository('ContentBundle:Category')->findOneBy(array('url' => $alias));
            if (is_null($category)) {
                $taxonomy = $em->getRepository('ContentBundle:ContentTaxonomy')->findOneBy(array('alias' => $alias));
                if (!is_null($taxonomy)) {
                    $parameters = array_merge(
                        $this->_parameters,
                        array(
                            'title' => $taxonomy->getTitle(),
                            'theme' => $this->_theme,
                            'contents' => $taxonomy->getContents(),
                            'tiny_url' => $this->get('cms.front.tools')->getTinyUrl($this->generateUrl("front_single", array("alias" => $alias), true))
                        )
                    );
                    if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$taxonomy->getAlias().'.html.twig')) {
                        return $this->render('@cms/'.$this->_theme.'/'.$taxonomy->getAlias().'.html.twig', $parameters);
                    }

                    return $this->render('@cms/'.$this->_theme.'/archive.html.twig', $parameters);
                }
                throw new NotFoundHttpException("Page not found");
            }
            $categories = $em->getRepository('ContentBundle:Category')->getAll(true);
            $contents = $em->getRepository('ContentBundle:Content')->getAllContents($category);


            $comment_form = $this->createCreateForm(new Comment());

            $parameters = array_merge(
                $this->_parameters,
                array(
                    'title' => $category->getTitle(),
                    'category' => $category,
                    'theme' => $this->_theme,
                    'contents' => $contents,
                    'categories' => $categories,
                    'tiny_url' => $this->get('cms.front.tools')->getTinyUrl($this->generateUrl("front_single", array("alias" => $alias), true)),
                    'comment_form' => $comment_form
                )
            );

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$category->getUrl().'.html.twig')) {
                return $this->render('@cms/'.$this->_theme.'/category-'.$category->getUrl().'.html.twig', $parameters);
            }

            return $this->render('@cms/'.$this->_theme.'/category.html.twig', $parameters);
        }

        if ($content !== null) {
            $parameters = array_merge(
                $this->_parameters,
                array('title' => $content->getTitle(), 'theme' => $this->_theme, 'content' => $content)
            );
            $taxonomy = $content->getTaxonomy();

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/single-'.$taxonomy->getAlias().'.html.twig')) {
                return $this->render('@cms/'.$this->_theme.'/single-'.$taxonomy->getAlias().'.html.twig', $parameters);
            }

            return $this->render('@cms/'.$this->_theme.'/single.html.twig', $parameters);
        }

        throw new NotFoundHttpException("La page n'existe pas");
    }



}
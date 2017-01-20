<?php

namespace CMS\Bundle\FrontBundle\Controller;

use CMS\Bundle\ContentBundle\Entity\Comment;
use CMS\Bundle\ContentBundle\Entity\Content;
use CMS\Bundle\ContentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @param string $_format
     * @return Response
     * @Route("/", name="home_index")
     * @Route("/index.{_format}", name="home", defaults={"_format": "html"})
     */
    public function indexAction($_format = "html")
    {
        $this->init();

        $em = $this->getDoctrine()->getManager();
        $metas["title"] = '<title>'.$em->getRepository('CoreBundle:Option')->get('sitename').'</title>';
        $metas["meta_description"] = '<meta name="description" value="'.$em->getRepository('CoreBundle:Option')->get('meta_description').'" />';
        $metas["meta_keywords"] = '<meta name="keywords" value="'.$em->getRepository('CoreBundle:Option')->get('meta_keywords').'" />';

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
                'metas' => $metas,
                'theme' => $this->_theme,
                'contents' => $contents,
                'categories' => $categories,
                'featured' => $featured,
                'tiny_url' => $this->get('cms.front.tools')->getTinyUrl($this->generateUrl("home", array(), true))
            )
        );
        if ($this->get('templating')->exists('@cms/'.$this->_theme.'/home.html.twig')) {
            return $this->render('@cms/'.$this->_theme.'/home.'.$_format.'.twig', $parameters);
        }
        return $this->render('@cms/'.$this->_theme.'/category.'.$_format.'.twig', $parameters);
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

    /**
     * Renvoie la page dans le format amp
     * @param $alias
     * @return Response
     *
     * @Route("/amp/{alias}.{_format}", name="single_amp_content", defaults={"_format": "html"})
     */
    public function ampSingleAction($alias)
    {
        $this->init();
        $content = $this->getDoctrine()->getRepository('ContentBundle:Content')->findOneBy(array('url' => $alias));
        $parameters = array_merge(
            array(
                'content' => $content
            ),$this->_parameters);
        return $this->render('@cms/'.$this->_theme.'/amp/single.amp.html.twig', $parameters);
    }

    public function createCreateForm(Comment $entity, Content $content)
    {
        $comment_form = $this->createForm(
            CommentType::class,
            $entity,
            array(
                'method' => 'POST',
                'attr' =>
                    array(
                        'class' => 'form',
                        'data-action' => $this->generateUrl('add_comment', array('content' => $content->getId()))
                    )
            )
        );
        $comment_form->add('submit', SubmitType::class, array('label' => 'Create', 'attr' => array('class' => 'btn btn-info btn-fill pull-right')));
        return $comment_form;
    }

    /**
     * @param Content $content
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/add/comment/{content}", name="add_comment")
     */
    public function addCommentAction(Content $content, Request $request)
    {
        $this->init();
        $comment = new Comment();
        $comment_form = $this->createCreateForm($comment, $content);
        $comment_form->handleRequest($request);

        if ($comment_form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $comment->setContent($content);
            $em->persist($comment);
            $em->flush();
        } else {
            $this->get('session')->getFlashBag()->add(
                'error',
                $comment_form->getErrors(true, true)
            );
        }
        return $this->redirectToRoute('front_single', array('alias' => $content->getUrl()));
    }

    /**
     * Add 1 Like to a comment.
     * @param Comment $comment
     * @return JsonResponse
     *
     * @Route("/add/like/{comment}", name="add_like")
     */
    public function addLove(Comment $comment)
    {
        if (is_null($comment)) {
            return new JsonResponse(array('status' => 'ERROR', 'error' => 'cms.content.comment.not_exist'));
        }
        $likes = $comment->getLikes();
        $likes++;
        $comment->setLikes($likes);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();
        return new JsonResponse(array('status' => 'SUCCESS', 'likes' => $likes));
    }


    /**
     * Add 1 Like to a comment.
     * @param Content $content
     * @return JsonResponse
     *
     * @Route("/add/like/content/{content}", name="add_like_content")
     */
    public function addContentLove(Content $content)
    {
        if (is_null($content)) {
            return new JsonResponse(array('status' => 'ERROR', 'error' => 'cms.content.content.not_exist'));
        }
        $likes = $content->getLikes();
        $likes++;
        $content->setLikes($likes);
        $em = $this->getDoctrine()->getManager();
        $em->persist($content);
        $em->flush();
        return new JsonResponse(array('status' => 'SUCCESS', 'likes' => $likes));
    }

    /**
     * Affiche le post ou les posts de la catégorie
     * @param  String $alias Alias d'une catégorie ou d'un post
     * @param string $_format
     * @return Response Renvoie la vue avec le bon template selon l'alias
     *
     * @Route("/{alias}.{_format}", name="front_single", defaults={"_format": "html", "format": "html"})
     */
    public function singleAction($alias, $_format = "html")
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
                    if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$taxonomy->getAlias().'.'.$_format.'.twig')) {
                        return $this->render('@cms/'.$this->_theme.'/'.$taxonomy->getAlias().'.'.$_format.'.twig', $parameters);
                    }

                    return $this->render('@cms/'.$this->_theme.'/archive.'.$_format.'.twig', $parameters);
                }
                throw new NotFoundHttpException("Page not found");
            }
            $categories = $em->getRepository('ContentBundle:Category')->getAll(true);
            $contents = $em->getRepository('ContentBundle:Content')->getAllContents($category);

            $parameters = array_merge(
                $this->_parameters,
                array(
                    'title' => $category->getTitle(),
                    'category' => $category,
                    'theme' => $this->_theme,
                    'contents' => $contents,
                    'categories' => $categories,
                    'tiny_url' => $this->get('cms.front.tools')->getTinyUrl($this->generateUrl("front_single", array("alias" => $alias), true)),
                )
            );

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$category->getUrl().'.'.$_format.'.twig')) {
                return $this->render('@cms/'.$this->_theme.'/category-'.$category->getUrl().'.'.$_format.'.twig', $parameters);
            }

            return $this->render('@cms/'.$this->_theme.'/category'.$_format.'.twig', $parameters);
        }

        if ($content !== null) {
            $comment_form = $this->createCreateForm(new Comment(), $content);
            $parameters = array_merge(
                $this->_parameters,
                array(
                    'title' => $content->getTitle(),
                    'theme' => $this->_theme,
                    'content' => $content,
                    'comment_form' => $comment_form->createView()
                )
            );
            $taxonomy = $content->getTaxonomy();

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/single-'.$taxonomy->getAlias().'.'.$_format.'.twig')) {
                return $this->render('@cms/'.$this->_theme.'/single-'.$taxonomy->getAlias().'.'.$_format.'.twig', $parameters);
            }

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$content->getUrl().'.'.$_format.'.twig')) {
                return $this->render('@cms/'.$this->_theme.'/'.$content->getUrl().'.'.$_format.'.twig', $parameters);
            }

            return $this->render('@cms/'.$this->_theme.'/single.'.$_format.'.twig', $parameters);
        }

        throw new NotFoundHttpException("La page n'existe pas");
    }



}
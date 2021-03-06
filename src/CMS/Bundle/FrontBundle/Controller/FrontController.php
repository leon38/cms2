<?php

namespace CMS\Bundle\FrontBundle\Controller;

use CMS\Bundle\ContentBundle\Entity\Comment;
use CMS\Bundle\ContentBundle\Entity\Content;
use CMS\Bundle\ContentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * @param Request $request
     * @return Response
     * @Route("/", name="home_index")
     * @Route("/index.{_format}", name="home", defaults={"_format": "html"})
     */
    public function indexAction($_format = "html", Request $request)
    {
        $response = new Response();
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $this->init();

        $em = $this->getDoctrine()->getManager();
        $metas["title"] = '<title>'.$em->getRepository('CoreBundle:Option')->get('sitename').'</title>';
        $metas["meta_description"] = '<meta name="description" content="'.$em->getRepository('CoreBundle:Option')->get('meta_description').'" />';
        $metas["og_description"] = '<meta property="og:description" content="'.$em->getRepository('CoreBundle:Option')->get('meta_description').'" />';
        $metas["og_title"] = '<meta property="og:title" content="'.$em->getRepository('CoreBundle:Option')->get('sitename').'" />';
        $metas["meta_keywords"] = '<meta name="keywords" content="'.$em->getRepository('CoreBundle:Option')->get('meta_keywords').'" />';

        $contents = $em->getRepository('ContentBundle:Content')->findBy(
            array('published' => true),
            array('created' => 'DESC')
        );

        $last_modified = $contents[0]->getModified();
        $date = new \DateTime('now');
        $date->add(new \DateInterval('P1D'));
        $response->setExpires($date);
        $response->setEtag("1".$last_modified->getTimestamp());

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

        $response->setLastModified($contents[0]->getCreated());
        $response->setPublic();
        if ($response->isNotModified($request)) {
            return $response;
        }

        if ($this->get('templating')->exists('@cms/'.$this->_theme.'/home.html.twig')) {
            return $this->render('@cms/'.$this->_theme.'/home.'.$_format.'.twig', $parameters, $response);
        }
        return $this->render('@cms/'.$this->_theme.'/category.'.$_format.'.twig', $parameters, $response);
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

            $response = $this->render('@cms/'.$this->_theme.'/category.html.twig', $parameters);
            // cache for 3600 seconds
            $response->setSharedMaxAge(3600);

            // (optional) set a custom Cache-Control directive
            $response->headers->addCacheControlDirective('must-revalidate', true);
            return $response;
        }
        throw new NotFoundHttpException("La catégorie n'existe pas");
    }

    /**
     * Renvoie la page dans le format amp
     * @param $alias
     * @return Response
     *
     * @Route("/amp/{alias}.{_format}", name="single_amp_content", defaults={"_format": "amp"})
     */
    public function ampSingleAction($alias)
    {
        $this->init();
        $content = $this->getDoctrine()->getRepository('ContentBundle:Content')->findOneBy(array('url' => $alias));
        $parameters = array_merge(
            array(
                'content' => $content
            ),$this->_parameters);
        $response = $this->render('@cms/'.$this->_theme.'/amp/single.amp.html.twig', $parameters);
        // cache for 3600 seconds
        $response->setSharedMaxAge(3600);

        // (optional) set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
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
     * @param Request $request
     * @return Response Renvoie la vue avec le bon template selon l'alias
     *
     * @Route("/{alias}.{_format}", name="front_single", requirements={"_format": "html|amp.html"}, defaults={"_format": "html"})
     */
    public function singleAction($alias, $_format = "html", Request $request)
    {
        $response = new Response();
        $response->setSharedMaxAge(3600);

        $response->headers->addCacheControlDirective('must-revalidate', true);

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
                        return $this->render('@cms/'.$this->_theme.'/'.$taxonomy->getAlias().'.'.$_format.'.twig', $parameters, $response);
                    }
                    return $this->render('@cms/'.$this->_theme.'/archive.'.$_format.'.twig', $parameters, $response);
                }
                throw new NotFoundHttpException("Page not found");
            }
            $date = new DateTime('now');
            $date->add(new DateInterval('P1D'));
            $categories = $em->getRepository('ContentBundle:Category')->getAll(true);
            $contents = $em->getRepository('ContentBundle:Content')->getAllContents($category);

            $last_modified = $contents[0]->getModified();
            $date = new \DateTime('now');
            $date->add(new \DateInterval('P1D'));
            $response->setExpires($date);
            $response->setEtag($category->getId().$last_modified->getTimestamp());

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

            $response->setLastModified($last_modified);
            $response->setPublic();
            if ($response->isNotModified($request)) {
                return $response;
            }

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$category->getUrl().'.'.$_format.'.twig')) {
                return $this->render('@cms/'.$this->_theme.'/category-'.$category->getUrl().'.'.$_format.'.twig', $parameters, $response);
            }

            return $this->render('@cms/'.$this->_theme.'/category.'.$_format.'.twig', $parameters, $response);
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
            $date = new \DateTime('now');
            $date->add(new \DateInterval('P180D'));
            $taxonomy = $content->getTaxonomy();
            $response->setLastModified($content->getModified());
            $response->setExpires($date);
            $response->setETag($content->getId().$content->getModified()->getTimestamp());
            $response->setPublic();
            if ($response->isNotModified($request)) {
                return $response;
            }

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/single-'.$taxonomy->getAlias().'.'.$_format.'.twig')) {
                return $this->render('@cms/'.$this->_theme.'/single-'.$taxonomy->getAlias().'.'.$_format.'.twig', $parameters, $response);
            }

            if ($this->get('templating')->exists('@cms/'.$this->_theme.'/'.$content->getUrl().'.'.$_format.'.twig')) {
                return $this->render('@cms/'.$this->_theme.'/'.$content->getUrl().'.'.$_format.'.twig', $parameters, $response);
            }

            return $this->render('@cms/'.$this->_theme.'/single.'.$_format.'.twig', $parameters, $response);
        }

        throw new NotFoundHttpException("La page n'existe pas");
    }

    public function createCreateForm(Comment $entity, Content $content)
    {
        $comment_form = $this->createForm(
            CommentType::class,
            $entity,
            array(
                'method' => 'POST',
                'action' => $this->generateUrl('add_comment', array('content' => $content->getId())),
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

}
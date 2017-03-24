<?php

namespace CMS\Bundle\FrontBundle\Controller;

use CMS\Bundle\ContentBundle\Entity\Comment;
use CMS\Bundle\ContentBundle\Entity\Content;
use CMS\Bundle\ContentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CommentController extends Controller
{
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
            $this->get('session')->getFlashBag()->add(
                'success',
                'cms.content.comment.success'
            );
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

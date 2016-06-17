<?php
namespace CMS\Bundle\RestAPIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;

class ContentsController extends FOSRestController
{
    /**
     * @return array
     * @View()
     */
    public function getContentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contents = $em->getRepository('ContentBundle:Content')->findBy(array('published' => 1));
        $tmp_contents = array();
        foreach($contents as $content) {
            if ($content->getThumbnail() != '') {
              $imagemanagerResponse = $this->container->get('liip_imagine.controller')->filterAction(new Request(), $content->getWebPath(), 'thumb_list');
              $url = $imagemanagerResponse->getTargetUrl();
              $content->setThumbnail($url);
            }
            $tmp_contents[] = $content;
        }
        $view = $this->view($tmp_contents, 200);
        return $this->handleView($view);
    }

    /**
     * @return array
     * @param  String $alias alias du contenu
     * @return View
     *
     * @Get("/{slug}")
     */
    public function getContentAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository('ContentBundle:Content')->findOneBy(array('published' => 1, 'url' => $slug));
        $view = $this->view($content, 200);
        return $this->handleView($view);
    }
}
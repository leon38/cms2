<?php

namespace CMS\Bundle\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    /**
 * @return \Symfony\Component\HttpFoundation\Response
 * @Route("/sitemap.{_format}", name="sitemap", requirements={"_format": "xml"})
 */
    public function indexAction()
    {
        $query = $this->getDoctrine()->getRepository('ContentBundle:Content')->getAllContentsNotTrashedQuery();
        $contents = $query->getResult();
        $categories = $this->getDoctrine()->getRepository('ContentBundle:Category')->findBy(array('published' => 1));
        $response = new Response();
        $response->headers->set('Content-Type', 'application/xml; charset=utf-8');
        return $this->render('FrontBundle:Sitemap:sitemap.xml.twig', array('contents' => $contents, 'categories' => $categories), $response);
    }


}

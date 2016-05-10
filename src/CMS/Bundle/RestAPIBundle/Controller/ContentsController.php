<?php
namespace CMS\Bundle\RestAPIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ContentsController extends FOSRestController
{
    /**
     * @return array
     * @View()
     */
    public function getContentsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $contents = $em->getRepository('ContentBundle:Content')->findBy(array('published' => true));
        return array('contents' => $contents);
    }
}
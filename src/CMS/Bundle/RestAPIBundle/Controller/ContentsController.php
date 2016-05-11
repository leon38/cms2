<?php
namespace CMS\Bundle\RestAPIBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use JMS\Serializer\SerializerBuilder;

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

        $serializer = SerializerBuilder::create()->build();
        $contents = $serializer->serialize($contents, 'json');
        $contents = $serializer->deserialize($contents, 'Doctrine\Common\Collections\ArrayCollection', 'json');
        return array('contents' => $contents);
    }
}
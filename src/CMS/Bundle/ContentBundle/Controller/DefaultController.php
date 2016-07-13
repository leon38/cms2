<?php

namespace CMS\Bundle\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
  /**
   * @Route("/admin/changeProp/{id}/{repo}/{prop}", name="admin_content_change_prop")
   */
  public function indexAction($id, $repo, $prop)
  {
    $func_set = "set" . ucfirst($prop);
    $func_get = "get" . ucfirst($prop);
    $obj = $this->getDoctrine()->getRepository($repo)->find($id);
    $new_value = !call_user_func(array($obj, $func_get));
    call_user_func(array($obj, $func_set), $new_value);
    $response = new JsonResponse();
    $response->setData(array('status' => true));
    return $response;
  }
}

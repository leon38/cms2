<?php

namespace CMS\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    /**
     * @Route("/admin/change-statut/{id}/{repo}/{property}", name="core_change_statut")
     */
    public function changeStatutAction($id, $repo, $property)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($repo)->find($id);
        if ($entity !== null) {
        	$func_get = 'get'.ucfirst($property);
        	$func_set = 'set'.ucfirst($property);
        	$old_value = call_user_func(array($entity, $func_get));
        	call_user_func(array($entity, $func_set), !$old_value);
        	$em->persist($entity);
        	$em->flush();
        	return new JsonResponse(array('status' => true));
        }
        return new JsonResponse(array('status' => false, 'message' => "L'entité n'existe pas"));
    }
}

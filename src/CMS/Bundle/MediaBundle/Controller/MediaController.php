<?php

namespace CMS\Bundle\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use CMS\Bundle\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * @Route("/admin/media")
 */
class MediaController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$media = $em->getRepository('MediaBundle:Media')->findBy(array(), array('dateAdded' => 'desc'));
    	$medium = new Media();
    	$form = $this->createForm('CMS\Bundle\MediaBundle\Form\MediaType', $medium);
        return $this->render('MediaBundle:Media:index.html.twig', array('media' => $media, 'form' => $form->createView()));
    }


    /**
     * Upload l'avatar de l'utilisateur
     * @return boolean
     *
     * @Route("/upload-media", name="admin_media_upload")
     * 
     */
    public function uploadMediaAction(Request $request)
    {

        $languages = $this->getDoctrine()->getRepository('CoreBundle:Language')->findAll();
        $file = $request->files->get('file');
        $fs = new Filesystem();
        $fileName = $file->getClientOriginalName();
        $name = pathinfo($fileName, PATHINFO_FILENAME);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);

        $thumbDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/thumbs/'.date('Y').'/'.date('m');


        if (!$fs->exists($thumbDir)) {
            $fs->mkdir($thumbDir);
        }
        $i = 1;
        while ($fs->exists($thumbDir.'/'.$fileName)) {
            $fileName = $name.'-'.$i.'.'.$ext;
            $i++;
        }
        if ($file->move($thumbDir, $fileName)) {
            $em = $this->getDoctrine()->getManager();
            $medium = new Media();
            $fileName = date('Y').'/'.date('m').'/'.$fileName;
            $medium->setPath($fileName);
            $metas = array();
            foreach($languages as $language) {
                $metas['alt_'.$language->getId()] = str_replace('-', ' ', $name);
                $metas['title_'.$language->getId()] = str_replace('-', ' ', $name);
            }
            $medium->setMetas($metas);
            $em->persist($medium);
            $em->flush();
            $response = new JsonResponse();
            $response->setData(array('status' => true, 'media' => $medium));
            return $response;
        }



        $response = new JsonResponse();
        $response->setData(array('status' => false));
        return $response;
    }
}

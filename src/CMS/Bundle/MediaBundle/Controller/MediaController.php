<?php

namespace CMS\Bundle\MediaBundle\Controller;

use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;

use CMS\Bundle\MediaBundle\Entity\Media;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;


/**
 * @Route("/admin/media")
 */
class MediaController extends Controller
{
  /**
   * @Route("/", name="admin_media_index")
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $media = $em->getRepository('MediaBundle:Media')->findBy(array(), array('dateAdded' => 'desc'));
    $medium = new Media();
    $form = $this->createForm('CMS\Bundle\MediaBundle\Form\MediaType', $medium);
    return $this->render('MediaBundle:Media:index.html.twig', array(
      'media' => $media,
      'form' => $form->createView(),
      'url' => 'admin_media_delete',
    ));
  }

  /**
   * @Route("/resize", name="admin_media_resize")
   */
  public function resizeAllMediaAction()
  {
    $response = new StreamedResponse();
    $response->setCallback(function () {
      $option_manager = $this->get('cms.media.media_option_manager');
      $options = $option_manager->getAllOptions();
      $dir = "/web/uploads/thumbs/*";
      $finder = new Finder();
      $finder->files()->in($this->container->getParameter('kernel.root_dir') . '/..' . $dir);
      $i = 0;
      $nb_files = $finder->count() * count($options);
      ob_get_flush();
      foreach ($finder as $file) {
        foreach ($options as $option) {
          if (!is_dir($this->container->getParameter('kernel.root_dir') . '/../web/uploads/' . $option->getOptionName())) {
            mkdir($this->container->getParameter('kernel.root_dir') . '/../web/uploads/' . $option->getOptionName());
          }
          $sizes = json_decode($option->getOptionValue());
          $image = $this->_resizeThumb($sizes->width, $sizes->height, $this->container->getParameter('kernel.root_dir') . '/../web/uploads/thumbs/2016/' . $file->getRelativePathname());
          $image->save($this->container->getParameter('kernel.root_dir') . '/../web/uploads/' . $option->getOptionName() . '/' . $file->getFilename());
          $image->__destruct();
          $i++;
          echo json_encode(array("percent" => (int)(($i / $nb_files) * 100), "filename" => $file->getFilename()));
          flush();
          sleep(1);
        }
      }
    });
    $response->send();
    return $response;
  }

  /**
   * @param Media $media
   * @Route("/delete/{id}", name="admin_media_delete")
   */
  public function deleteMediaAction($id)
  {
    $option_manager = $this->get('cms.media.media_option_manager');
    $options = $option_manager->getAllOptions();
    $em = $this->getDoctrine()->getManager();
    $media = $em->getRepository('MediaBundle:Media')->find($id);
    $origin_path = $media->getWebPath();
    $filename = basename($origin_path);
    $fs = new Filesystem();
    foreach($options as $option) {
      $fs->remove($this->container->getParameter('kernel.root_dir') . '/../web/uploads/'.$option->getOptionName().'/'.$filename);
    }

    $fs->remove($this->container->getParameter('kernel.root_dir') . '/../web'.$origin_path);
    $em->remove($media);
    $em->flush();
    $response = new JsonResponse();
    $response->setData(array('status' => true));
    return $response;
  }

  private function _resizeMedia($file)
  {
    $option_manager = $this->get('cms.media.media_option_manager');
    $options = $option_manager->getAllOptions();
    foreach ($options as $option) {
      if (!is_dir($this->container->getParameter('kernel.root_dir') . '/../web/uploads/' . $option->getOptionName())) {
        mkdir($this->container->getParameter('kernel.root_dir') . '/../web/uploads/' . $option->getOptionName());
      }
      $sizes = json_decode($option->getOptionValue());
      $image = $this->_resizeThumb($sizes->width, $sizes->height, $file->getPathname());
      $image->save($this->container->getParameter('kernel.root_dir') . '/../web/uploads/' . $option->getOptionName() . '/' . $file->getFilename());
      $image->__destruct();
    }
  }

  private function _resizeThumb($targetWidth, $targetHeight, $sourceFilename)
  {
    $target = new Box($targetWidth, $targetHeight);
    $imagine = new Imagine();
    $originalImage = $imagine->open($sourceFilename);
    $orgSize = $originalImage->getSize();
    if ($orgSize->getWidth() > $orgSize->getHeight()) {
      // Landscaped.. We need to crop image by horizontally
      $w = $orgSize->getWidth() * ($target->getHeight() / $orgSize->getHeight());
      $h = $target->getHeight();
      $cropBy = new Point((max($w - $target->getWidth(), 0)) / 2, 0);
    } else {
      // Portrait..
      $w = $target->getWidth(); // Use target box's width and crop vertically
      $h = $orgSize->getHeight() * ($target->getWidth() / $orgSize->getWidth());
      $cropBy = new Point(0, (max($h - $target->getHeight(), 0)) / 2);
    }

    $tempBox = new Box($w, $h);
    $img = $originalImage->thumbnail($tempBox, ImageInterface::THUMBNAIL_OUTBOUND);
    // Here is the magic..

    return $img->crop($cropBy, $target); // Return "ready to save" final image instance

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
    if ($file !== null) {
      $fileName = $file->getClientOriginalName();
      $name = pathinfo($fileName, PATHINFO_FILENAME);
      $ext = pathinfo($fileName, PATHINFO_EXTENSION);

      $thumbDir = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/thumbs/' . date('Y') . '/' . date('m');


      if (!$fs->exists($thumbDir)) {
        $fs->mkdir($thumbDir);
      }
      $i = 1;
      while ($fs->exists($thumbDir . '/' . $fileName)) {
        $fileName = $name . '-' . $i . '.' . $ext;
        $i++;
      }
      try {
        $file = $file->move($thumbDir, $fileName);
        $em = $this->getDoctrine()->getManager();
        $medium = new Media();
        $fileName = date('Y') . '/' . date('m') . '/' . $fileName;
        $medium->setPath($fileName);
        $metas = array();
        foreach ($languages as $language) {
          $metas['alt_' . $language->getId()] = str_replace('-', ' ', $name);
          $metas['title_' . $language->getId()] = str_replace('-', ' ', $name);
        }
        $medium->setMetas($metas);
        $em->persist($medium);
        $em->flush();

        $this->_resizeMedia($file);
        return $this->render('MediaBundle:Media:thumb.html.twig', array('media' => $medium));
      } catch (FileException $e) {
        $response = new JsonResponse();
        $response->setData(array('status' => false, "message" => $e->getMessage()));
        return $response;
      }

    }
    $response = new JsonResponse();
    $response->setData(array('status' => false));
    return $response;

  }
}

<?php

namespace CMS\Bundle\MediaBundle\Controller;

use CMS\Bundle\MediaBundle\Form\MediaInfoType;
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


class MediaController extends Controller
{
    /**
     * @Route("/", name="admin_media_index")
     */
    public function indexAction()
    {
        $nb_media = 6;
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('MediaBundle:Media')->findBy(array(), array('dateAdded' => 'desc'), $nb_media, 0);
        $medium = new Media();
        $page = 1;
        $form = $this->createForm('CMS\Bundle\MediaBundle\Form\MediaType', $medium);
        $nb_media_total = $em->getRepository('MediaBundle:Media')->getNbMedia()[0][1];
        $nb_media_left = $nb_media_total - ($page + 1) * $nb_media;
        $more_pages = ($nb_media_left > 0);
        
        return $this->render(
            'MediaBundle:Media:index.html.twig',
            array(
                'media' => $media,
                'form' => $form->createView(),
                'url' => 'admin_media_delete',
                'nb_media_total' => $nb_media_total,
            )
        );
    }
    
    /**
     * @param $page
     * @param int $nb_media
     * @return Template
     *
     * @Route("/media/{page}/{nb_media}", name="admin_media_page", defaults={"nb_media":6, "page":2})
     */
    public function mediaAction($page = 2, $nb_media = 6)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository('MediaBundle:Media')->findBy(
            array(),
            array('dateAdded' => 'desc'),
            $nb_media,
            ($page * $nb_media)
        );
        
        return $this->render(
            'MediaBundle:Media:media.html.twig',
            array(
                'media' => $media,
                'url' => 'admin_media_delete',
            )
        );
    }
    
    /**
     * @Route("/resize", name="admin_media_resize")
     */
    public function resizeAllMediaAction()
    {
        $response = new StreamedResponse();
        $response->setCallback(
            function () {
                $option_manager = $this->get('cms.media.media_option_manager');
                $options = $option_manager->getAllOptions();
                $dir = "/web/uploads/thumbs/*";
                $finder = new Finder();
                $finder->files()->in($this->container->getParameter('kernel.root_dir').'/..'.$dir);
                $i = 0;
                $nb_files = $finder->count() * count($options);
                ob_get_flush();
                foreach ($finder as $file) {
                    foreach ($options as $option) {
                        if (!is_dir(
                            $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$option->getOptionName(
                            )
                        )
                        ) {
                            mkdir(
                                $this->container->getParameter(
                                    'kernel.root_dir'
                                ).'/../web/uploads/'.$option->getOptionName()
                            );
                        }
                        $sizes = json_decode($option->getOptionValue());
                        $image = $this->_resizeThumb(
                            $sizes->width,
                            $sizes->height,
                            $this->container->getParameter(
                                'kernel.root_dir'
                            ).'/../web/uploads/thumbs/2016/'.$file->getRelativePathname()
                        );
                        $image->save(
                            $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$option->getOptionName(
                            ).'/'.$file->getFilename()
                        );
                        $image->__destruct();
                        $i++;
                        echo json_encode(
                            array("percent" => (int)(($i / $nb_files) * 100), "filename" => $file->getFilename())
                        );
                        flush();
                        sleep(1);
                    }
                }
            }
        );
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
        foreach ($options as $option) {
            $fs->remove(
                $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$option->getOptionName(
                ).'/'.$filename
            );
        }
        
        $fs->remove($this->container->getParameter('kernel.root_dir').'/../web'.$origin_path);
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
            if (!is_dir(
                $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$option->getOptionName()
            )
            ) {
                mkdir($this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$option->getOptionName());
            }
            $sizes = json_decode($option->getOptionValue());
            $image = $this->_resizeThumb($sizes->width, $sizes->height, $file->getPathname());
            $image->save(
                $this->container->getParameter('kernel.root_dir').'/../web/uploads/'.$option->getOptionName(
                ).'/'.$file->getFilename()
            );
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
     * @Route("/upload-media/{popup}", name="admin_media_upload", defaults={"popup": false})
     *
     */
    public function uploadMediaAction(Request $request, $popup = false)
    {
        $languages = $this->getDoctrine()->getRepository('CoreBundle:Language')->findAll();
        $file = $request->files->get('file');
        $fs = new Filesystem();
        if ($file !== null) {
            $fileName = $file->getClientOriginalName();
            $name = pathinfo($fileName, PATHINFO_FILENAME);
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            
            $thumbDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/thumbs/'.date('Y').'/'.date(
                    'm'
                );
            
            
            if (!$fs->exists($thumbDir)) {
                $fs->mkdir($thumbDir);
            }
            $i = 1;
            while ($fs->exists($thumbDir.'/'.$fileName)) {
                $fileName = $name.'-'.$i.'.'.$ext;
                $i++;
            }
            try {
                $file = $file->move($thumbDir, $fileName);
                $em = $this->getDoctrine()->getManager();
                $medium = new Media();
                $fileName = date('Y').'/'.date('m').'/'.$fileName;
                $medium->setPath($fileName);
                $metas = array();
                foreach ($languages as $language) {
                    $metas['alt_'.$language->getId()] = str_replace('-', ' ', $name);
                    $metas['title_'.$language->getId()] = str_replace('-', ' ', $name);
                }
                $medium->setMetas($metas);
                $em->persist($medium);
                $em->flush();
                
                $this->_resizeMedia($file);
                if (!$popup) {
                    return $this->render('MediaBundle:Media:thumb.html.twig', array('media' => $medium));
                } else {
                    $response = new JsonResponse();
                    $response->setData(
                        array('status' => true, "media" => $medium, 'path' => $medium->getWebPathList())
                    );
                    
                    return $response;
                }
                
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
    
    /**
     * @param $id
     * @param Request $request
     * @return Response
     *
     * @Route("/{id}/edit", name="admin_media_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $medium = $em->getRepository('MediaBundle:Media')->find($id);
        $form = $this->createForm(new MediaInfoType(), $medium, array("id" => $id));
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($medium);
                $em->flush();
            }
        }
        
        return $this->render(
            "MediaBundle:Media:edit.html.twig",
            array("form" => $form->createView(), "medium" => $medium)
        );
    }
    
    /**
     * Mise à jour des metas en ajax
     * @return JsonResponse
     * @Route("/update-metas", name="admin_update_meta")
     */
    public function updateMetasAction(Request $request)
    {
        $media_info = $request->request->get('media_info');
        $media = $this->getDoctrine()->getRepository('MediaBundle:Media')->find($media_info['id']);
        $media->setMetas($media_info['metas']);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($media);
        $em->flush();
        
        return new JsonResponse(array('status' => true));
    }
    
    /**
     * Récupère tous les médias et les présente dans une modal
     * @Route("/media-popup", name="admin_media_popup")
     * @Method("GET")
     * @Template()
     */
    public function mediaPopupAction()
    {
        $media = $this->getDoctrine()->getRepository('MediaBundle:Media')->findBy(array(), array('id' => 'DESC'));
        $defaultLanguage = $this->getDoctrine()->getRepository('CoreBundle:Language')->find(1);
        
        return $this->render(
            'MediaBundle:Media:mediaPopup.html.twig',
            array(
                'media' => $media,
                'defaultLanguage' => $defaultLanguage,
            )
        );
    }
    
    /**
     * Récupère tous les médias et les présente dans une modal
     * Au clic sur une image elle s'insère dans l'éditeur
     * @return array
     * @Route("/media-popup-summernote", name="admin_media_popup_summernote")
     * @Method("GET")
     */
    public function mediaPopupSummernoteAction()
    {
        $media = $this->getDoctrine()->getRepository('MediaBundle:Media')->findBy(array(), array('id' => 'DESC'));
        $defaultLanguage = $this->getDoctrine()->getRepository('CoreBundle:Language')->find(1);
        
        return $this->render(
            'MediaBundle:Media:mediaPopupSummernote.html.twig',
            array(
                'media' => $media,
                'defaultLanguage' => $defaultLanguage,
            )
        );
    }
    
    /**
     * Récupère les détails sur le média
     * @Route("/details-image/{form_edit}", name="admin_media_details", defaults={"form_edit": "true"})
     * @Method("POST")
     */
    public function getDetailsMediaAction(Request $request, $form_edit = "true")
    {
        $media = $this->getDoctrine()->getRepository('MediaBundle:Media')->find($request->request->get('id'));
        if ($media === null) {
            return new JsonResponse(array('status' => false, 'msg' => 'cms.media.not_found'));
        }
        $response['medium'] = $media;
        
        if ($form_edit == "true") {
            $form = $this->createForm(
                'CMS\Bundle\MediaBundle\Form\MediaInfoType',
                $media,
                array(
                    'action' => $this->generateUrl('admin_media_edit', array('id' => $media->getId())),
                )
            );
            $response['form'] = $form->createView();
            $response['url_delete'] = $this->generateUrl('admin_media_delete', array('medium' => $media->getId()));
        } else {
            $form = $this->createForm(
                'CMS\Bundle\MediaBundle\Form\MediaInfoType',
                $media,
                array(
                    'action' => $this->generateUrl('admin_update_meta'),
                    'id' => $media->getId(),
                )
            );
            $response['form'] = $form->createView();
        }
        
        return $this->render("MediaBundle:Media:details.html.twig", $response);
    }
    
    /**
     * @Route("/gallery-popup", name="admin_media_gallery")
     */
    public function galleryPopupAction()
    {
        $media = $this->getDoctrine()->getRepository('MediaBundle:Media')->findBy(array(), array('id' => 'DESC'));
        $defaultLanguage = $this->getDoctrine()->getRepository('CoreBundle:Language')->find(1);
        return $this->render("MediaBundle:Media:gallery.html.twig", array('media' => $media, 'defaultLanguage' => $defaultLanguage));
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/gallery-images", name="admin_media_gallery_images")
     */
    public function getGalleryImagesAction(Request $request)
    {
        $images = $request->request->get('images');
        $media = $this->getDoctrine()->getRepository('MediaBundle:Media')->findMediaIn($images);
        $defaultLanguage = $this->getDoctrine()->getRepository('CoreBundle:Language')->find(1);
        return $this->render('MediaBundle:Media:media-gallery.html.twig', array('media' => $media, 'defaultLanguage' => $defaultLanguage));
    }
}

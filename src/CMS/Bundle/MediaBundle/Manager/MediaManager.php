<?php
namespace CMS\Bundle\MediaBundle\Manager;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;

use CMS\Bundle\MediaBundle\Entity\Repository\MediaRepository;



class MediaManager
{

  private $kernel;
  private $repo;

  public function __construct(KernelInterface $kernel, MediaRepository $repo)
  {
    $this->kernel = $kernel;
    $this->repo = $repo;
  }


  public function getDetailsImage($id)
  {
    $media = $this->repo->find($id);
    if ($media === null) {
      return new JsonResponse(array('status' => false, 'msg' => 'locabri.media.not_found'));
    }
    $dir = pathinfo('/web' . $media->getWebPath(), PATHINFO_DIRNAME);
    $finder = new Finder();
    $finder->files()->in($this->kernel->getRootDir() . '/..' . $dir)->name(basename($media->getPath()));
    $response = array();
    foreach ($finder as $file) {
      // dump($file); die;
      $response["filename"] = $file->getFilename();
      $response["uploaded"] = date('d/m/Y', $file->getMTime());
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $response["filetype"] = finfo_file($finfo, $file->getRealPath());
      finfo_close($finfo);
      $response["size"] = number_format($file->getSize() / 1024, 1) . 'kB';
      $response["dimensions"] = getimagesize($file->getRealPath());

    }
  }

  public function resizeMedia($path) {
    $image = $this->_resizeThumb(240,240, $path);
    $image->save($this->kernel->getRootDir().'/../web/uploads/thumb_list/'.basename($path));
    return true;
  }

  public function resizeAll()
  {
    $response = new StreamedResponse();
    $response->setCallback(function () {
      $dir = "/web/uploads/thumbs/*";
      $finder = new Finder();
      $finder->files()->in($this->kernel->getRootDir() . '/..' . $dir);
      $i = 0;
      $nb_files = $finder->count();
      ob_get_flush();
      foreach ($finder as $file) {
        $image = $this->_resizeThumb(240,240, $this->kernel->getRootDir().'/../web/uploads/thumbs/2016/'.$file->getRelativePathname());
        $image->save($this->kernel->getRootDir().'/../web/uploads/thumb_list/'.$file->getFilename());
        $image->__destruct();
        $i++;
        echo (int)(($i/$nb_files)*100);
        flush();
        sleep(1);

      }
    });
    $response->send();
  }

  private function _resizeThumb($targetWidth, $targetHeight, $sourceFilename)
  {
    $target = new Box($targetWidth, $targetHeight );
    $imagine = new Imagine();
    $originalImage = $imagine->open( $sourceFilename );
    $orgSize = $originalImage->getSize();
    if ($orgSize->getWidth() > $orgSize->getHeight()) {
      // Landscaped.. We need to crop image by horizontally
      $w = $orgSize->getWidth() * ( $target->getHeight() / $orgSize->getHeight() );
      $h =  $target->getHeight();
      $cropBy = new Point( ( max ( $w - $target->getWidth(), 0 ) ) / 2, 0);
    } else {
      // Portrait..
      $w = $target->getWidth(); // Use target box's width and crop vertically
      $h = $orgSize->getHeight() * ( $target->getWidth() / $orgSize->getWidth() );
      $cropBy = new Point( 0, ( max( $h - $target->getHeight() , 0 ) ) / 2);
    }

    $tempBox = new Box($w, $h);
    $img = $originalImage->thumbnail($tempBox, ImageInterface::THUMBNAIL_OUTBOUND);
    // Here is the magic..
    return $img->crop($cropBy, $target); // Return "ready to save" final image instance
  }

  function resizeToFit($targetWidth, $targetHeight, $sourceFilename, $targetPath)
  {
    $size = new Box($targetWidth, $targetHeight);
    $imagine = new Imagine();
    $imagine->open($sourceFilename)->resize($size)->save($targetPath);
  }
}
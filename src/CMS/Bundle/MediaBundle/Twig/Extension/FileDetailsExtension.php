<?php

namespace CMS\Bundle\MediaBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Finder\Finder;

use CMS\Bundle\MediaBundle\Entity\Repository\MediaRepository;

class FileDetailsExtension extends \Twig_Extension
{

    private $kernel;
    private $repo;

    public function __construct(KernelInterface $kernel, MediaRepository $repo)
    {
        $this->kernel = $kernel;
        $this->repo = $repo;
    }

    public function getFunctions()
    {
        return array(
                'file_details' => new \Twig_Function_Method($this, 'fileDetailsFilter'),
        );
    }

    public function fileDetailsFilter($id, $template="default")
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
        $file_size = $file->getSize() / 1024;
        if ($file_size < 1000) {
          $response["size"] = number_format($file_size, 1) . 'kB';
        } else {
          $response["size"] = number_format($file_size / 1024, 1) . 'MB';
        }
        $response["dimensions"] = getimagesize($file->getRealPath());
      }
      if ($template == "default") {
        return '<span class="title">' . $media->getMetas()['title_1'] . '</span><br />' . $response['dimensions'][0] . 'x' . $response['dimensions'][1] . ' px<br />' . $response['size'];
      } else {
        $str = '<ul class="list-unstyled">';
        $str .= '<li><strong>Filename: </strong>'.$response["filename"].'</li>';
        $str .= '<li><strong>Uploaded: </strong>'.$response["uploaded"].'</li>';
        $str .= '<li><strong>Filetype: </strong>'.$response["filetype"].'</li>';
        $str .= '<li><strong>Size: </strong>'.$response["size"].'</li>';
        $str .= '<li><strong>Width: </strong>'.$response['dimensions'][0].'px</li>';
        $str .= '<li><strong>Height: </strong>'.$response['dimensions'][1].'px</li>';
        $str .= '</ul>';
        return $str;
      }
    }

    public function getName()
    {
        return 'file_details';
    }
}
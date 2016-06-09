<?php
namespace CMS\Bundle\MediaBundle\Manager;

class MediaManager
{
	

	public function getDetailsImage($id)
	{
		$media = $this->repo->find($id);
        if ($media === null) {
            return new JsonResponse(array('status' => false, 'msg' => 'locabri.media.not_found'));
        }
        $dir = pathinfo('/web'.$media->getWebPath(), PATHINFO_DIRNAME);
        $finder = new Finder();
        $finder->files()->in($this->kernel->getRootDir() . '/..'.$dir)->name(basename($media->getPath()));
        $response = array();
        foreach($finder as $file) {
            // dump($file); die;
            $response["filename"] = $file->getFilename();
            $response["uploaded"] = date('d/m/Y', $file->getMTime());
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $response["filetype"] = finfo_file($finfo, $file->getRealPath());
            finfo_close($finfo);
            $response["size"] = number_format($file->getSize()/1024, 1).'kB';
            $response["dimensions"] = getimagesize($file->getRealPath());
        }
	}
}
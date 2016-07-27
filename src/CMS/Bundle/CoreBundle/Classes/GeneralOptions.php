<?php
namespace CMS\Bundle\CoreBundle\Classes;

use CMS\Bundle\CoreBundle\Entity\Option;
use CMS\Bundle\CoreBundle\Entity\Repository\OptionRepository;
use CMS\Bundle\MediaBundle\Entity\Repository\MediaRepository;

class GeneralOptions
{
	/**
	 * Options générales
	 * @var ArrayCollection
	 */
	private $options;
    
    private $mediaRepo;

	public function __construct(OptionRepository $opRepo, MediaRepository $mediaRepo)
	{
		$this->opRepo = $opRepo;
        $this->mediaRepo = $mediaRepo;
		$tmp_options = $opRepo->getGeneralOptions();
        foreach($tmp_options as $option) {
            if($option->getType() == 'image') {
                $option->setOptionValue($this->mediaRepo->find($option->getOptionValue()));
            }
            $this->options[] = $option;
        }
        
	}

	public function __get($option_name)
	{
		if(is_array($this->options)) {
			foreach ($this->options as $option) {
				if ($option->getOptionName() == $option_name) {
					return $option;
				}
			}
		}
	}

	public function __set($option_name, $option_value)
	{
		if(is_array($this->options)) {
			foreach ($this->options as $option) {
				if ($option->getOptionName() == $option_name) {
					$option->setOptionValue($option_value);
					return $option;
				}
			}
		}
	}

	public function getOptions()
	{
		return $this->options;
	}
}
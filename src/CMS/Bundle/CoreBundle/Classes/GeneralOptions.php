<?php
namespace CMS\Bundle\CoreBundle\Classes;

use CMS\Bundle\CoreBundle\Entity\Option;
use CMS\Bundle\CoreBundle\Entity\Repository\OptionRepository;

class GeneralOptions
{
	/**
	 * Options générales
	 * @var ArrayCollection
	 */
	private $options;

	public function __construct(OptionRepository $opRepo)
	{
		$this->opRepo = $opRepo;
		$this->options = $opRepo->getGeneralOptions();
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
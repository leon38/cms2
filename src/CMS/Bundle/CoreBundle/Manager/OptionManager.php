<?php
namespace CMS\Bundle\CoreBundle\Manager;

use Doctrine\ORM\EntityManager;

use CMS\Bundle\CoreBundle\Entity\Option;
use CMS\Bundle\CoreBundle\Entity\Repository\OptionRepository;

class OptionManager
{

	protected $em;
	protected $repo;

	public function __construct(EntityManager $em, OptionRepository $repo)
	{
		$this->em = $em;
		$this->repo = $repo;
	}

	public function add($name, $value, $type = 'text', $general = false)
	{
		$option = $this->_exists($name);
		if ($option === null) {
			$option = new Option();
			$option->setOptionName($name);
		}
		$option->setOptionValue($value);
		$option->setType($type);
		$option->setGeneral($general);
		$this->em->persist($option);
		$this->em->flush();
	}

	public function get($name, $default_value)
	{
		$option = $this->_exists($name);
		return ($option != null) ? $option : $default_value;
	}

	private function _exists($name)
	{
		$option = $this->repo->findOneBy(array('option_name' => $name));
		return ($option !== null) ? $option : null;
	}

}
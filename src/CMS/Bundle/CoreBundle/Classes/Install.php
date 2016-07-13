<?php
namespace CMS\Bundle\CoreBundle\Classes;

use Symfony\Component\Finder\Finder;

use CMS\Bundle\CoreBundle\Entity\User;
use CMS\Bundle\CoreBundle\Entity\Option;

/**
 * Classe d'abstraction pour l'installation du CMS
 * Cette classe instancie un nouvel utilisateur,
 * Crée le fichier robots correct
 * et ajoute le titre du site en nouvelle option
 *
 * @package scms
 * @subpackage Core
 * @since 0.1
 */
class Install
{

	/**
	 * Titre du site
	 * @var String
	 */
	private $option_value;

	/**
	 * Libelle "titre du site"
	 * @var String
	 */
	private $option_name;

	/**
	 * Nom d'utilisateur
	 * @var String
	 */
	private $user_login;

	/**
	 * Mot de passe de l'utilisateur
	 * @var String
	 */
	private $user_pass;

	/**
	 * Email de l'utilisateur
	 * @var String
	 */
	private $user_email;

	/**
	 * Le site doit être protégé des robots
	 * @var integer
	 */
	private $robots = true;


	public function getOptionValue()
	{
		return $this->option_value;
	}

	public function getOptionName()
	{
		return $this->option_name;
	}

	public function getUserLogin()
	{
		return $this->user_login;
	}

	public function getUserPass()
	{
		return $this->user_pass;
	}

	public function getUserEmail()
	{
		return $this->user_email;
	}

	public function getRobots()
	{
		return $this->robots;
	}

	public function setOptionValue($option_value)
	{
		$this->option_value = $option_value;
		return $this;
	}

	public function setOptionName($option_name)
	{
		$this->option_name = $option_name;
		return $this;
	}

	public function setUserLogin($user_login)
	{
		$this->user_login = $user_login;
		return $this;
	}

	public function setUserPass($user_pass)
	{
		$this->user_pass = $user_pass;
		return $this;
	}

	public function setUserEmail($user_email)
	{
		$this->user_email = $user_email;
		return $this;
	}

	/**
	 * Ecris dans le fichier robots.txt en fonction de ce qui a été défini dans le formulaire.
	 * @param bool $robots Si oui le site ne sera pas référencé
	 */
	public function setRobots($robots)
	{
		$this->robots = $robots;

		$finder = new Finder();

		$finder->files()->name('robots.txt')->in(__DIR__.'/../../../../../web');
		foreach($finder as $file) {
			$handle = fopen($file->getRealPath(), "w");
			if($this->robots == 0) {
				fwrite($handle, "User-agent: *\nDisallow: /");
			} else {
				fwrite($handle, "User-agent: *");
			}
			fclose($handle);
		}
		return $this;
	}

	/**
	 * Affecte les infos à l'utilisateur
	 */
	public function setUser()
	{
		$user = new User();
		$user->setUserLogin($this->user_login);
		$user->setUserPass($this->user_pass);
		$user->setUserEmail($this->user_email);
    $user->setUserStatus(true);
		return $user;
	}

	public function getOption()
	{
		$option = new Option();
		$option->set($this->option_name, $this->option_value);
		return $option;
	}
}
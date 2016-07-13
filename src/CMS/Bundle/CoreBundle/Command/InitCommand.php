<?php
namespace CMS\Bundle\CoreBundle\Command;

use CMS\Bundle\CoreBundle\Entity\UserMeta;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use CMS\Bundle\CoreBundle\Entity\Option;
use CMS\Bundle\CoreBundle\Entity\Role;
use CMS\Bundle\CoreBundle\Entity\Language;
use CMS\Bundle\ContentBundle\Entity\ContentTaxonomy;
use CMS\Bundle\ContentBundle\Entity\Category;

class InitCommand extends ContainerAwareCommand
{

	protected function configure()
	{
		$this
			->setName('scms:init')
			->setDescription("Initialize options for the cms")
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('Initialization of the options...');
		$output->writeln(' ');
		$em = $this->getContainer()->get('doctrine')->getManager();

		$output->write('Slogan => ');
		$slogan = new Option();
		$option_name = 'slogan';
		$option_value = 'Un site fait avec le CMS de 3c-evolution';
		$slogan->set($option_name, $option_value);
		$em->persist($slogan);
		$output->writeln($option_value);

		$output->write('Adresse de messagerie => ');
    $email = new Option();
    $option_name = 'email_admin';
    $option_value = '';
    $email->set($option_name, $option_value);
    $em->persist($email);
    $output->writeln($option_value);

    $output->write('Fuseau horaire => ');
		$timezone = new Option();
		$option_name = 'timezone';
		$option_value = 'UTC+0';
		$timezone->set($option_name, $option_value);
		$em->persist($timezone);
		$output->writeln($option_value);

		$output->write('Date Format => ');
		$date_format = new Option();
		$option_name = 'date_format';
		$option_value = 'd/m/Y';
		$date_format->set($option_name, $option_value);
		$em->persist($date_format);
		$output->writeln($option_value);

		$output->write('Role => ');
		$role_super_admin = new Role();
		$role_super_admin->setRoleName('ROLE_SUPER_ADMIN');
		$role_super_admin->setRoleNicename('Super Administrator');
		$em->persist($role_super_admin);
		$output->writeln('Super Administrator');

		$output->write('Role => ');
		$role_admin = new Role();
		$role_admin->setRoleName('ROLE_ADMIN');
		$role_admin->setRoleNicename('Administrator');
		$em->persist($role_admin);
		$output->writeln('Administrator');

		$output->write('Langues => ');
		$language = new Language();
		$language->setName('Français');
		$language->setCodeLocal('fr_fr');
		$language->setCodeLang('fr_fr');
		$language->setDefault(true);
		$em->persist($language);
		$output->write('Français');


		$output->write('Catégories => ');
		$category = new Category();
		$category->setTitle('Root');
		$category->setLft(1);
		$category->setRgt(4);
		$category->setOrdre(1);
		$category->setLevel(0);
		$category->setUrl('root.html');
		$category->setPublished(0);

		$em->persist($category);

		$uncategorized = new Category();
		$uncategorized->setTitle('Non catégorisé');
		$uncategorized->setLft(2);
		$uncategorized->setRgt(3);
		$uncategorized->setOrdre(2);
		$uncategorized->setLevel(1);
		$uncategorized->setUrl('non-categorise.html');
		$uncategorized->setPublished(1);
		$uncategorized->setParent($category);
		$uncategorized->setLanguage($language);
		$category->addChild($uncategorized);
		$em->persist($category);
		$em->persist($uncategorized);
		$output->writeln('Catégories');

		$output->write('Taxonomy => ');
		$contentTax = new ContentTaxonomy();
		$contentTax->setTitle('Post');
		$contentTax->setAlias('post');
		$em->persist($contentTax);
		$output->writeln('Post');

		$em->flush();



		$output->writeln(' ');
		$output->writeln('Initialization finished');
	}
}
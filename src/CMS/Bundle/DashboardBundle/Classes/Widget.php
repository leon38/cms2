<?php
/*******************************
 * Name: Widget.php
 * Date: 14/01/2015 10:57
 * Author: Damien CORONA
 * URL: https://github.com/leon38/SCMS.git
 *
 * Copyright (c) 2014 Damien Corona
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *******************************/
namespace CMS\Bundle\DashboardBundle\Classes;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Router;

use CMS\Bundle\DashboardBundle\Entity\WidgetEntity;

/**
 * Définit le comportement général d'une classe Widget
 */
class Widget implements IWidget
{

	protected $factory;
	protected $em;
	protected $widget;
	protected $args;
	protected $templating;
	protected $router;

	/**
	 * Construit un nouveau widget
	 * @param FormBuilderInterface $builder [description]
	 */
	public function __construct(FormFactoryInterface $factory, EntityManager $em, TwigEngine $templating, Router $router, array $args = array())
	{
		$this->factory = $factory;
		$this->em = $em;
		$this->args = $args;
		$this->templating = $templating;
		$this->router = $router;
	}

	/**
	 * Affiche le widget sur le front ou le back
	 * @return String          Code HTML du widget
	 */
	public function widget()
	{
		die("The function widget must be overriden in a sub-class");
	}

	/**
	 * Formulaire qui permet de configurer le widget
	 * @return String          Affichage du formulaire du widget en back-end
	 */
	public function form()
	{
		return $this->factory->create()
		                     ->add('title', 'text', array('label' => 'Titre'))
		                     ->add('description', 'textarea')
		                     ->add('position', 'choice', array('label' => false, 'empty_value' => 'Choisissez une position', 'choices' => array('header' => 'header', 'sidebar' => 'sidebar', 'middle' => 'middle', 'footer' => 'footer'), 'expanded' => false, 'multiple' => false));
	}

	/**
	 * Met à jour les valeurs de configuation du widget
	 * @param  array $new_instance Nouvelles valeurs postées dans le formulaire
	 * @param  array $old_instance Anciennes valeurs du widget
	 * @return Widget              Renvoie l'objet Widget
	 */
	public function update(array $new_instance, array $old_instance)
	{
		return $new_instance;
	}

	public function setWidget(WidgetEntity $widget)
	{
		$this->widget = $widget;
	}

	public function getArgs()
	{
		return $this->args;
	}
}
<?php
namespace CMS\Bundle\DashboardBundle\Classes;

interface IWidget
{

	/**
	 * Affiche le widget sur le front ou le back
	 * @param  array $args     Tableau d'arguments
	 * @param  array $instance Tableau des valeurs définies dans la configuration du widget
	 * @return String          Code HTML du widget
	 */
	public function widget();

	/**
	 * Formulaire qui permet de configurer le widget
	 * @param  array $instance Valeurs définies précédemment
	 * @return String          Affichage du formulaire du widget en back-end
	 */
	public function form();

	/**
	 * Met à jour les valeurs de configuation du widget
	 * @param  array $new_instance Nouvelles valeurs postées dans le formulaire
	 * @param  array $old_instance Anciennes valeurs du widget
	 * @return Widget              Renvoie l'objet Widget
	 */
	public function update(array $new_instance, array $old_instance);



}
<?php
namespace CMS\Bundle\WidgetBundle\Classes;

interface IWidget {

	public function renderWidget();

	public function update();

	public function renderForm();

}
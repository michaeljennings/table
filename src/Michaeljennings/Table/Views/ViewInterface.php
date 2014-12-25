<?php namespace Michaeljennings\Table\Views;

interface ViewInterface {

	/**
	 * Make the view
	 * 
	 * @param  string $template 
	 * @param  mixed  $data
	 * @return string       
	 */
	public function make($template, $data = array());
}
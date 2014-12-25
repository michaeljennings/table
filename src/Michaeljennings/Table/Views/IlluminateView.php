<?php namespace Michaeljennings\Table\Views;

use View;

class IlluminateView implements ViewInterface {

	/**
	 * Make the view
	 * 
	 * @param  string $template 
	 * @param  mixed  $data
	 * @return string
	 */
	public function make($template, $data = array())
	{
		return View::make($template, $data);
	}
}
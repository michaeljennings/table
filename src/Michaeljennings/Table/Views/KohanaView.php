<?php namespace Michaeljennings\Table\Views;

use View;

class KohanaView implements ViewInterface {

	/**
	 * Make the view
	 * 
	 * @param  string $template 
	 * @param  mixed  $data
	 * @return string
	 */
	public function make($template, $data = array())
	{
		$view = View::factory($template);
		if (!empty($data)) {
			foreach ($data as $key => $val) {
				$view->$key = $val;
			}
		}

		return $view->render();
	}
}
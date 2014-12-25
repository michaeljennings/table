<?php namespace Michaeljennings\Table\Sessions;

use Session;

class KohanaSession implements SessionInterface {

	public function __construct()
	{
		$this->session = Session::instance();
	}

	/**
	 * Remove the table session.
	 *
	 * @return object
	 */
	public function forget($name)
	{
		return $this->session->delete($name);
	}

	/**
	 * Get the table session value.
	 *
	 * @return object
	 */
	public function get($name)
	{
		return $this->session->get($name);
	}

	/**
	 * Check if the table session value exists
	 * 
	 * @param  string  $name 
	 * @return object
	 */
	public function has($name)
	{
		return $this->session->get($name) ? true : false;
	}

	/**
	 * Put a value in the table session.
	 *
	 * @param  mixed   $value
	 * @return object
	 */
	public function put($name, $value)
	{
		return $this->session->set($name, $value);
	}
}
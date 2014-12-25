<?php namespace Michaeljennings\Table\Sessions;

use Session;

class IlluminateSession implements SessionInterface {

	/**
	 * Remove the table session.
	 *
	 * @return object
	 */
	public function forget($name)
	{
		return Session::forget($name);
	}

	/**
	 * Get the table session value.
	 *
	 * @return object
	 */
	public function get($name)
	{
		return Session::get($name);
	}

	/**
	 * Check if the table session value exists
	 * 
	 * @param  string  $name 
	 * @return object
	 */
	public function has($name)
	{
		return Session::has($name);
	}

	/**
	 * Put a value in the table session.
	 *
	 * @param  mixed   $value
	 * @return object
	 */
	public function put($name, $value)
	{
		return Session::put($name, $value);
	}
}
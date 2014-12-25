<?php namespace Michaeljennings\Table\Sessions;

interface SessionInterface {

	/**
	 * Remove the table session.
	 *
	 * @return object
	 */
	public function forget($name);

	/**
	 * Get the table session value.
	 *
	 * @return object
	 */
	public function get($name);

	/**
	 * Check if the table session value exists
	 * 
	 * @param  string  $name 
	 * @return object
	 */
	public function has($name);

	/**
	 * Put a value in the table session.
	 *
	 * @param  mixed   $value
	 * @return object
	 */
	public function put($name, $value);
}
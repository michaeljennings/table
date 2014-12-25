<?php namespace Michaeljennings\Table\Config;

interface ConfigInterface {

	/**
	 * Get the table configuration file and return as a flattened array
	 * 
	 * @param  string $file The config file
	 * @return array       
	 */
	public static function get($file);
}
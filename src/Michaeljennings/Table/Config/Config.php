<?php namespace Michaeljennings\Table\Config;

use Exception;
use Illuminate\Filesystem\Filesystem;
use Michaeljennings\Table\Config\ConfigInterface;

class ConfigException extends Exception {}

class Config implements ConfigInterface {

	public function __construct()
	{
		$this->path = __DIR__.'/../../../config/';
		$this->files = new Filesystem;
	}

	/**
	 * Get the table configuration file and return as a flattened array
	 * 
	 * @param  string $file The config file
	 * @return array       
	 */
	public static function get($file)
	{
		$items = array();
		$config = new self();

		if (!$config->files->get($config->path.$file.'.php'))
			throw new ConfigException('Failed to locate the configuration file');

		return array_dot($config->files->getRequire($config->path.$file.'.php'));
	}
}
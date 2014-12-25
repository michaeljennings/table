<?php namespace Michaeljennings\Table;

use Closure;
use Michaeljennings\Table\Config\Config as TableConfig;

class TableCollectionException extends \Exception {}

class TableCollection {

	/**
	 * A collection of table closures
	 * 
	 * @var array
	 */
	protected $collection = array();

	/**
	 * An optional search class
	 * 
	 * @var mixed
	 */
	protected $search = false;

	/**
	 * Set the table config and optionally a search class
	 * 
	 * @param mixed $search
	 */
	public function __construct($search = false)
	{
		$this->config = TableConfig::get('config');

		if ($search) $this->search = $search;
	}

	/**
	 * Add a table into the table colleciton
	 * 
	 * @param string  $name     
	 * @param Closure $callback
	 */
	public function put($name, Closure $callback)
	{
		$this->collection[$name] = $callback;
	}

	/**
	 * Retrieve a table from the table collection
	 * 
	 * @param  string $name 
	 * @return Michaeljennings\Table\Table
	 */
	public function get($name)
	{
		$this->loadTables();

		if (!array_key_exists($name, $this->collection))
			throw new TableCollectionException('That table does not exist in the collection');

		return new Table($name, $this->collection[$name], $this->search);
	}

	/**
	 * Create a table without using the collection
	 *
	 * @param  string  $name 
	 * @param  Closure $callback 
	 * @return Michaeljennings\Table\Table
	 */
	public function make($name, Closure $callback)
	{
		return new Table($name, $callback, $this->search);
	}

	/**
	 * Load all of the tables from the table file into the collection
	 */
	public function loadTables()
	{
		$tableFile = $this->config['tableFile'];

		if (file_exists($tableFile))
			require_once $tableFile;
	}
}
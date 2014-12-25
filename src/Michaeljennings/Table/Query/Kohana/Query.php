<?php namespace Michaeljennings\Table\Query\Kohana;

use Illuminate\Support\Collection as Collection;
use Michaeljennings\Table\Query\QueryInterface as QueryInterface;

class Query implements QueryInterface {

	/**
	 * The model the query is being run on
	 * 
	 * @var object
	 */
	protected $model;

	/**
	 * The columns to select
	 * 
	 * @var array
	 */
	protected $select = array('*');

	public function __construct($model)
	{
		$this->model = \ORM::factory($model);
	}

	/**
	 * Run a where clause on the model
	 * 
	 * @param  string $column   
	 * @param  string $operator 
	 * @param  string $value    
	 * @return Mixed           
	 */
	public function where($column, $operator, $value)
	{
		$this->model = $this->model->where($column, $operator, $value);
		return $this;
	}

	/**
	 * Tun a where in clause on the model.
	 *
	 * @param  string  $column
	 * @param  mixed   $values
	 * @param  string  $boolean
	 * @param  bool    $not
	 * @return Mixed
	 */
	public function whereIn($column, $values, $boolean = 'and', $not = false)
	{
		$type = $not ? 'Not In' : 'In';

		$this->model = $this->model->where($column, $type, $values);
		return $this;
	}

	/**
	 * Set the columns to be selected
	 * 
	 * @param  mixed  $columns 
	 * @return Mixed
	 */
	public function select(array $columns)
	{
		$this->model = $this->model->select($columns);
		return $this;
	}

	/**
	 * Set the amount of results to select
	 * 
	 * @param  int $limit 
	 * @return Mixed        
	 */
	public function limit($limit)
	{
		$this->model = $this->model->limit($limit);
		return $this;
	}

	/**
	 * Set a join
	 * 
	 * @param  string $table       
	 * @param  string $foreign_key 
	 * @param  string $operator    
	 * @param  string $local_key   
	 * @return Mixed              
	 */
	public function join($table, $foreign_key, $operator, $local_key)
	{
		$this->model = $this->model
							->join($table, 'INNER')
							->on($foreign_key, $operator, $local_key);
		return $this;
	}

	/**
	 * Set a left join
	 * 
	 * @param  string $table       
	 * @param  string $foreign_key 
	 * @param  string $operator    
	 * @param  string $local_key   
	 * @return Mixed              
	 */
	public function leftJoin($table, $foreign_key, $operator, $local_key)
	{
		$this->model = $this->model
							->join($table, 'LEFT')
							->on($foreign_key, $operator, $local_key);
		return $this;
	}

	/**
	 * Run a raw select query on the model
	 * 
	 * @param  string $query 
	 * @return Mixed        	
	 */
	public function rawSelect($query)
	{
		$this->model = $this->model->select(DB::expr($query));
		return $this;
	}

	/**
	 * Get the results from the query
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function results()
	{
		$results = $this->model->find_all()->as_array();
		$results = new Collection($results);
		return $results;
	}
}
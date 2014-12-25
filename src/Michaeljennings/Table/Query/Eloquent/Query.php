<?php namespace Michaeljennings\Table\Query\Eloquent;

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
		$this->model =  new $model;
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
		$this->model = $this->model->whereIn($column, $values, $boolean, $not);
		return $this;
	}

	/**
	 * Set the columns to be selected
	 * 
	 * @param  array  $columns 
	 * @return Mixed
	 */
	public function select(array $columns)
	{
		$this->select = $columns;
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
		$this->model = $this->model->take($limit);
		return $this;
	}

	/**
	 * Order the results
	 * 
	 * @param  string  $column 
	 * @param  boolean $dir
	 * @return Mixed
	 */
	public function orderBy($column, $dir = false)
	{
		return $this->model = $this->model->orderBy($column, $dir);
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
							->join($table, $foreign_key, $operator, $local_key);
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
							->leftJoin($table, $foreign_key, $operator, $local_key);
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
		$this->model = $this->model->select($query);
		return $this;
	}

	/**
	 * Refresh the order by statement
	 * 
	 * @param  string $col 
	 * @param  string $dir 
	 * @return Michaelj\Table\Query\Eloquent\Query
	 */
	public function refreshOrderBy()
	{
		$query = $this->model->getQuery();
		unset($query->orders);

		$this->model->setQuery($query);
		return $this;
	}

	/**
	 * Get the results from the query
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function results()
	{
		return $this->model->get($this->select);
	}

	/**
	 * Paginate the results
	 * 
	 * @param  integer $amount 
	 * @return Illuminate\Support\Collection
	 */
	public function paginate($amount)
	{
		return $this->model->paginate($amount, $this->select);
	}

	/**
	 * Return the total results
	 * 
	 * @return integer
	 */
	public function count()
	{
		$countModel = clone $this->model;
		$countQuery = $countModel->getQuery();
		$countQuery->orders = null;
		$countModel->setQuery($countQuery);

		return $countModel->count();
	}
}
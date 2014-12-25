<?php namespace Michaeljennings\Table\Query;

interface QueryInterface {

	/**
	 * Run a where query on the model
	 * 
	 * @param  string $column   
	 * @param  string $operator 
	 * @param  string $value    
	 * @return mixed           
	 */
	public function where($column, $operator, $value);

	/**
	 * Add a "where in" clause to the query.
	 *
	 * @param  string  $column
	 * @param  mixed   $values
	 * @param  string  $boolean
	 * @param  bool    $not
	 * @return mixed
	 */
	public function whereIn($column, $values, $boolean = 'and', $not = false);

	/**
	 * Set the columns to be selected
	 * 
	 * @param  array  $columns 
	 * @return mixed
	 */
	public function select(array $columns);

	/**
	 * Set the amount of results to select
	 * 
	 * @param  int $limit 
	 * @return mixed        
	 */
	public function limit($limit);

	/**
	 * Order the results
	 * 
	 * @param  string  $column 
	 * @param  boolean $dir
	 * @return mixed
	 */
	public function orderBy($column, $dir = false);

	/**
	 * Set a join
	 * 
	 * @param  string $table       
	 * @param  string $foreign_key 
	 * @param  string $operator    
	 * @param  string $local_key   
	 * @return mixed              
	 */
	public function join($table, $foreign_key, $operator, $local_key);

	/**
	 * Set a left join
	 * 
	 * @param  string $table       
	 * @param  string $foreign_key 
	 * @param  string $operator    
	 * @param  string $local_key   
	 * @return mixed              
	 */
	public function leftJoin($table, $foreign_key, $operator, $local_key);

	/**
	 * Run a raw select query on the model
	 * 
	 * @param  string $query 
	 * @return mixed        	
	 */
	public function rawSelect($query);

	/**
	 * Get the results from the query
	 * 
	 * @return Illuminate\Support\Collection
	 */
	public function results();

	/**
	 * Paginate the results
	 * 
	 * @param  integer $amount 
	 * @return Illuminate\Support\Collection
	 */
	public function paginate($amount);

	/**
	 * Return the total results
	 * 
	 * @return integer
	 */
	public function count();
}
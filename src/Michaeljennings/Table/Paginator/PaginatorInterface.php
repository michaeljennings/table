<?php namespace Michaeljennings\Table\Paginator;

interface PaginatorInterface {
	
	/**
	 * Create a new paginator
	 * 
	 * @param  int $total   
	 * @param  int $perPage 
	 * @return mixed
	 */
	public function make($total, $perPage);

	/**
	 * Generate the links
	 * 
	 * @return view 
	 */
	public function links();

	/**
	 * Style each of the links
	 * 
	 * @param  Closure $callback 
	 * @return mixed        
	 */
	public function presenter(Closure $callback);

	/**
	 * Get a link for the last page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getFirst($label = 'First');

	/**
	 * Get a link for the last page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getLast($label = 'Last');

	/**
	 * Generate a link for the previous page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getNext($label = 'Next');

	/**
	 * Generate a link for the previous page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getPrev($label = 'Prev');

	/**
	 * Accessor for the links
	 * 
	 * @return string 
	 */
	public function getLinks();

	/**
	 * Check for a presenter callback and run it on the link if neccesary
	 * 
	 * @param  string   $link 
	 * @return string       
	 */
	public function callback($link);
}
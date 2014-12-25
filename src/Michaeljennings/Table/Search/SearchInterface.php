<?php namespace Michaeljennings\Table\Search;

interface SearchInterface {

	/**
	 * Get the results for the provided terms
	 * 
	 * @param  string $type  The search type to search in
	 * @param  string $terms The terms to search for
	 * @return array         An array of ids to search in
	 */
	public function getResults($terms, $type);
}
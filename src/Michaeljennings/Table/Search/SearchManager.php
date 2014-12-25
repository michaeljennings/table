<?php namespace Michaeljennings\Table\Search;

class SearchManager implements SearchInterface {

	/**
	 * An instance of the provided search class
	 * 
	 * @var mixed
	 */
	protected $search;

	/**
	 * Set the search class being used
	 * 
	 * @param mixed $search
	 */
	public function __construct($search)
	{
		$this->search = $search;
	}

	/**
	 * Get the results for the provided terms
	 * 
	 * @param  string $type  The search type to search in
	 * @param  string $terms The terms to search for
	 * @return array         An array of ids to search in
	 */
	public function getResults($terms, $type)
	{
		$results = $this->search->{$type}()->search($terms);
		$ids = array();

		foreach ($results as $result) {
			$ids[] = (int) $result->id;
		}

		return $ids;
	}
}
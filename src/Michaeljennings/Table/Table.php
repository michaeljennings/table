<?php namespace Michaeljennings\Table;

use Closure;
use Illuminate\Support\Collection;
use Michaeljennings\Table\Components\Row;
use Michaeljennings\Table\Components\Cell;
use Michaeljennings\Table\Components\Action;
use Michaeljennings\Table\Components\Column;
use Michaeljennings\Table\Exceptions\TableException;
use Michaeljennings\Table\Config\Config as TableConfig;

class Table {

	/**
	 * The table actions
	 * 
	 * @var array
	 */
	protected $actions = array();

	/**
	 * The table columns
	 * 
	 * @var array
	 */
	protected $columns = array();

	/**
	 * The table rows
	 * 
	 * @var array
	 */
	protected $rows = array();

	/**
	 * The table model
	 * 
	 * @var string/boolean
	 */
	protected $model = false;

	/**
	 * An instance of the model
	 * 
	 * @var mixed
	 */
	protected $instance;

	/**
	 * The reults to be displayed in the table
	 * 
	 * @var mixed
	 */
	protected $results;

	/**
	 * Set the amount to paginate by
	 * 
	 * @var integer/boolean
	 */
	protected $paginate = false;

	/**
	 * An array of queries to run on the model
	 * 
	 * @var array
	 */
	protected $filters = array();

	/**
	 * The table config
	 * 
	 * @var array
	 */
	protected $config = array();

	/**
	 * The table view
	 * 
	 * @var string
	 */
	protected $template;

	/**
	 * The table title
	 * 
	 * @var string
	 */
	protected $title;

	/**
	 * The url the table actions will post to
	 * 
	 * @var string
	 */
	protected $formAction;

	/**
	 * A unique for the table. This is used in the session names to prevent
	 * clashes when navigating between tables.
	 * 
	 * @var string
	 */
	protected $key;

	/**
	 * Set whether the table is sortable or not
	 * 
	 * @var boolean
	 */
	protected $sortable = true;

	/**
	 * Set whether the table is searchable or not
	 * 
	 * @var boolean
	 */
	protected $searchable = false;

	/**
	 * An optional search class to search the results with
	 * 
	 * @var mixed
	 */
	protected $search = false;

	/**
	 * The terms to search for
	 * 
	 * @var string
	 */
	protected $searchTerms;

	/**
	 * An array of search results
	 * 
	 * @var array
	 */
	protected $searchResults = array();

	/**
	 * The field to search for results in
	 * 
	 * @var string
	 */
	protected $searchKey = 'id';

	/**
	 * Create a new table instance
	 * 
	 * @param string  $key      The unique key for the table
	 * @param Closure $callback The closure to create the table from
	 * @param mixed   $search   An optional search class so we can search the 
	 *                          results
	 */
	public function __construct($key, Closure $callback, $search = false)
	{
		$this->key = $key;
		$this->config = TableConfig::get('config');
		$this->template = $this->config['template'];
		$this->session = new $this->config['session'];
		if ($search) $this->search = $search;

		if (isset($_GET['sort'])) {
			$this->session->put('michaeljennings.table.'.$this->key.'.sort', $_GET['sort']);
			if (isset($_GET['dir'])) {
				$this->session->put('michaeljennings.table.'.$this->key.'.dir', true);
			} else {
				$this->session->forget('michaeljennings.table.'.$this->key.'.dir');
			}
		}

		$callback($this);
	}

	/**
	 * Create a new action
	 * 
	 * @param  string $name     
	 * @param  string $position The position on the table, can be 'table' or 'row'
	 * @return Michaeljennings\Table\Components\Action
	 */
	public function action($name, $position = 'table')
	{
		$this->actions[$position][$name] = new Action($name);

		return $this->actions[$position][$name];
	}

	/**
	 * Create a new column
	 * 
	 * @param  string  $name
	 * @param  string $label
	 * @return Michaeljennings\Table\Components\Column
	 */
	public function column($name)
	{
		$this->columns[$name] = new Column($this->key, $name);
		$this->columns[$name]->label = htmlentities(ucwords(str_replace('_', ' ', $name)));

		return $this->columns[$name];
	}

	/**
	 * Set the table model
	 * 
	 * @param  string $model 
	 * @return void
	 */
	public function model($model)
	{
		$this->model = $model;
	}

	/**
	 * Add a filter to be run on the model
	 * 
	 * @param  Closure $callback 
	 * @return void
	 */
	public function filter(Closure $callback)
	{
		$this->filters[] = $callback;
	}

	/**
	 * Set the amount to paginate the results by
	 * 
	 * @param  integer $amount 
	 * @return void
	 */
	public function paginate($amount)
	{
		$this->paginate = $amount;
	}

	/**
	 * Set the table results
	 * 
	 * @param array $results
	 */
	public function setResults(array $results)
	{
		foreach ($results as $k => $r) {
			if (is_array($r)) {
				$results[$k] = (object) $r;
			}
		}
		$this->results = new Collection($results);
	}

	/**
	 * Get the results from the model
	 * 
	 * @return void
	 */
	public function getResults()
	{
		if (!$this->model) {
			throw new TableException('You need to set a model to get the results from');
		}

		// $query = $this->config['query'];
		$this->instance = new $this->config['query']($this->model);

		if (!empty($this->filters)) {
			foreach ($this->filters as $filter) {
				$filter($this->instance);
			}
		}

		$this->orderResults();

		if (empty($this->results)) {

			// Check if any search terms are set and if they are that the search
			// has returned results
			if ( empty($this->searchTerms) || (!empty($this->searchTerms) && !empty($this->searchResults)) ) {

				if (!$this->paginate) {
					$this->results = $this->instance->results();
				} else {
					$paginator = new $this->config['paginator'];
					$paginator = $paginator->make($this->instance->count(), $this->paginate);
					$this->links = $paginator->links();
					$this->results = $this->instance->paginate($this->paginate);
				}

			} else {
				// If the search has not returned any results then set the 
				// results to empty arrays. This prevents database errors when
				// trying to search for results in an empty array.
				if (!$this->paginate) {
					$this->results = array();
				} else {
					$paginator = new $this->config['paginator'];
					$paginator = $paginator->make(0, $this->paginate);
					$this->links = $paginator->links();
					$this->results = array();
				}
			}

		}
	}

	/**
	 * Order the results by the set column
	 */
	private function orderResults()
	{
		if ($this->session->has('michaeljennings.table.'.$this->key.'.sort')) {
			$this->sortBy = $this->session->get('michaeljennings.table.'.$this->key.'.sort');
			if ($this->session->has('michaeljennings.table.'.$this->key.'.dir')) {
				$this->sortDir = 'desc';
			}
		}

		if (isset($this->sortBy)) {
			$this->instance->refreshOrderBy();
			if (isset($this->sortDir)) {
				$this->instance->orderBy($this->sortBy, $this->sortDir);
			} else {
				$this->instance->orderBy($this->sortBy, 'asc');
			}
		}
	}

	/**
	 * Create the table rows
	 * 
	 * @return void
	 */
	public function rows()
	{
		if (empty($this->results)) {
			$this->getResults();
		}

		foreach ($this->results as $result) {
			$row = new Row;
			if (!empty($result->id)) {
				$row->id = $result->id;
			}

			foreach ($this->columns as $key => $column) {
				$row->cells[$key] = new Cell($result->$key, $result, $key, $column);
			}

			if (!empty($this->actions['row'])) {
				$actions = '';
    			foreach ($this->actions['row'] as $action) {
                    if ($action->valid($result)) {
                		$column = $action->getColumn();
                		$action->value = $result->$column;
                		$actions .= $action->render();
                    }
				}
				$row->cells[] = new Cell($actions);
			}

			$this->rows[] = $row;
		}

		if (!empty($this->actions['row'])) {
			$this->columns['option'] = new Column($this->key);
		}
	}

	/**
	 * Render the table
	 * 
	 * @return mixed
	 */
	public function render()
	{
		$this->rows();
		$view = new $this->config['view'];

		return $view->make($this->template, array('table' => $this));
	}

	/**
	 * Search the results of the table for the given terms with in a type. If no
	 * search type is specified we will us the model name given.
	 * 
	 * @param  string  $terms
	 * @param  string $index
	 * @return Michaeljennings\Table\Table
	 */
	public function search($terms, $type = false)
	{
		if ( ! $this->search ) {
			throw new TableException('No search class has been specified. Please specify a search class in the TableServiceProvider.');
		}

		if ( ! $type ) {
			$type = $this->model;
		}

		$results = $this->search->getResults($terms, $type);
		$this->searchResults = $results;
		$this->searchTerms = $terms;

		$this->filter(function($q) use ($results)
		{
			$q->whereIn($this->searchKey, $results);
			// Order by the results
			$q->orderBy(\DB::raw('FIELD('. $this->searchKey .', ' . implode(',', $results) . ')'), 'ASC');
		});

		return $this;
	}

	/**
	 * Set whether the table is searchable or not
	 * 
	 * @param boolean $searchable
	 */
	public function searchable($searchable = true)
	{
		$this->searchable = $searchable;
	}

	/**
	 * Set whether the table is sortable or not
	 * 
	 * @param  boolean $sortable
	 * @return void
	 */
	public function sortable($sortable = false)
	{
		$this->sortable = $sortable;
	}

	/**
	 * Set the unique key for this table
	 * 
	 * @param string $key
	 */
	public function setKey($key)
	{
		$this->key = $key;
	}

	/**
	 * Set the template for this table instance
	 * 
	 * @param string $template
	 */
	public function setTemplate($template)
	{
		$this->template = $template;
	}

	/**
	 * Set the table title
	 * 
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Set the url the table actions post to
	 * 
	 * @param string $action
	 */
	public function setFormAction($action)
	{
		$this->formAction = $action;
	}

	/**
	 * Set the database field to search for results in
	 * 
	 * @param string $key
	 */
	public function setSearchKey($key)
	{
		$this->searchKey = $key;
	}

	/**
	 * Return the table title
	 * 
	 * @return string
	 */
	public function getTitle()
	{
		return empty($this->title) ? htmlentities(ucwords(str_replace('_', ' ', $this->model))) : $this->title;
	}

	/**
	 * Check if there are any rows
	 * 
	 * @return boolean
	 */
	public function hasRows()
	{
		return !empty($this->rows);
	}

	/**
	 * Get the table row
	 * 
	 * @return array
	 */
	public function getRows()
	{
		return $this->rows;
	}

	/**
	 * Check if there are any table actions
	 * 
	 * @return boolean
	 */
	public function hasActions($postition)
	{
		return !empty($this->actions[$postition]);
	}

	/**
	 * Get the table actions
	 * 
	 * @return array
	 */
	public function getActions($postition)
	{
		return !empty($this->actions[$postition]) ? $this->actions[$postition] : false;
	}

	/**
	 * Check if there are any table columns
	 * 
	 * @return boolean
	 */
	public function hasColumns()
	{
		return !empty($this->columns);
	}

	/**
	 * Get the table columns
	 * 
	 * @return array
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Check if there are any table links
	 * 
	 * @return boolean
	 */
	public function hasLinks()
	{
		return !empty($this->links);
	}

	/**
	 * Get the table links
	 * 
	 * @return array
	 */
	public function getLinks()
	{
		return $this->links;
	}

	/**
	 * Check if any search terms have been set
	 * 
	 * @return boolean
	 */
	public function hasSearchTerms()
	{
		return !empty($this->searchTerms);
	}

	/**
	 * Retrieve the search terms
	 * 
	 * @return string
	 */
	public function getSearchTerms()
	{
		return $this->searchTerms;
	}

	/**
	 * Check if a form action has been set
	 * 
	 * @return boolean
	 */
	public function hasFormAction()
	{
		return !empty($this->formAction);
	}

	/**
	 * Get the form action
	 * 
	 * @return string
	 */
	public function getFormAction()
	{
		return $this->formAction;
	}

	/**
	 * Check if any search results were found
	 * 
	 * @return boolean
	 */
	public function hasSearchResults()
	{
		return $this->searchResults;
	}

	/**
	 * Get the total amount of search results
	 * 
	 * @return integer
	 */
	public function getSearchResultsTotal()
	{
		return count($this->searchResults);
	}

	/**
	 * Check whether the table is searchable
	 * 
	 * @return boolean
	 */
	public function isSearchable()
	{
		return $this->searchable;
	}

	/**
	 * Check if the table is sortable or not
	 * 
	 * @return boolean
	 */
	public function isSortable()
	{
		return $this->sortable;
	}
}
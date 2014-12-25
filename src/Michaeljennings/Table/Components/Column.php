<?php namespace Michaeljennings\Table\Components;

use Closure;
use Illuminate\Support\Fluent;
use Michaeljennings\Table\Config\Config as TableConfig;

class Column extends Fluent {

	/**
	 * The presenter callback
	 * 
	 * @var Closure
	 */
	protected $presenter;

	/**
	 * The link for the header
	 * 
	 * @var string
	 */
	protected $href;

	/**
	 * A spreadsheet cell closure
	 * 
	 * @var Closure
	 */
	protected $spreadsheetCell;

	/**
	 * An instance of the session class
	 * 
	 * @var object
	 */
	private $session;

	/**
	 * The current tables unique key
	 * 
	 * @var string
	 */
	private $tableKey;

	public function __construct($tableKey, $column = false)
	{
		$config = TableConfig::get('config');
		$this->session = new $config['session'];

		$this->tableKey = $tableKey;
		if ($column) {
			$this->createHref($column);
		}
	}

	/**
	 * Create the href for the column
	 * 
	 * @param  string $column 
	 * @return void         
	 */
	private function createHref($column)
	{
		if ($this->session->get('gmlconsulting.table.'.$this->tableKey.'.sort') == $column) {
			if ($this->session->has('gmlconsulting.table.'.$this->tableKey.'.dir')) {
				$splitUrl = explode('?', $_SERVER['REQUEST_URI']);
				if(count($splitUrl) < 2)
				{
					$this->session->forget('gmlconsulting.table.'.$this->tableKey.'.sort');
					$this->session->forget('gmlconsulting.table.'.$this->tableKey.'.dir');
					$this->href = '?sort='.$column;
				} else {
					$this->href = $splitUrl[0];
					$this->sort = 'up';
				}
			} else {
				$this->href = '?sort='.$column.'&dir=desc';
				$this->sort = 'down';
			}
		} else {
			$this->href = '?sort='.$column;
		}
	}

	/**
	 * Set the presenter callback for the column cells
	 * 
	 * @param  Closure $callback 
	 */
	public function presenter(Closure $callback)
	{
		$this->presenter = $callback;
	}

	/**
	 * Check if there is a presenter callback for the column
	 * @return boolean 
	 */
	public function hasPresenter()
	{
		return is_null($this->presenter) ? false : true;
	}

	/**
	 * Accessor for the presenter
	 * @return Closure|boolean
	 */
	public function getPresenter()
	{
		if (is_null($this->presenter)) return false;

		return $this->presenter;
	}

	/**
	 * Create a new spreadsheet cell
	 *
	 * @param  Closure $callback
	 * @return void
	 */
	public function spreadsheetCell(Closure $callback)
	{
		$this->spreadsheetCell = $callback;
	}

	/**
	 * Check if there is a callback for the spreadsheet cell
	 * 
	 * @return boolean
	 */
	public function hasSpreadsheetCell()
	{
		return is_null($this->spreadsheetCell) ? false : true;
	}

	/**
	 * Get the spreadsheet cell callback
	 * 
	 * @return Closure
	 */
	public function getSpreadsheetCell()
	{
		if (is_null($this->spreadsheetCell)) return false;

		return $this->spreadsheetCell;
	}

	/**
	 * Accessor for the columns href
	 * 
	 * @return string 
	 */
	public function getHref()
	{
		return $this->href;
	}

	/**
	 * Set an undefined item into the attributes array
	 * 
	 * @param  string $name      The attribute name
	 * @param  array  $arguments The attribute arguments
	 * @return object            Self
	 */
	public function __call($name, $arguments)
    {
        $this->attributes[$name] = $arguments[0];
        return $this;
    }	
}
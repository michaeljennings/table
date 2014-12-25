<?php namespace Michaeljennings\Table\Components;

class SpreadsheetCell {

	/**
	 * The cell type and name
	 * 
	 * @var array
	 */
	protected $type = array();

	/**
	 * Set the values for the cell
	 * 
	 * @var array
	 */
	protected $values = array();

	/**
	 * Set the default value for a cell
	 * 
	 * @var Mixed
	 */
	protected $default;

	/**
	 * The cell value
	 * 
	 * @var Mixed
	 */
	protected $value;

	/**
	 * The id of the table row
	 * 
	 * @var integer
	 */
	protected $id;

	public function __construct($value, $rowId)
	{
		$this->value = $value;
		$this->id = $rowId;
	}

	/**
	 * Set the type to a text input
	 * 
	 * @param  string $name 
	 * @return Mixed
	 */
	public function text($name)
	{
		$this->type = array(
			'text' => $name,
		);

		return $this;
	}

	/**
	 * Set the type to a textare
	 * 
	 * @param  string $name 
	 * @return Mixed
	 */
	public function textarea($name)
	{
		$this->type = array(
			'textarea' => $name,
		);

		return $this;
	}

	/**
	 * Set the type to a select
	 * 
	 * @param  string $name   
	 * @param  array $values 
	 * @return Mixed
	 */
	public function select($name, array $values)
	{
		$this->type = array(
			'select' => $name,
		);

		$this->values = $values;

		return $this;
	}

	/**
	 * Set the cell value
	 * 
	 * @param  Mixed $value 
	 * @return Mixed
	 */
	public function value($value)
	{
		$this->value = $value;

		return $this;
	}

	/**
	 * Render the spreadsheet cell
	 * 
	 * @return string
	 */
	public function render()
	{
		switch (key($this->type)) {
			case "text":
				return $this->renderText();
				break;
			case "textarea":
				return $this->renderTextarea();
				break;
			case "select":
				return $this->renderSelect();
				break;
			default:
				return $this->value;
				break;
		}
	}

	/**
	 * Render a text cell
	 * 
	 * @return string
	 */
	private function renderText()
	{
		$type = key($this->type);
		$name = $this->type[$type];

		return '<input type="' . $type .'" name="results[' . $this->id . '][' . $name . ']" value="' . $this->value .'" />';
	}

	/**
	 * Render a text cell
	 * 
	 * @return string
	 */
	private function renderTextarea()
	{
		$type = key($this->type);
		$name = $this->type[$type];

		return '<input type="text" name="results[' . $this->id . '][' . $name . ']" value="' . $this->value .'" />';
	}

	/**
	 * Render a select cell
	 * 
	 * @return string
	 */
	private function renderSelect()
	{
		$type = key($this->type);
		$name = $this->type[$type];

		$select = '<select name="results[' . $this->id . '][' . $name .']">';

		foreach ($this->values as $key => $val) {
			$select .= '<option value="' . $key .'" '. ($val == $this->value ? 'selected' : '') .'>' . $val .'</option>';
		}

		$select .= '</select>';

		return $select;
	}
}
<?php

return array
(
	/*
	 | ==========================================================
	 |	The Template
	 | ==========================================================
	 |	
	 |	The default template to be used by the table class, If you need to 
	 |  use a different template for a specific table you can use the 
	 |  setTemplate function.
	 |
	 */
	'template' => 'michaeljennings/table::default/table',

	/*
	 | ==========================================================
	 |	Paginator
	 | ==========================================================
	 |	
	 |	The paginator class to be used.
	 |
	 */
	'paginator' => 'Michaeljennings\Table\Paginator\Paginator',

	/*
	 | ==========================================================
	 |	Paginator Template
	 | ==========================================================
	 |	
	 |	The template to be used to display the pagination.
	 |
	 */
	'paginationTemplate' => 'michaeljennings/table::default/pagination',

	/*
	 | ==========================================================
	 |	Query
	 | ==========================================================
	 |	
	 |	The query class to be used.
	 |
	 */
	'query' => 'Michaeljennings\Table\Query\Eloquent\Query',

	/*
	 | ==========================================================
	 |	Session
	 | ==========================================================
	 |	
	 |	The session class to be used
	 |
	 */
	'session' => 'Michaeljennings\Table\Sessions\IlluminateSession',

	/*
	 | ==========================================================
	 |	View
	 | ==========================================================
	 |	
	 |	The view class to be used.
	 |
	 */
	'view' => 'Michaeljennings\Table\Views\IlluminateView',

	/*
	 | ==========================================================
	 |	Table file
	 | ==========================================================
	 |	
	 |	The file that has all of your tables in.
	 |
	 */
	'tableFile' => app_path().'/tables.php',
);
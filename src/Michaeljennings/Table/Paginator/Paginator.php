<?php namespace Michaeljennings\Table\Paginator;

use Closure;
use Illuminate\Support\Fluent;
use Michaeljennings\Table\Config\Config as TableConfig;

class Paginator extends fluent {

	/**
	 * The pagination links
	 * 
	 * @var string
	 */
	protected $links;

	/**
	 * The pagination template
	 * 
	 * @var string
	 */
	protected $template;

	/**
	 * The total paginated pages
	 * 
	 * @var integer
	 */
	protected $totalPages;

	/**
	 * The current page we are on
	 * 
	 * @var integer
	 */
	protected $currentPage;

	public function __construct()
	{
		$this->config = TableConfig::get('config');

		$this->view = new $this->config['view'];
		$this->template = $this->config['paginationTemplate'];
	}

	/**
	 * Create a new paginator
	 * 
	 * @param  int $total   
	 * @param  int $perPage 
	 * @return Gmlconsulting\Table\Paginator\Paginator
	 */
	public function make($total, $perPage)
	{
		$this->totalPages = ceil($total / $perPage);

		if (isset($_GET['page'])) {
			$this->currentPage = $_GET['page'];
		} else {
			$this->currentPage = 1;
		}

		if ($this->totalPages > 1) {
			for ($i=1; $i < ($this->totalPages + 1); $i++) {
				if ($this->currentPage == $i) {
					$this->links[] ='<li class="active"><a href="?page='.$i.'">'.$i.'</a></li>';
				} else {
					$this->links[] ='<li><a href="?page='.$i.'">'.$i.'</a></li>';
				}
			}
		} else {
			$this->links = array();
		}

		return $this;
	}

	/**
	 * Generate the links
	 * 
	 * @return view 
	 */
	public function links()
	{
		return $this->view->make($this->template, array('paginator' => $this));
	}

	/**
	 * Style each of the links
	 * 
	 * @param  Closure $callback 
	 * @return Gmlconsulting\Table\Paginator\Paginator        
	 */
	public function presenter(Closure $callback)
	{
		$this->presenter = $callback;
		return $this;
	}

	/**
	 * Render the links
	 * 
	 * @return string
	 */
	public function renderLinks()
	{
		if ($this->totalPages < 9) {
			$links = '';

			foreach ($this->getLinks() as $link) {
				$links .= $link;
			}

			return $links;
		} else {
			return $this->renderPrettyLinks();
		}
	}

	/**
	 * Render the links so that the amount of links are limited and the active
	 * link is always visible
	 * 
	 * @return string
	 */
	public function renderPrettyLinks($limit = 8)
	{
		if ($this->totalPages < $limit) {
			return $this->renderLinks();
		}

		$i = 0;
		$links = '';

		if ($this->currentPage < $limit) {
			// return links with ellipsis at the end
			foreach ($this->getLinks() as $link) {
				if ($i < 9) {
					$links .= $link;
				} else {
					$links .= '<li><a>...</a></li>';
					$links .= '<li>'.$this->getLast().'</li>';
					break;
				}
				$i++;
			}
		} elseif ($this->currentPage > ($this->totalPages - $limit)) {
			// return links with ellipsis at the start
			$links .= '<li>'.$this->getFirst().'</li>';
			$links .= '<li><a>...</a></li>';
			$l = $this->getLinks();
			for ($i = ($this->totalPages - 9); $i < $this->totalPages; $i++) {
				$links .= $l[$i];
			}
		} else {
			// return links with ellipsis at both ends
			$links .= '<li>'.$this->getFirst().'</li>';
			$links .= '<li><a>...</a></li>';
			$l = $this->getLinks();
			for ($i = ($this->currentPage - 4); $i < ($this->currentPage + 3) ;$i++) {
				$links .= $l[$i];
			}
			$links .= '<li><a>...</a></li>';
			$links .= '<li>'.$this->getLast().'</li>';
		}

		return $links;
	}

	/**
	 * Get a link for the last page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getFirst($label = false)
	{
		if (!$label) {
			$label = 1;
		}

		return $this->callback('<a href="?page=1">'.$label.'</a>');
	}

	/**
	 * Get a link for the last page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getLast($label = false)
	{
		if (!$label) {
			$label = $this->totalPages;
		}

		return $this->callback('<a href="?page='.$this->totalPages.'">'.$label.'</a>');
	}

	/**
	 * Generate a link for the previous page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getNext($label = 'Next')
	{
		if ($this->currentPage < $this->totalPages) {
			return $this->callback('<a href="?page='.($this->currentPage + 1).'">'.$label.'</a>');
		} else {
			return false;
		}
	}

	/**
	 * Generate a link for the previous page
	 * 
	 * @param  string $label 
	 * @return string        
	 */
	public function getPrev($label = 'Prev')
	{
		if ($this->currentPage > 1) {
			return $this->callback('<a href="?page='.($this->currentPage - 1).'">'.$label.'</a>');
		} else {
			return false;
		}
	}

	/**
	 * Accessor for the links
	 * 
	 * @return string 
	 */
	public function getLinks()
	{
		return $this->links;
	}

	/**
	 * Check for a presenter callback and run it on the link if neccesary
	 * 
	 * @param  string   $link 
	 * @return string       
	 */
	public function callback($link)
	{
		if (!empty($this->presenter)) {
			$callback = $this->presenter;
			return $callback($link);
		} else {
			return $link;
		}
	}
}
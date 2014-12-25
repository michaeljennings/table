<?php namespace Michaeljennings\Table\Paginator;

use Illuminate\Http\Request as Request;
use Illuminate\Events\Dispatcher as Dispatcher;
use Illuminate\Pagination\Paginator as Paginator;
use Illuminate\View\Environment as ViewEnvironment;
use Illuminate\Filesystem\Filesystem as Filesystem;
use Illuminate\View\FileViewFinder as FileViewFinder;
use Symfony\Component\Translation\Translator as Translator;
use Illuminate\View\Engines\EngineResolver as EngineResolver;
use Illuminate\Pagination\Environment as PaginatorEnvironment;
use Illuminate\View\ViewFinderInterface as ViewFinderInterface;

class IlluminatePaginator {

	/**
	 * Create a new paginator
	 * 
	 * @param  array  $items   
	 * @param  int $total   
	 * @param  int $perPage 
	 * @return Illuminate\Pagination\Paginator
	 */
	public function make($items = array(), $total, $perPage) {

		var_dump(new Filesystem); die();

		$view = new ViewEnvironment(new EngineResolver, new FileViewFinder(new Filesystem, array()), new Dispatcher);

		$env = new PaginatorEnvironment(new Request, $view, new Translator('en'));

		$paginator = new Paginator($env, $items, $total, $perPage);

		return $paginator;
	}
}
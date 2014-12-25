<?php namespace Michaeljennings\Table;

use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('michaeljennings/table', 'michaeljennings/table');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('Michaeljennings\\Table\\Search\\SearchInterface', function($app)
		{
			if (isset($app['michaeljennings.search'])) {
				return new Search\SearchManager($app['michaeljennings.search']);
			} else {
				return null;
			}
		});

		$this->app->bind('table', function($app)
		{
			return new TableCollection($app->make('Michaeljennings\\Table\\Search\\SearchInterface'));
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}

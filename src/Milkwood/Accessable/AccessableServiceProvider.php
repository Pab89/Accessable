<?php namespace Milkwood\Accessable;

use Illuminate\Support\ServiceProvider;

class AccessableServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->publishes([
		    __DIR__.'/Authenticate.php' => base_path('/app/Http/Middleware/Authenticate.php')
		], 'middleware');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}

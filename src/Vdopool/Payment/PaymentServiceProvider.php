<?php namespace Vdopool\Payment;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider {

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
		$this->package('vdopool/payment');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
        {
          $this->app['sms'] = $this->app->share(function($app)
          {
            return new Payment($app);
          });

          $this->app->booting(function()
          {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Payment', 'Vdopool\Payment\Facades\Payment');
          });
        }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('payment');
	}

}

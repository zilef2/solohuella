<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider {
	
	/**
	 * Register any application services.
	 */
	public function register(): void {
		//
	}
	
	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void {
		Inertia::share([
			               'language' => fn() => [
				               'original' => app()->getLocale(),
			               ],
		               ]);
		
		// Si detecta que estamos entrando por un túnel o proxy (ngrok)
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) || isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
			URL::forceRootUrl(config('app.url'));
			URL::forceScheme('https');
		}
	}
}

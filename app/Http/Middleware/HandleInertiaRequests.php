<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware {
	
	/**
	 * The root template that's loaded on the first page visit.
	 *
	 * @see https://inertiajs.com/server-side-setup#root-template
	 *
	 * @var string
	 */
	protected $rootView = 'app';
	
	/**
	 * Determines the current asset version.
	 *
	 * @see https://inertiajs.com/asset-versioning
	 */
	public function version(Request $request): ?string {
		return parent::version($request);
	}
	
	/**
	 * Define the props that are shared by default.
	 *
	 * @see https://inertiajs.com/shared-data
	 *
	 * @return array<string, mixed>
	 */
	public function share(Request $request): array {
		[$message, $author] = str(Inspiring::quotes()->random())->explode('-');
		
//		$locale = App::getLocale();
		$locale = 'es';
		$translationPath = base_path("lang/{$locale}");
		$files = File::files($translationPath);
		
		$translations = [];
		foreach ($files as $file) {
			$filename = $file->getFilenameWithoutExtension();
			// Incluir el archivo PHP de traducciones (retorna array)
			$translations[$filename] = require $file->getRealPath();
		}
		
		
		return [
			...parent::share($request),
			'name'  => config('app.name'),
			'quote' => ['message' => trim($message), 'author' => trim($author)],
			'auth'  => [
				'user' => $request->user(),
				'can'  => $request->user() ? $request->user()->getPermissionArray() : [],
			],
			
			'ziggy'        => [
				...(new Ziggy)->toArray(),
				'location' => $request->url(),
			],
			'flash'        => [
				'success' => fn() => $request->session()->get('success'),
				'error'   => fn() => $request->session()->get('error'),
				'warning' => fn() => $request->session()->get('warning'),
				'info'    => fn() => $request->session()->get('info'),
			],
			'sidebarOpen'  => !$request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
			'locale'       => $locale,              // por ejemplo "es"
			'translations' => $translations,
		];
	}
}

<?php

namespace App\Http\Controllers;

use AllowDynamicProperties;
use App\Models\Proceso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

#[AllowDynamicProperties]
class PruebasController extends Controller {
	
	public function __construct() {
		$this->titleindex = 'Huella';
	}
	
	public function index(Request $req) {
		Carbon::setLocale('es');
		
		return Inertia::render('huella', [
			'title'                 => $this->titleindex,
			'filters'               => $req->all(['search', 'field']),
		]);
	}
	
}

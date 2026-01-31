<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use lbuchs\WebAuthn\WebAuthn;

class HuellaController extends Controller {
	
	protected WebAuthn $webAuthn;
	
	/**
	 * @throws \lbuchs\WebAuthn\WebAuthnException
	 */
	public function __construct() {
		// Esto detecta automáticamente si estás en ngrok o en solohuella.test
		$host = parse_url(url('/'), PHP_URL_HOST);
		$this->webAuthn = new WebAuthn('SoloHuella', $host);
	}
	
	// Coincide con tu router.post y router.get a /alumnos/options
	public function getOptions(Request $request) {
		if ($request->isMethod('post')) {
			// Registro
			$userName = $request->name ?? 'Alumno';
			$userId = bin2hex(random_bytes(16));
			//$registrationResponse = json_decode($request->registrationResponse, true);
			$args = $this->webAuthn->getCreateArgs($userId, $userName, $userName);
		}
		else {
			// Reconocimiento
			$args = $this->webAuthn->getGetArgs();
		}
		
		session(['webauthn_challenge' => $this->webAuthn->getChallenge()]);
		
		return Inertia::render('huella', [
			'options' => $args
		]);
	}
	
	public function confirmarRegistro(Request $request) {
		$user = new User();
		$user->name = $request->name;
		$user->credential_id = $request->registrationResponse['id'];
		$user->public_key = serialize($request->registrationResponse['response']);
		$user->save();
//		dd(
//		    $user
//		);
		return redirect()->back()->with('alumno_identificado', 'Alumno guardado');
	}
	
	// Coincide con tu router.post('/alumnos/identificar'...)
	public function identificar(Request $request) {
		$alumno = User::where('credential_id', $request->id)->first();
		
		$nombre = $alumno ? $alumno->name : 'No reconocido';
		
		return redirect()->back()->with('alumno_identificado', $nombre);
	}
}
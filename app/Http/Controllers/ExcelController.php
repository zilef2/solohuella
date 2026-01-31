<?php

namespace App\Http\Controllers;

use App\helpers\Myhelp;

use App\helpers\HelpExcel;
use App\Imports\PersonalImport;
use App\Jobs\ImportGenericousChunkJob;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller {
	
	public function uploadAlumnoss(Request $request): \Illuminate\Http\RedirectResponse //import
	{
		ini_set('max_execution_time', 360); // 6 minutos
		
		$pesoMaximo = 8192;
		$pesoString = (int)($pesoMaximo / 1000) . 'MB';
		$VariablesEsteProyecto = [
			'log'          => 'Este es el log de la importación de Genericous',
			'Validaciones' => [
				'formatos1'           => 'xlsx',
				'formatos2'           => 'xls',
				'mensajeErrorFormato' => 'xls',
				'pesoMaximo'          => $pesoMaximo,
				'pesoMaximoerror'     => 'El archivo debe pesar menos de ' . $pesoString,
			],
		];
		
		$exten = $request->archivo1->getClientOriginalExtension();
		if ($exten != 'xlsx' && $exten != 'xls') {
			return back()->with('warning', 'El archivo debe ser de Excel');
		}
		Myhelp::EscribirEnLog($this, $VariablesEsteProyecto['log'], ' Subir a excel, paso las primeras validaciones');
		$pesoKilobyte = ((int)($request->archivo1->getSize())) / (1024);
		if ($pesoKilobyte > $pesoMaximo) {
			return back()->with('warning', $VariablesEsteProyecto['Validaciones']['pesoMaximoerror']);
		}
		
		try {
			$ruta = $request->file('archivo1')->storeAs('importGenericous', uniqid() . '_' . $request->file('archivo1')->getClientOriginalName());
			$theEmail = Myhelp::AuthU()->email;
			ImportGenericousChunkJob::dispatch($ruta, $theEmail);
			
			return back()->with('success', 'La importación está en proceso. Recibirás un correo al finalizar.');
			
		} catch (ValidationException $e) {
			ini_set('max_execution_time', 180); // 3 minutos
			
			$failures = $e->failures();
			$errorRows = collect($failures)->map(function ($failure) {
				$rowNumber = $failure->row();
				$attribute = $failure->attribute();
				$errors = $failure->errors();
				$values = $failure->values();
				
				$errorMessage = "Fila {$rowNumber}, Columna '{$attribute}': ";
				$errorMessage .= implode(', ', $errors);
				$errorMessage .= " (Valores: " . json_encode($values) . ")";
				
				return $errorMessage;
			});
			
			$errorSummary = $errorRows->implode('; ');
			
			$message = 'Se encontraron errores en el archivo Excel.';
			if ($errorSummary) {
				$message .= ' Detalles: ' . $errorSummary;
			}
			else {
				$message .= ' Detalles: ' . $e->getMessage(); // Mostrar el mensaje original si no hay fallas detalladas
			}
			
			return back()->with('warning', 'Error Excel. ' . $e->getMessage() . '. filas con errores: ' . $message);
		}
	}
	
	
	
	
	private function MensajeWar($import) {
		$contares = [
			$import->contar1,
			$import->contar2,
			$import->contar3,
			$import->contarVacios,
		];
		$mensajesWarnings = [
			'Cedulas repetidas: ',
			'nombre vacio: ',
			'#identificacions no numericas: ',
			
			'#filas con celdas vacias: ',
		];
		
		$mensaje = '';
		foreach ($mensajesWarnings as $key => $value) {
			if ($contares[$key] > 0) {
				$mensaje .= $value . $contares[$key] . '. ';
			}
		}
		
		return $mensaje;
	}
	
	public function deployartisandown() {
		echo Artisan::call('down --secret="token-it"');
		
		return "Aplicación abajo: token-it";
	}
}

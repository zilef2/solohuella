<?php

namespace App\Jobs;

use App\Imports\GenericImport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportGenericousChunkJob implements ShouldQueue
{
    //php artisan queue:work --queue=default --daemon --sleep=1 --tries=1 --max-jobs=100
	
	use Dispatchable, InteractsWithQueue, SerializesModels,Queueable;

    /**
     * Tiempo máximo de ejecución en segundos
     *php artisan make:import GenericImport --model=Ordenproduccion
     * @var int
     */
    public int $timeout = 10120;
    public array $backoff = [5, 15, 30]; // segundos
	
	public int $tries = 2;
	
	public string $rutaArchivo;
	public string $email;
	
	public function __construct(string $rutaArchivo, $email = 'ajelof2@gmail.com') {
		$this->rutaArchivo = $rutaArchivo;
		$this->email = $email;
	}
	
	public function handle() {
		try {
			
			Log::channel('solosuper')->info('estamos en job 1');
			
			
			$genericImport = new GenericImport();

			
			$fullPath = storage_path('app/' . $this->rutaArchivo);
			
			if (!file_exists($fullPath)) {
				Log::channel('solosuper')->error("Archivo no encontrado: " . $fullPath);
				
				return;
			}
			
			Excel::import($genericImport, $fullPath);
			
			// Preparar el mensaje
			$filasAc = $genericImport->nFilasNuevas;
			$filasNew = $genericImport->nFilasActualizadas;
			$filasLeidas = $filasAc + $filasNew;
			
			$mensajefin = $filasNew . ' filas nuevas.  ' 
				. $filasAc . ' filas actualizadas.  ' 
				. $filasLeidas . ' en total.';
			
			// Enviar el correo
			Log::channel('solosuper')->info('Empezamos a mandar correo');
			
			if (app()->environment('local') || app()->environment('test')) {
				$mensajeFinal = 'Este es un mensaje de prueba. ' . $mensajefin;
				Log::channel('solosuper')->info('Estamos en local o test, no se envian correos');
				Log::channel('solosuper')->info($mensajeFinal);
			}
//			else {
//				if ($this->email !== 'ajelof2@gmail.com') {
//					Mail::to('ajelof2@gmail.com')->send(new ImportacionFinalizada($mensajeFinal));
//				}
//				Mail::to($this->email)->send(new ImportacionFinalizada($mensajeFinal));
//			}
//			Log::channel('solosuper')->info('Correos enviados');
			
		} catch (\Throwable $e) {
			Log::channel('solosuper')->error($e->getMessage() . ' - || - ' . $e->getLine() . ' - - ' . $e->getFile());
			
		}
	}
}

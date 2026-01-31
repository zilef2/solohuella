<?php

namespace App\Imports;

use App\helpers\HelpExcel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Throwable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

use App\Models\Ordenproduccion;

class GenericImport implements ToCollection, WithHeadingRow, SkipsOnError, WithChunkReading, ShouldQueue {
	
	public int $numeroFilas = 1;
	public bool $interrupcionPorExcesoDeErrores;
	public int $numeroFilasConErrores = 0;
	public string $MensajeError = '';
	public array $MensajeErrorArray = [];
	
	//propias del proyecto
	public array $ordensExistentes = [];
	public int $nFilasNuevas = 0;
	public int $nFilasActualizadas = 0;
	public int $nFilasOmitidas = 0;
	public int $nFilasSinPrecio = 0;
	public int $nFilasSinFecha = 0;
	public string $nombredebugTXT = 'debugGenericImport';
	
	public $ForbidenCodes = [ //$razones[] = 'Hay un código prohibido. ';
		'#VALUE!',
		'',
		' ',
	];
	private string $columnaIndice = 'op';
	private array $camposObligatorios = [
//		'pedido' => 'No se pudo leer la columna PEDIDO. ',
		'op'     => 'No se pudo leer la columna OP. ',
	];
	
	private array $hacerZeroSiSonNulos = [ //solo columnas de numeros
//		'pedido',
//		'op'
	];
	private array $lasColumnas = [ //pegar las columnas de excel
	
	];
	// Campos que deben ser convertidos en fecha
	private array $camposFecha = [
		"fecha_solicitud" => 'fecha_solicitud',
	];
	
	// Campos a mapear directamente desde la fila
	private array $camposMapeados = [
		//como esta en excel  =>  como esta en el model
//		"pedido"               => 'pedido',
		"op"                   => 'op',
		"cliente"              => 'cliente',
		"obra "                => 'obra',
//		"contrato"             => 'contrato',
		"producto_descripcion" => 'producto_descripcion',
		"asesor"               => 'asesor',
		"estado"               => 'estado',
		"cantidad"               => 'cant',
		//		fecha	
	
	];
	
	public function chunkSize(): int { return 250; }
	
	/**
	 * @param \Illuminate\Support\Collection $collection
	 * @return void|null
	 */
	public function collection(Collection $collection) {
		Log::channel('solosuper')->info('Job iniciado');
		
		register_shutdown_function(function (): void {
			$error = error_get_last();
			if ($error && str_contains($error['message'], 'Allowed memory size')) {
				Log::channel('solosuper')->error('Job detenido por falta de memoria');
			}
		});
		
		$this->MensajeErrorArray = [];
		
		if ($collection->isEmpty()) {
			Log::channel('solosuper')->info('Import vacío — nada que procesar');
			
			return null;
		}
		
		$first = $collection->first();
		if (!$first) {
			return null;
		}
		
		$this->interrupcionPorExcesoDeErrores = false;
		file_put_contents(storage_path("logs/$this->nombredebugTXT.txt"), print_r('Antes del ciclo, linea 105 --- ' . Carbon::now(), true), FILE_APPEND);
		$ArrayMensajeome = [];
		
		foreach ($collection as $row) {//foreach principal
			$keys = implode(', ', $row->keys()->toArray());
			Log::channel('solosuper')->info('indices row: ' . $keys);
			Log::channel('solosuper')->info('  ' . $this->columnaIndice);
			Log::channel('solosuper')->info('this columna indice  ' . $this->columnaIndice);
			Log::channel('solosuper')->info('$row[$this->columnaIndice]  ' . $row[$this->columnaIndice]);
			$this->numeroFilas ++;
			
			
			if (!isset($row[$this->columnaIndice]) || $row[$this->columnaIndice] === "op") {
				$ArrayMensajeome[] = '!!row omitida: Sin ' . $this->columnaIndice;
				Log::channel('solosuper')->info('Genericimport  no hay indice');
				
				$this->nFilasOmitidas ++;
				continue;
			}
			
			else {
				
				Log::channel('solosuper')->info('Genericimport  hay indice');
				//todobien
				if (!$row[$this->columnaIndice] || in_array($row[$this->columnaIndice], $this->ForbidenCodes)) {
					$razones = [];
					
					// Validaciones dinámicas de campos obligatorios
					foreach ($this->camposObligatorios as $campo => $mensaje) {
						if (empty($row[$campo])) {
							$razones[] = $mensaje;
						}
					}
					
					// Validación de códigos prohibidos
					if (in_array($row[$this->columnaIndice], $this->ForbidenCodes)) {
						$razones[] = 'Hay un código prohibido. ';
					}
					
					$this->nFilasOmitidas ++;
					
					$mensajeFinal = '!!_!row omitida: ' . implode(', ', $row->toArray()) . ' :: ' . implode('', $razones);
					
					Log::channel('solosuper')->info('Desde ordenImport: ' . $mensajeFinal);
					
					continue;
				}
				
			}
			
			Log::channel('solosuper')->info('revisando, existe indice:: ' . $row[$this->columnaIndice]);
			
			if (isset($row[$this->columnaIndice])) {
				//inicio validaciones
				$ValidRow0 = $this->Validarvacios($row);
				$this->TransformarNumeros($row);
				//fin validaciones
				Log::channel('solosuper')->info('Job en fase1: existe la columnaindice');
				
				if ((strcmp($ValidRow0, '') === 0)) {
					Log::channel('solosuper')->info('Job en fase2: no hay vacios');
					
					$this->CrearYContar($row);
				}
				else {
					$this->numeroFilasConErrores ++;
					$this->MensajeErrorArray[] = $ValidRow0 . ' -- En la fila ' . $this->numeroFilas . ' ';
					if ($this->numeroFilasConErrores > 10) {
						$this->interrupcionPorExcesoDeErrores = true;
						break;
					}
				}
				
			}
		}
		$countMensajeome = count($ArrayMensajeome);
		if ($countMensajeome) {
			Log::channel('solosuper')->info('Desde ordenImport :: N = ' . $countMensajeome . ' filas sin campo obligatrio ' . '::' . implode(',', $ArrayMensajeome));
		}
		$counterrorArray = count($this->MensajeErrorArray);
		if ($counterrorArray) {
			Log::channel('solosuper')->info('Desde ordenImport :: N = ' . $counterrorArray . ' errores ::' . implode(',', $this->MensajeErrorArray));
		}
		
	}
	
	private function Validarvacios(mixed $row): string { //excesodeerrores
		
		$valideRequired = [
			$this->columnaIndice,
			$this->columnaIndice,
		];
		$mensaje = '';
		foreach ($valideRequired as $campo) {
			if (!isset($row[$campo]) || strcmp($row[$campo], '') === 0) {
				$mensaje = 'Campo ' . $campo . ' es obligatorio: ' . $row[$campo] . '. En la fila ' . $this->numeroFilas;
				Log::channel('solosuper')->info('Desde ordenImport ' . '::' . $mensaje);
				
				return $mensaje;
			}
		}
		
		return $mensaje;
	}
	
	public function TransformarNumeros(mixed &$row): void {
		
		$tiempoinicioCiclo = Carbon::now();
		file_put_contents(storage_path("logs/$this->nombredebugTXT.txt"), print_r('TransformarNumeros::' . $tiempoinicioCiclo, true), FILE_APPEND);
		$soloEsUnaFila = true;
		foreach ($this->hacerZeroSiSonNulos as $campo) {
			if (!is_numeric($row[$campo]) || trim($row[$campo]) == null) {
				$soloEsUnaFila = false;
				$imprimible = $row->toArray();
				$imprimible['campo_a_validar'] = $campo;
				$imprimible['validacion_nume'] = !!(!is_numeric($row[$campo]));
				$imprimible['validacion_null'] = !!(trim($row[$campo]) == null);
				file_put_contents(storage_path("logs/$this->nombredebugTXT.txt"), print_r($imprimible, true), FILE_APPEND);
				
				$row[$campo] = 0;
			}
		}
		
		if (!$soloEsUnaFila) {
			$this->nFilasSinPrecio ++;
		}
	}
	
	private function CrearYContar(mixed $row) {
		$codigoUnico = ($row[$this->columnaIndice]);
		Log::channel('solosuper')->info('existe la variable codigounico = ' . $codigoUnico);
		
		// -------------------------------------------
		// CAMPOS FECHA → convertir todas automáticamente
		// -------------------------------------------
		$fechasProcesadas = [];
		foreach ($this->camposFecha as $campoFecha) {
			
			$valorFecha = Carbon::createFromDate(1899, 12, 30)->addDays($row[$campoFecha]);
			Log::channel('solosuper')->info('fecha solicitud = ' . $valorFecha);
			
			$fechasProcesadas[$campoFecha] = $valorFecha;
		}
		$camposFinal = [];
		foreach ($this->camposMapeados as $modelo => $excel) {
			
			$camposFinal[$modelo] = $row[$excel];
		}
		$DatosDelorden = array_merge([$this->columnaIndice => $codigoUnico,], $fechasProcesadas, $camposFinal);
		Log::channel('solosuper')->info('Job en fase3: ' . implode(',', $DatosDelorden));
		
		
		// -------------------------------------------
		// GUARDAR / ACTUALIZAR
		// -------------------------------------------
		$DatosDelorden = collect($DatosDelorden)
		    ->mapWithKeys(fn ($value, $key) => [trim($key) => $value])
		    ->toArray();
		$orden = Ordenproduccion::updateOrCreate(
			[$this->columnaIndice => $codigoUnico],
			$DatosDelorden
		);
		Log::channel('solosuper')->info('Orden ID = ' . $orden->id);
		
		if ($orden->wasRecentlyCreated) {
			$this->nFilasNuevas++;
		}else {
			$this->nFilasActualizadas++;
		}
		
		Log::channel('solosuper')->info('finalizo la fila exitosamente');
		
		return $orden;
	}
	
	public function onError(Throwable $e) {
		$erroresThrow = $e->getMessage() . '::' . $e->getLine() . '::' . $e->getFile();
		$mensajeome = 'onerror de orden import ' . ' | Error en la importación: ' . $erroresThrow;
		Log::channel('solosuper')->info('Desde ordenImport ' . '::' . $mensajeome);
		//		dd('Error en la importación: ' . $e->getMessage());
	}
	
}

<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarAvisoProcesoJob;
use App\Models\Proceso;
use App\helpers\Myhelp;
use App\helpers\MyModels;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProcesoController extends Controller {
	
	public array $thisAtributos;
	public string $FromController = 'Proceso';
	
	//<editor-fold desc="Construc | filtro and dependencia">
	public function __construct() {
		$this->thisAtributos = (new Proceso())->getFillable(); //not using
	}
	
	public function ProcesosConsultados(Request $request): \Illuminate\Http\Response {
		$procesosVigilados = [
			"05266310500120220004300",
			"05360310500220240023300",
			"05360310500220240014100"
		];
		
		$pruebascontroller = new PruebasController();
		
		// Empieza el HTML
		$html = '<!doctype html><html><head><meta charset="utf-8"><title>Procesos consultados</title></head><body>';
		$html .= '<h2>Procesos consultados</h2>';
		$html .= '<table border="1" cellpadding="6" cellspacing="0">';
		$html .= '<thead><tr><th>Proceso</th><th>Numprocesos</th><th>PaginaNoResponde</th><th>Info EnviarCorreo</th></tr></thead><tbody>';
		
		foreach ($procesosVigilados as $procesos_vigilado) {
			$PaginaNoResponde = 0;
			$Numprocesos = 0;
			$ProcesoDB = null;
			
			$data = $pruebascontroller->GetJsonRama($procesos_vigilado);
			
			if ($data && isset($data['procesos'])) {
				$Numprocesos = count($data['procesos']);
				$ProcesoDB = Proceso::where('llave_proceso', $procesos_vigilado)->first();
				// si enviarCorreo devuelve texto, lo capturamos
				$info = $this->EnviarCorreo($ProcesoDB, 'Numprocesos', $Numprocesos, $procesos_vigilado, $PaginaNoResponde);
				// y/o realizar la búsqueda en API
				$pruebascontroller->buscarEnAPI($procesos_vigilado, $data);
			}
			else {
//				$PaginaNoResponde = 1;
				$info = 'No se encontraron procesos';
			}
			
			$html .= '<tr>';
			$html .= '<td>' . e($procesos_vigilado) . '</td>';
			$html .= '<td>' . e($Numprocesos) . '</td>';
//			$html .= '<td>' . ($PaginaNoResponde ? 'Sí' : 'No') . '</td>';
			$html .= '<td>' . e($info) . '</td>';
			$html .= '</tr>';
		}
		
		$html .= '</tbody></table></body></html>';
		
		
		return response($html, 200)->header('Content-Type', 'text/html');
	}
	
	/**
	 * @param \App\Models\Proceso|null $ProcesoDB
	 * @param $variablesAVigilar
	 * @param int $Numprocesos
	 * @return string
	 */
	private function EnviarCorreo(Proceso|null $ProcesoDB, $variablesAVigilar, int $Numprocesos, string $procesos_vigilado, int $PaginaNoResponde = 0): string {
		$traducirVariables = [
			'Numprocesos' => 'Numero de procesos',
		];
		if ($PaginaNoResponde) {
			$mensaje = 'El proceso ' . $procesos_vigilado . ' tiene problemas en consultaprocesos.ramajudicial.gov.co';
			dispatch(new EnviarAvisoProcesoJob($mensaje, "Sin respuesta $procesos_vigilado"));
			
			
			return $mensaje;
		}
		if (!$ProcesoDB) {
			$asunto = "<br>📌 Nuevo Proceso Detectado";
			$mensaje = "Es la primera vez que se consulta el proceso " . $procesos_vigilado;
			
			
			return $asunto . '  ' . $mensaje;
			//			dispatch(new EnviarAvisoProcesoJob($asunto , $mensaje));
		}
		else {
			if ($ProcesoDB->{$variablesAVigilar} != $Numprocesos) {
				$mensaje = "⚠️ Cambio Detectado en el proceso" . $procesos_vigilado;
				dispatch(new EnviarAvisoProcesoJob($mensaje, "Se registró un cambio en el proceso $procesos_vigilado  **{$traducirVariables[$variablesAVigilar]}**."));
				
				
				return $mensaje . '. Por favor, revise su correo';
			}
			else {
				$asunto = "✅ Sin Cambios";
				$mensaje = "El proceso $procesos_vigilado no ha tenido modificaciones desde la última consulta.";
				//				dd($asunto, $mensaje);
				dispatch(new EnviarAvisoProcesoJob($asunto, $mensaje));
				
				
				return $asunto . '  ' . $mensaje;
			}
		}
	}
	
	public function index(Request $request) {
		$numberPermissions = MyModels::getPermissionToNumber(Myhelp::EscribirEnLog($this, ' Procesos '));
		$Procesos = $this->Filtros($request)->get();
		//        $losSelect = $this->Dependencias();
		
		$perPage = $request->has('perPage') ? $request->perPage : 10;
		
		
		return Inertia::render($this->FromController . '/Index', [
			'fromController'    => $this->PerPageAndPaginate($request, $Procesos),
			'total'             => $Procesos->count(),
			'breadcrumbs'       => [
				[
					'label' => __('app.label.' . $this->FromController),
					'href'  => route($this->FromController . '.index')
				]
			],
			'title'             => __('app.label.' . $this->FromController),
			'filters'           => $request->all(['search', 'field', 'order']),
			'perPage'           => (int)$perPage,
			'numberPermissions' => $numberPermissions,
			'losSelect'         => $this->losSelect(Proceso::class, ' Proceso', 'proveeNombre'),
			'titulos'           => Proceso::getFillableWithTypes(),
		
		]);
	}
	
	public function Filtros($request): Builder {
		$Procesos = Proceso::query();
		if ($request->has('search')) {
			$Procesos = $Procesos->where(function ($query) use ($request) {
				$query->where('llave_proceso', 'LIKE', "%" . $request->search . "%")
					//                    ->orWhere('codigo', 'LIKE', "%" . $request->search . "%")
					//                    ->orWhere('identificacion', 'LIKE', "%" . $request->search . "%")
				;
			});
		}
		
		if ($request->has(['field', 'order'])) {
			$Procesos = $Procesos->orderBy($request->field, $request->order);
		}
		else {
			$Procesos = $Procesos->orderBy('updated_at', 'DESC');
		}
		
		
		return $Procesos;
	}
	
	//    public function Dependencias()
	//    {
	//        $no_nadasSelect = No_nada::all('id','nombre as name')->toArray();
	//        array_unshift($no_nadasSelect,["name"=>"Seleccione un no_nada",'id'=>0]);
	
	//        $ejemploSelec = CentroCosto::all('id', 'nombre as name')->toArray();
	//        array_unshift($ejemploSelec, ["name" => "Seleccione un ejemploSelec", 'id' => 0]);
	//        return [$no_nadasSelect];
	//        return [$no_nadasSelect,$ejemploSelec];
	//    }
	
	//</editor-fold>
	
	public function PerPageAndPaginate($request, $Procesos) {
		$perPage = $request->has('perPage') ? $request->perPage : 10;
		$page = request('page', 1); // Current page number
		$paginated = new LengthAwarePaginator($Procesos->forPage($page, $perPage), $Procesos->count(), $perPage, $page, ['path' => request()->url()]);
		
		
		return $paginated;
	}
	
	public function losSelect(string $modelClass, string $label, string $displayField = 'nombre'): array {
		// Verifica si la clase del modelo existe
		if (!class_exists($modelClass)) {
			return []; // O podrías lanzar una excepción más informativa
		}
		
		// Intenta obtener todos los registros del modelo
		$modelCollection = call_user_func([$modelClass, 'all']);
		
		// Verifica si el resultado es una colección
		if (!$modelCollection instanceof Collection) {
			return []; // O podrías lanzar una excepción
		}
		
		
		return [
			$label => Myhelp::NEW_turnInSelectID($modelCollection, $label . ' ', $displayField),
		];
	}
	
	//! STORE - UPDATE - DELETE
	//! STORE functions
	
	/**
	 * @throws \Throwable
	 */
	public function store(Request $request): RedirectResponse {
		$permissions = Myhelp::EscribirEnLog($this, ' Begin STORE:Procesos');
		DB::beginTransaction();
		//        $no_nada = $request->no_nada['id'];
		//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
		$Proceso = Proceso::create($request->all());
		
		DB::commit();
		Myhelp::EscribirEnLog($this, 'STORE:Procesos EXITOSO', 'Proceso id:' . $Proceso->id . ' | ' . $Proceso->nombre, false);
		
		
		return back()->with('success', __('app.label.created_successfully', ['name' => $Proceso->nombre]));
	}
	
	//fin store functions
	
	public function create() {}
	
	public function show($id) {}
	
	public function edit($id) {}
	
	public function update(Request $request, $id): RedirectResponse {
		$permissions = Myhelp::EscribirEnLog($this, ' Begin UPDATE:Procesos');
		DB::beginTransaction();
		$Proceso = Proceso::findOrFail($id);
		//        $request->merge(['no_nada_id' => $request->no_nada['id']]);
		$Proceso->update($request->all());
		
		DB::commit();
		Myhelp::EscribirEnLog($this, 'UPDATE:Procesos EXITOSO', 'Proceso id:' . $Proceso->id . ' | ' . $Proceso->nombre, false);
		
		
		return back()->with('success', __('app.label.updated_successfully2', ['nombre' => $Proceso->nombre]));
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	
	public function destroy($Procesoid) {
		$permissions = Myhelp::EscribirEnLog($this, 'DELETE:Procesos');
		$Proceso = Proceso::find($Procesoid);
		$elnombre = $Proceso->nombre;
		$Proceso->delete();
		Myhelp::EscribirEnLog($this, 'DELETE:Procesos', 'Proceso id:' . $Proceso->id . ' | ' . $Proceso->nombre . ' borrado', false);
		
		
		return back()->with('success', __('app.label.deleted_successfully', ['name' => $elnombre]));
	}
	
	//FIN : STORE - UPDATE - DELETE
	
	public function destroyBulk(Request $request) {
		$Proceso = Proceso::whereIn('id', $request->id);
		$Proceso->delete();
		
		
		return back()->with('success', __('app.label.deleted_successfully', ['name' => count($request->id) . ' ' . __('app.label.user')]));
	}
	
}

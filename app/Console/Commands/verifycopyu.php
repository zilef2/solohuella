<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class verifycopyu extends Command {
	
	use Constants;
	
	const MSJ_EXITO = ' fue realizada con exito ';
	const MSJ_FALLO = ' Fallo';
	public $generando;
	protected $signature = 'very:u';
	protected $description = 'Copia de la entidad generica';
	protected int $contadorMetodos;
	
	protected function generateAttributes(): array {
		// text // number // dinero // date // datetime // foreign
		return [
			'Codigo'                        => 'text',
			'Descripcion'                   => 'text',
		];
		
		/*
//            'valor_consig' => 'biginteger',
//            'texto' => 'text',
//            'fecha_legalizacion' => 'datetime',
//            'descripcion' => 'text',
//            'precio' => 'decimal',
		*/
	}
	
	protected function generateForeign(): array {
		return [
			'user_id' => 'user_id',
		];
	}
	public function handle(): int {
		try {
			$this->generando = self::getMessage('generando');
			
			$this->contadorMetodos = 0;
			$submetodo['Lenguaje'] = 0;
			
			$modelName = 'Ejemplosio';
			
			if ($this->MetodologiaInicial($modelName, 'generic', '')) {
				$this->info('MetodologiaInicial correcto ✅');
			}else{
				$this->warn('incorrecto 0');
				return 0;
			}
			
			if ($this->AddAttributesVue($modelName)) {
				$this->info('AddAttributesVue correcto ✅');
			}else{
				$this->warn('incorrecto 1');
				return 0;
			}
			
			if ($this->Paso2($modelName, $submetodo)) {
				$this->info('Paso2 correcto ✅');
			}else{
				$this->warn('incorrecto 2');
				return 0;
			}
			$this->info('FINISH');
			
			
			return 1;
		} catch (Exception $e) {
			$this->error("FALLO CONTADOR: " . $this->contadorMetodos . "FALLO Lenguaje: " . $submetodo['Lenguaje'] . " excepcion: " . $e->getMessage());
			
			
			return 0;
		}
		
	}
	
	/**
	 * @param mixed $modelName
	 * @param string $plantillaActual
	 * @param mixed $depende
	 * @return int
	 */
	public function MetodologiaInicial(mixed $modelName, string $plantillaActual, mixed $depende): int {
		$this->warn("Dentro de MetodologiaInicial, verifique manulamnete si WriteFillable y LanguageCopyU existe");
		
		if ($this->ValidatePages($plantillaActual, $modelName)) {
		$this->info('la funcion principal:MetodologiaInicial --> ValidatePages' . self::MSJ_EXITO);
			
			$this->info('ValidatePages correcto');
		}
		else {
			$this->warn('ValidatePages  fallo');
			
			
			return 0;
			
		}
		
		if ($this->MakeVuePages($plantillaActual, $modelName)) {
		$this->info('la funcion principal:MetodologiaInicial --> MakeVuePages' . self::MSJ_EXITO);
			
			$this->info('MakeVuePages correcto');
		}
		else {
			$this->warn('MakeVuePages  fallo');
			
			
			return 0;
			
		}
		
		if ($this->MakeControllerPages($plantillaActual, $modelName)) {
		$this->info('la funcion principal:MetodologiaInicial --> MakeControllerPages' . self::MSJ_EXITO);
			
			$this->info('MakeControllerPages correcto');
		}
		else {
			$this->warn('MakeControllerPages  fallo');
			
			
			return 0;
			
		}
		
		
		return 1;
	}
	
	private function ValidatePages($plantillaActual, $modelName): bool {
		$folderMayus = ucfirst($modelName);
		
		//validaciones del controlador
		$ObjetoEnMira = 'Controller.php';
		$RutaDelArchivo = 'app/Http/Controllers/';
		$controllerExiste = $this->ExisteOno($RutaDelArchivo, $plantillaActual, $ObjetoEnMira);
		
		
		return $controllerExiste;
	}
	
	private function ExisteOno($primeraParte, $plantillaActual, $ObjetoEnMira): bool {
		$sourcePath = base_path($primeraParte . $plantillaActual . $ObjetoEnMira);
		
		if (!File::exists($sourcePath)) {
			$this->error("El $ObjetoEnMira de origen '$sourcePath' no existe.");
			
			
			return false;
		}
		
		
		return true;
	}
	
	private function MakeVuePages($plantillaActual, $modelName): bool {
		$sourcePath = base_path('resources/js/Pages/' . $plantillaActual);
		$destinationPath = base_path("resources/js/Pages/$modelName");
		
		// Add this validation
		if (!File::exists($sourcePath)) {
			$this->error("La carpeta de origen '$plantillaActual' no existe.");
			
			
			return false;
		}
		if (File::exists($destinationPath)) {
			$this->warn("La carpeta de destino '$modelName' ya existe.");
			
			
			return false;
		}
		
		
		return true;
	}
	
	private function MakeControllerPages($plantillaActual, $modelName): bool {
		$folderMayus = ucfirst($modelName);
		$sourcePath = base_path('app/Http/Controllers/' . $plantillaActual . 'Controller.php');
		if (!File::exists($sourcePath)) {
			$this->error("El controlador de origen '$sourcePath' no existe.");
			
			
			return false;
		}
		
		$destinationPath = base_path("app/Http/Controllers/" . $folderMayus . "sController.php");
		
		if (File::exists($destinationPath)) {
			$this->warn("La carpeta de destino '$destinationPath' ya existe.");
			
			
			return false;
		}
		
		return true;
	}
	
	private function AddAttributesVue($modelName): int {
		$vueFilePath = resource_path("js/Pages/generic/Index.vue");
		
		if (!File::exists($vueFilePath)) {
			$this->error('El archivo js/Pages/generic/Index.vue no existe.');
			
			return 0;
		}
		
		return 1;
	}
	
	private function Paso2($modelName, &$submetodo): int {
		//estos metodos para abajo tienen validacion
		
		$this->warn('verifique que 	//aquipues este en web');
		
		if ($this->L2_LenguajeInsert($modelName, $submetodo) === 0) {
			return 0;
		}
		$this->info('la funcion principal:paso2 --> L2_LenguajeInsert ' . self::MSJ_EXITO);
		
		if ($this->DoSideBar($modelName)) {
			
			$this->info('DoSideBar ' . self::MSJ_EXITO);
			$this->contadorMetodos ++;
		}
		else {
			$this->error('DoSideBar ' . self::MSJ_FALLO);
			
			
			return 0;
		}
		$this->info('la funcion principal:paso2 --> DoSideBar ' . self::MSJ_EXITO);
		
		$this->contadorMetodos ++;
		
		return 1;
	}
	
	public function L2_LenguajeInsert($modelName, &$submetodo): int {
		if ($this->DoAppLenguaje($modelName)) {
			$submetodo['Lenguaje'] = 0;
			$this->info('DoAppLenguaje' . self::MSJ_EXITO);
			$this->contadorMetodos ++;
			
			foreach ($this->generateAttributes() as $key => $generateAttribute) {
				$this->DoAppLenguaje($key);
				$submetodo['Lenguaje'] ++;
			}
			foreach ($this->generateForeign() as $generateAttribute) {
				$this->DoAppLenguaje($generateAttribute, 'mochar_id');
				$submetodo['Lenguaje'] ++;
			}
			
			
			return 1;
		}
		else {
			$this->error('DoAppLenguaje ' . self::MSJ_FALLO);
			$this->error('$this->contadorMetodos = ' . $this->contadorMetodos);
			$this->error('$submetodo = ' . $submetodo['Lenguaje']);
			
			
			return 0;
		}
	}
	
	private function DoAppLenguaje($resource, $mochar = 'no'): int {
		$directory = 'lang/es/app.php';
		$files = glob($directory);
		
		if ($mochar == 'mochar_id') {
			$resource_Sin_Id = substr($resource, 0, - 3);
			$insertable = "'$resource' => '$resource_Sin_Id',\n\t\t//aquipues";
		}
		else {
			$insertable = "'$resource' => '$resource',\n\t\t//aquipues";
		}
		$pattern = '/\/\/aquipues/';
		$this->warn('verifique que ' . $insertable . ' tiene //aquipues en ' . count($files) . ' files');
		
		
		return 1;
		
	}
	
	
	private function DoSideBar($resource): int {
		$directory = 'resources/js/Components/SideBarMenu.vue';
		$files = glob($directory);
		
		$insertable = "'" . $resource . "',\n\t//aquipuesSide";
		$pattern = '/\/\/aquipuesSide/';
		
		$this->warn('verifique que ' . $insertable . ' tiene ' . $pattern . ' en ' . count($files) . ' archivo(s)');
		
		return 1;
	}
	
	
}

<?php

namespace App\Imports;

use App\helpers\HelpExcel;
use App\helpers\Myhelp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;

class PersonalImport implements ToModel {
	
	public int $CountFilas = 0;
	public int $contar1 = 0;
	public int $contar2 = 0;
	public int $contar3 = 0;
	public int $contarVacios = 0;
	public array $larow;
	
	/* valores del excel
			0 => "nombre"
		   1 => "cc"
		   2 => "cargo"
		   3 => "salario"
		   4 => "nacimiento"
		   5 => "dir"
		   6 => "cel"
	
	 valores del model
	  'name',
	'email',
	'password',
	'identificacion',
	'celular',
	'sexo',
	'fecha_nacimiento',
	'salario',
	'cargo',
	'area',
	 */
	
	public function model(array $row) {
		try {
			$this->larow = $row;
			
			//# validaciones
			if($row[0] === 'nombre') return null;
			
			if (!$this->Requeridos($row)) {
				$this->contarVacios++;
				Log::channel('solosuper')->info('vacio::'.implode(',',$row));
				
				return null;
			}
			if (!is_numeric($row[1])) {
				$this->contar3++;
				Log::channel('solosuper')->info('contar3::'.implode(',',$row));
				
				return null;
			}
			
			if (User::where('identificacion', $row[1])->exists()) {
				$this->contar1++;
				Log::channel('solosuper')->info('contar1::'.implode(',',$row));
				
				return null;
			}
			
			if (strtolower(trim($row[0])) === 'nombre' || strtolower(trim($row[0])) == '') { //saltar 1 fila
				$this->contar2++;
				Log::channel('solosuper')->info('contar2::'.implode(',',$row));
				return null;
			}
			//# fin validaciones
			
			$this->CountFilas++;
			
			/* valores del excel
			    0 => "nombre" 1 => "cc"
				2 => "cargo" 3 => "salario"
				4 => "nacimiento" 5 => "dir" 6 => "cel"
			 */
			
			$nombreSinEspacio = preg_replace('/\s+/', '', trim($row[0]));

			$user = new User([
                 'name'             => $row[0],
                 'email'            => $row[1],
                 'identificacion'   => $row[1],
                 'celular'          => $row[6],
                 'sexo'             => 'Masculino',
                 'fecha_nacimiento' => Carbon::parse($row[4]),
                 'salario'          => $row[3],
                 'cargo'            => $row[2],
                 'area'             => '',
                 
                 'password' => Hash::make($row[1] . '*'),
             ]);
			$user->assignRole('empleado');
			
			return $user;
		} catch (\Throwable $th) {
			$erormejor = $th->getMessage() . ' L:' . $th->getLine() . ' Ubi: ' . $th->getFile();
			Myhelp::EscribirEnLog($this, 'IMPORT:users', ' Fallo dentro de la importacion: ' .$erormejor , false);
		}
	}
	
	public function Requeridos($theRow) {
		//        foreach ($theRow as $value) {
		//            if (is_null($value) || $value === '')
		//                return false;
		//        }
		if (!is_string($theRow[0])) {
			return false;
		}
		if (!is_int(intval($theRow[1]))) {
			return false;
		}
		
		return true;
	}
}

<?php

namespace App\helpers;

class MyModels {
	
	public static function getPermissionToNumber($permissions): int {
		
		$arrayRoles = MyModels::CargosYModelos('soloNombresRoles');
		if(is_array($permissions)) $permissions = $permissions[0];//por alguna razon, cambio a ser un vector
		
		if (in_array($permissions, $arrayRoles)) {
			return array_search($permissions, $arrayRoles);
		}
		
		
		return 0;
		//		if ($permissions === 'trabajador') {
		//			return 1;
		//		}
		//		if ($permissions === 'supervisor') {
		//			return 2;
		//		}
		//		if ($permissions === 'admin') {
		//			return 9;
		//		}
		//		if ($permissions === 'superadmin') {
		//			return 10;
		//		}
		//		return 0;
	}
	
	public static function CargosYModelos($returning = '') {
		
		$nombresDeCargos = [
			1 => 'trabajador',
		];
		$nombresDeCargosSuper = [
			99  => 'admin',
			100 => 'superadmin',
		];
		if ($returning === 'soloNombresRoles') {
			
			return array_merge($nombresDeCargos, $nombresDeCargosSuper);
		}
		$Models = [
			'role',
			'permission',
			'user',
			'parametros',
			
			'elcore',//core
		
		];
		if ($returning === 'soloModels') {
			
			return $Models;
		}
		
		$isSome = [];
		foreach ($nombresDeCargos as $key => $value) {
			$isSome[] = 'is' . $value;
		}
		
		
		return [
			'nombresDeCargos' => $nombresDeCargos,
			'Models'          => $Models,
			'isSome'          => $isSome,
		];
	}
	
	public static function getPermissiToLog($permissions): string {
		
		if ($permissions === 'trabajador') {
			return 'single';
		}
		if ($permissions === 'supervisor') {
			return 'supervisor';
		}
		if ($permissions === 'admin') {
			return 'soloadmin';
		}
		if ($permissions === 'superadmin') {
			return 'solosuper';
		}
		
		
		return 'emergency';
	}
}

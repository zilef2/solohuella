<?php

namespace Database\Seeders;

use App\helpers\MyModels;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder {
	
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$AllRols = MyModels::CargosYModelos('soloNombresRoles');
		foreach ($AllRols as $index => $all_rol) {
			$elRol = Role::create(['name' => $all_rol]);
			$elRol->givePermissionTo(['is' . $all_rol,]);
		}
		
		$vectorCRUD = ['create', 'update', 'read', 'delete'];
        $vectorModelo = MyModels::CargosYModelos('soloModels');
		
		foreach ($AllRols as $theRol) {
			$Rol = Role::findByName($theRol);
			foreach ($vectorCRUD as $value) {
				foreach ($vectorModelo as $model) {
					$Rol->givePermissionTo([$value . ' ' . $model]);
				}
			}
		}
		
		
		//unicos para superadmin
		$this->UniquesForAdmins($AllRols);
		
		
		//one by one
//		$PersonlowRange = Role::findByName($AllRols[1]);
//		$EasyVisualWay = 'reporte';
//		$PersonlowRange->givePermissionTo([
//            'read '.$EasyVisualWay,
//            'create '.$EasyVisualWay,
//            'delete '.$EasyVisualWay,
//        ]);
		
	}
	
	private function UniquesForAdmins($allRols) {
		$super = Role::findByName('superadmin');
		$admin = Role::findByName('admin');
		unset($allRols[100]); //remove superadmin from the list
		foreach ($allRols as $index => $rol) {
			$super->givePermissionTo(['is'.$rol]);
		}
		unset($allRols[99]);
		foreach ($allRols as $index => $rol) {
			$admin->givePermissionTo(['is'.$rol]);
		}
	}
}

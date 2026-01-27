@echo off
set /p modelo="Introduce el nombre del modelo (ej. Proceso): "

echo Eliminando archivos para %modelo%...

:: Borrar Modelo
del "app\Models\%modelo%.php"

:: Borrar Controlador
del "app\Http\Controllers\%modelo%Controller.php"

:: Borrar Requests
rd /s /q "app\Http\Requests\%modelo%"

:: Borrar Factory
del "database\factories\%modelo%Factory.php"

:: Borrar Seeder
del "database\seeders\%modelo%Seeder.php"

:: Borrar Migracion (Busca por nombre parcial ya que tiene timestamp)
del "database\migrations\*_create_%modelo%s_table.php"

echo Proceso finalizado. Recuerda ejecutar 'composer dump-autoload'.
pause
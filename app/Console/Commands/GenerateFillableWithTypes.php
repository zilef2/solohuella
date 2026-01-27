<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateFillableWithTypes extends Command {
	
	protected $signature = 'generate:fillable';
	protected $description = 'Agrega la función getFillableWithTypes a un modelo especificado.';
	
	public function handle() {
		$modelName = $this->ask('¿Cómo se llama el modelo? (por ejemplo: Item)');
		//		$modelName = 'Item'; // Para pruebas, puedes cambiarlo a lo que necesites
		
		$modelPath = app_path('Models'); // Asumiendo estructura moderna (app/Models)
		$modelFiles = File::allFiles($modelPath);
		
		$matches = [];
		
		foreach ($modelFiles as $file) {
			if (Str::contains(strtolower($file->getFilename()), strtolower($modelName))) {
				$matches[] = $file;
			}
		}
		
		if (count($matches) === 0) {
			$this->error('❌ No se encontró ningún modelo que coincida.');
			
			
			return 0;
		}
		
		if (count($matches) > 1) {
			$this->error('⚠️ Hay más de un modelo que coincide. Estos fueron los resultados:' . implode(', ', $matches));
			
			foreach ($matches as $match) {
				$this->line($match->getFilename());
			}
			
			
			return 0;
		}
		
		$modelFile = $matches[0]->getPathname();
		$this->info('✅ Modelo encontrado: ' . $modelFile);
		
		$content = File::get($modelFile);
		
		if (Str::contains($content, 'function getFillableWithTypes')) {
			$this->error('❌ El modelo ya tiene la función getFillableWithTypes.');
			
			
			return 0;
		}
		
		// Función a insertar
		$functionCode = <<<EOT

    public static function getFillableWithTypes()
    {
        \$table = (new static)->getTable();
        \$columns = \\DB::select("SHOW COLUMNS FROM {\$table}");

        \$fillable = (new static)->getFillable();
        \$result = [];

        foreach (\$columns as \$column) {
            if (!in_array(\$column->Field, \$fillable)) {
                continue;
            }

            // Detectar tipo
            \$type = 'text'; // default
            if (str_contains(\$column->Type, 'int')) {
                \$type = 'integer';
            } elseif (str_contains(\$column->Type, 'decimal') || str_contains(\$column->Type, 'float')) {
                \$type = 'dinero';
            } elseif (str_contains(\$column->Type, 'foreign') || \$column->Field === 'oferta_id') {
                \$type = 'foreign';
            }

            \$result[] = [
                'order' => \$column->Field,
                'label' => \$column->Field,
                'type'  => \$type,
            ];
        }

        return \$result;
    }

EOT;
		
		// Insertar justo antes de la última llave de cierre de clase
		$newContent = preg_replace('/}\s*$/', $functionCode . "\n}", $content);
		
		File::put($modelFile, $newContent);
		
		$this->info('🎯 Función agregada exitosamente al modelo.');
		
		
		return 1;
	}
}

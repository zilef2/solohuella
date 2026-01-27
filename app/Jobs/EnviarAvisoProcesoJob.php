<?php

namespace App\Jobs;

use App\Mail\AvisoProcesoMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarAvisoProcesoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $titulo;
    protected string $mensaje;

    public function __construct($titulo, $mensaje)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
    }

    public function handle()
    {
		$destinos = [
			'ajelof2@gmail.com',
//			'rosanafelizzolaf@gmail.com',
		];
        Mail::to($destinos)->send(new AvisoProcesoMailable($this->titulo, $this->mensaje));
    }
}

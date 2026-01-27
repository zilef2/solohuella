<x-mail::message>
# {{ $titulo }}

{{ $mensaje }}

<x-mail::panel>
Este aviso fue generado automáticamente por el sistema de seguimiento de procesos.  
Por favor revise la información y tome las acciones necesarias.
</x-mail::panel>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>

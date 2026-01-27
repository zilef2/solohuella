<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'valor',
        'valor2',
        'valor_date',
        'valor_int',
        'valor_double',
        'valor_string1m',
    ];
}

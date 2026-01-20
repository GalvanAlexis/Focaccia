<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'orden',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean',
        'orden' => 'integer'
    ];

    public function scopeActivas($query)
    {
        return $query->where('activa', true)
                     ->orderBy('orden')
                     ->orderBy('nombre');
    }

    public static function getActivas()
    {
        return self::activas()->get();
    }
}

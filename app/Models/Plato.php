<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    protected $table = 'platos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'disponible',
        'imagen',
        'stock',
        'stock_ilimitado'
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'stock_ilimitado' => 'boolean',
        'precio' => 'decimal:2',
        'stock' => 'integer'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}

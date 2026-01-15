<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';

    protected $fillable = [
        'usuario_id',
        'plato_id',
        'cantidad',
        'total',
        'estado',
        'tipo_entrega',
        'direccion',
        'forma_pago',
        'notas',
        'created_at'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'cantidad' => 'integer'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }
}

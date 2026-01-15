<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    protected $table = 'caja_chica';

    protected $fillable = [
        'fecha',
        'hora',
        'concepto',
        'tipo',
        'monto',
        'es_digital',
        'user_id'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'es_digital' => 'boolean',
        'fecha' => 'date',
        'hora' => 'datetime:H:i:s'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getMovimientosPorFecha($fecha)
    {
        return self::where('fecha', $fecha)
                   ->orderBy('hora')
                   ->get();
    }

    public static function getSaldoDia($fecha)
    {
        $movimientos = self::getMovimientosPorFecha($fecha);

        $totalEntradas = 0;
        $totalSalidas = 0;
        $totalEfectivo = 0;
        $totalDigital = 0;

        foreach ($movimientos as $mov) {
            if ($mov->tipo === 'entrada') {
                $totalEntradas += $mov->monto;
                if ($mov->es_digital) {
                    $totalDigital += $mov->monto;
                } else {
                    $totalEfectivo += $mov->monto;
                }
            } else {
                $totalSalidas += $mov->monto;
            }
        }

        return [
            'entradas' => $totalEntradas,
            'salidas' => $totalSalidas,
            'saldo' => $totalEntradas - $totalSalidas,
            'efectivo' => $totalEfectivo,
            'digital' => $totalDigital,
        ];
    }
}

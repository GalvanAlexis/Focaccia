<?php

use App\Models\Pedido;
use App\Models\CajaChica;

$pedidos = Pedido::whereDate('created_at', now())
    ->where('estado', 'pendiente')
    ->take(4)
    ->get();

foreach ($pedidos as $p) {
    // Actualizar estado del pedido
    $p->update(['estado' => 'completado']);

    // Registrar el ingreso en Caja Chica
    CajaChica::create([
        'fecha' => date('Y-m-d'),
        'hora' => date('H:i:s'),
        'concepto' => "Pedido #{$p->id} - Venta Directa",
        'tipo' => 'entrada',
        'monto' => $p->total,
        'es_digital' => in_array($p->forma_pago, ['qr', 'mercado_pago', 'transferencia', 'tarjeta']) ? 1 : 0,
        'user_id' => null
    ]);
}

echo "Se completaron " . $pedidos->count() . " pedidos y se registraron en caja.";

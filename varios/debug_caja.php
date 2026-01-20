<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CajaChica;
use Carbon\Carbon;

$hoy = Carbon::now()->toDateString();
echo "Fecha de hoy: $hoy\n\n";

// Crear movimientos para hoy
echo "Creando movimientos para hoy...\n";

CajaChica::create([
    'fecha' => $hoy,
    'hora' => '09:00:00',
    'concepto' => 'Venta del día - Pizzas',
    'tipo' => 'entrada',
    'monto' => 28000,
    'es_digital' => false,
    'user_id' => 1,
]);

CajaChica::create([
    'fecha' => $hoy,
    'hora' => '11:30:00',
    'concepto' => 'Venta del día - Empanadas',
    'tipo' => 'entrada',
    'monto' => 15500,
    'es_digital' => true,
    'user_id' => 1,
]);

CajaChica::create([
    'fecha' => $hoy,
    'hora' => '13:00:00',
    'concepto' => 'Compra de ingredientes - Queso',
    'tipo' => 'salida',
    'monto' => 12000,
    'es_digital' => false,
    'user_id' => 1,
]);

CajaChica::create([
    'fecha' => $hoy,
    'hora' => '15:30:00',
    'concepto' => 'Pago de cliente - Delivery',
    'tipo' => 'entrada',
    'monto' => 22000,
    'es_digital' => true,
    'user_id' => 1,
]);

CajaChica::create([
    'fecha' => $hoy,
    'hora' => '17:00:00',
    'concepto' => 'Pago de servicios - Gas',
    'tipo' => 'salida',
    'monto' => 5000,
    'es_digital' => false,
    'user_id' => 1,
]);

echo "Movimientos creados!\n\n";

// Verificar con el método del modelo
$movimientos = CajaChica::getMovimientosPorFecha($hoy);
echo "Movimientos encontrados con getMovimientosPorFecha: " . $movimientos->count() . "\n\n";

foreach ($movimientos as $mov) {
    echo "{$mov->hora} - {$mov->concepto} - {$mov->tipo} - \${$mov->monto}\n";
}

// Calcular saldo
$saldo = CajaChica::getSaldoDia($hoy);
echo "\n=== RESUMEN ===\n";
echo "Entradas: \${$saldo['entradas']}\n";
echo "Salidas: \${$saldo['salidas']}\n";
echo "Saldo: \${$saldo['saldo']}\n";
echo "Efectivo: \${$saldo['efectivo']}\n";
echo "Digital: \${$saldo['digital']}\n";

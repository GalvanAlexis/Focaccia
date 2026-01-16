<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Pedido;
use App\Models\Plato;
use App\Models\CajaChica;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== LIMPIANDO DATOS ANTERIORES ===\n";
Pedido::truncate();
CajaChica::truncate();
echo "✓ Pedidos y Caja Chica limpiados\n\n";

// Obtener empanadas y bebidas
$empanadas = Plato::where('categoria', 'Empanadas')->get();
$bebidas = Plato::where('categoria', 'Bebidas')->get();

if ($empanadas->isEmpty() || $bebidas->isEmpty()) {
    die("Error: No hay empanadas o bebidas en el menú\n");
}

$formasPago = ['efectivo', 'transferencia', 'mercadopago'];
$direcciones = [
    'Av. Libertador 1234, Chascomús',
    'Calle Mitre 567, Chascomús',
    'Bolivia 123, Chascomús',
    'San Martín 890, Chascomús',
    'Rivadavia 456, Chascomús',
];

$hoy = Carbon::now();
$totalVentasHoy = 0;

echo "=== CREANDO 20 PEDIDOS PARA HOY ===\n";

for ($i = 1; $i <= 20; $i++) {
    // Cada persona pide entre 6 y 12 empanadas
    $cantidadEmpanadas = rand(6, 12);
    $empanada = $empanadas->random();

    // Y una bebida
    $bebida = $bebidas->random();

    $totalEmpanadas = $empanada->precio * $cantidadEmpanadas;
    $totalBebida = $bebida->precio;
    $totalPedido = $totalEmpanadas + $totalBebida;

    $formaPago = $formasPago[array_rand($formasPago)];
    $esDigital = in_array($formaPago, ['transferencia', 'mercadopago']);
    $tipoEntrega = rand(0, 1) ? 'delivery' : 'retiro';
    $estado = 'entregado'; // Todos entregados para que cuenten como ventas

    $horaPedido = Carbon::today()->addHours(rand(10, 21))->addMinutes(rand(0, 59));

    // Crear pedido de empanadas
    Pedido::create([
        'usuario_id' => 1,
        'plato_id' => $empanada->id,
        'cantidad' => $cantidadEmpanadas,
        'total' => $totalEmpanadas,
        'estado' => $estado,
        'tipo_entrega' => $tipoEntrega,
        'direccion' => $tipoEntrega === 'delivery' ? $direcciones[array_rand($direcciones)] : null,
        'forma_pago' => $formaPago,
        'notas' => null,
        'created_at' => $horaPedido,
        'updated_at' => $horaPedido,
    ]);

    // Crear pedido de bebida
    Pedido::create([
        'usuario_id' => 1,
        'plato_id' => $bebida->id,
        'cantidad' => 1,
        'total' => $totalBebida,
        'estado' => $estado,
        'tipo_entrega' => $tipoEntrega,
        'direccion' => $tipoEntrega === 'delivery' ? $direcciones[array_rand($direcciones)] : null,
        'forma_pago' => $formaPago,
        'notas' => null,
        'created_at' => $horaPedido,
        'updated_at' => $horaPedido,
    ]);

    // Registrar en caja chica
    CajaChica::create([
        'fecha' => $hoy->toDateString(),
        'hora' => $horaPedido->format('H:i:s'),
        'concepto' => "Venta #{$i} - {$cantidadEmpanadas} {$empanada->nombre} + {$bebida->nombre}",
        'tipo' => 'entrada',
        'monto' => $totalPedido,
        'es_digital' => $esDigital,
        'user_id' => 1,
        'created_at' => $horaPedido,
        'updated_at' => $horaPedido,
    ]);

    $totalVentasHoy += $totalPedido;

    echo "Pedido #{$i}: {$cantidadEmpanadas}x {$empanada->nombre} + {$bebida->nombre} = \$" . number_format($totalPedido, 0, ',', '.') . " ({$formaPago})\n";
}

echo "\n✓ 20 pedidos creados (40 items: 20 empanadas + 20 bebidas)\n";
echo "✓ 20 movimientos de caja registrados\n";
echo "Total ventas del día: \$" . number_format($totalVentasHoy, 0, ',', '.') . "\n\n";

// Agregar algunos gastos del día
echo "=== AGREGANDO GASTOS DEL DÍA ===\n";

$gastos = [
    ['concepto' => 'Compra de ingredientes - Harina y levadura', 'monto' => 15000],
    ['concepto' => 'Compra de ingredientes - Queso muzzarella', 'monto' => 22000],
    ['concepto' => 'Compra de bebidas - Reposición stock', 'monto' => 18000],
    ['concepto' => 'Pago delivery - Combustible', 'monto' => 5000],
];

foreach ($gastos as $gasto) {
    $horaGasto = Carbon::today()->addHours(rand(8, 18))->addMinutes(rand(0, 59));

    CajaChica::create([
        'fecha' => $hoy->toDateString(),
        'hora' => $horaGasto->format('H:i:s'),
        'concepto' => $gasto['concepto'],
        'tipo' => 'salida',
        'monto' => $gasto['monto'],
        'es_digital' => false,
        'user_id' => 1,
        'created_at' => $horaGasto,
        'updated_at' => $horaGasto,
    ]);

    echo "- {$gasto['concepto']}: \$" . number_format($gasto['monto'], 0, ',', '.') . "\n";
}

$totalGastos = array_sum(array_column($gastos, 'monto'));
echo "\n✓ 4 gastos registrados\n";
echo "Total gastos: \$" . number_format($totalGastos, 0, ',', '.') . "\n\n";

// Resumen final
$saldo = CajaChica::getSaldoDia($hoy->toDateString());

echo "=== RESUMEN FINAL ===\n";
echo "Pedidos creados: " . Pedido::count() . "\n";
echo "Movimientos de caja: " . CajaChica::count() . "\n";
echo "\nCaja del día:\n";
echo "  Entradas: \$" . number_format($saldo['entradas'], 0, ',', '.') . "\n";
echo "  Salidas: \$" . number_format($saldo['salidas'], 0, ',', '.') . "\n";
echo "  Saldo: \$" . number_format($saldo['saldo'], 0, ',', '.') . "\n";
echo "  Efectivo: \$" . number_format($saldo['efectivo'], 0, ',', '.') . "\n";
echo "  Digital: \$" . number_format($saldo['digital'], 0, ',', '.') . "\n";

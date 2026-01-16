<?php

namespace Database\Seeders;

use App\Models\CajaChica;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CajaChicaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@focaccia.com')->first();

        if (!$admin) {
            echo "Usuario admin no encontrado. Ejecuta RolesAndPermissionsSeeder primero.\n";
            return;
        }

        // Conceptos de entradas
        $conceptosEntrada = [
            'Venta del día - Pizzas',
            'Venta del día - Empanadas',
            'Pago de cliente - Delivery',
            'Venta mostrador',
            'Pago pendiente recibido',
            'Venta tartas',
            'Venta bebidas',
            'Pago transferencia cliente',
        ];

        // Conceptos de salidas
        $conceptosSalida = [
            'Compra de ingredientes - Harina',
            'Compra de ingredientes - Queso',
            'Compra de ingredientes - Tomates',
            'Pago de servicios - Luz',
            'Pago de servicios - Gas',
            'Sueldo empleado',
            'Mantenimiento horno',
            'Compra de packaging',
            'Delivery - Combustible',
            'Limpieza y productos',
        ];

        // Crear 30 movimientos de caja
        for ($i = 0; $i < 30; $i++) {
            $tipo = $i % 3 === 0 ? 'salida' : 'entrada'; // Más entradas que salidas
            $esDigital = rand(0, 1) === 1;

            if ($tipo === 'entrada') {
                $concepto = $conceptosEntrada[array_rand($conceptosEntrada)];
                $monto = rand(5000, 35000); // Ventas entre 5k y 35k
            } else {
                $concepto = $conceptosSalida[array_rand($conceptosSalida)];
                $monto = rand(3000, 25000); // Gastos entre 3k y 25k
            }

            // Fechas de los últimos 10 días
            $fecha = Carbon::now()->subDays(rand(0, 10));
            $hora = Carbon::createFromTime(rand(8, 22), rand(0, 59), 0);

            CajaChica::create([
                'fecha' => $fecha->toDateString(),
                'hora' => $hora->toTimeString(),
                'concepto' => $concepto,
                'tipo' => $tipo,
                'monto' => $monto,
                'es_digital' => $esDigital,
                'user_id' => $admin->id,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
        }

        echo "30 movimientos de caja creados exitosamente.\n";
    }
}

<?php

namespace Database\Seeders;

use App\Models\Pedido;
use App\Models\User;
use App\Models\Plato;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PedidosSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener el admin user y algunos platos
        $admin = User::where('email', 'admin@focaccia.com')->first();
        $platos = Plato::all();

        if ($platos->isEmpty()) {
            echo "No hay platos disponibles. Ejecuta MenuSeeder primero.\n";
            return;
        }

        $estados = ['pendiente', 'confirmado', 'en_preparacion', 'entregado', 'cancelado'];
        $tiposEntrega = ['retiro', 'delivery'];
        $formasPago = ['efectivo', 'transferencia', 'mercadopago'];
        $direcciones = [
            'Av. Libertador 1234, Chascomús',
            'Calle Mitre 567, Chascomús',
            'Bolivia 123, Chascomús',
            'San Martín 890, Chascomús',
            'Rivadavia 456, Chascomús',
        ];

        // Crear 20 pedidos de ejemplo
        for ($i = 0; $i < 20; $i++) {
            $plato = $platos->random();
            $cantidad = rand(1, 4);
            $total = $plato->precio * $cantidad;
            $estado = $estados[array_rand($estados)];
            $tipoEntrega = $tiposEntrega[array_rand($tiposEntrega)];
            $formaPago = $formasPago[array_rand($formasPago)];

            // Fechas de los últimos 7 días
            $fecha = Carbon::now()->subDays(rand(0, 7));

            Pedido::create([
                'usuario_id' => $admin->id,
                'plato_id' => $plato->id,
                'cantidad' => $cantidad,
                'total' => $total,
                'estado' => $estado,
                'tipo_entrega' => $tipoEntrega,
                'direccion' => $tipoEntrega === 'delivery' ? $direcciones[array_rand($direcciones)] : null,
                'forma_pago' => $formaPago,
                'notas' => $i % 3 === 0 ? 'Sin cebolla por favor' : null,
                'created_at' => $fecha,
                'updated_at' => $fecha,
            ]);
        }

        echo "20 pedidos creados exitosamente.\n";
    }
}

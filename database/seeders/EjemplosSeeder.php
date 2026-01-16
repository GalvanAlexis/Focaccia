<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pedido;
use App\Models\CajaChica;
use Carbon\Carbon;

class EjemplosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Eliminar datos existentes
        Pedido::query()->delete();
        CajaChica::query()->delete();

        echo "Creando pedidos de ejemplo...\n";

        // Primero crear usuarios de ejemplo
        $usuarios = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@email.com',
                'password' => bcrypt('password123'),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5)
            ],
            [
                'name' => 'María García',
                'email' => 'maria.garcia@email.com',
                'password' => bcrypt('password123'),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4)
            ],
            [
                'name' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@email.com',
                'password' => bcrypt('password123'),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3)
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@email.com',
                'password' => bcrypt('password123'),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'name' => 'Lucas Fernández',
                'email' => 'lucas.fernandez@email.com',
                'password' => bcrypt('password123'),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ]
        ];

        foreach ($usuarios as $usuario) {
            $userModel = new \App\Models\User();
            $userModel->name = $usuario['name'];
            $userModel->email = $usuario['email'];
            $userModel->password = $usuario['password'];
            $userModel->created_at = $usuario['created_at'];
            $userModel->updated_at = $usuario['updated_at'];
            $userModel->save();
            
            // Asignar rol de cliente
            $userModel->assignRole('cliente');
            echo "✅ Usuario creado: {$usuario['name']} ({$usuario['email']})\n";
        }

        // Obtener IDs de usuarios creados
        $userIds = \App\Models\User::whereIn('email', ['juan.perez@email.com', 'maria.garcia@email.com', 'carlos.rodriguez@email.com', 'ana.martinez@email.com', 'lucas.fernandez@email.com'])->pluck('id')->toArray();

        // Crear pedidos de ejemplo
        $pedidos = [
            [
                'usuario_id' => $userIds[0], // Juan Pérez
                'plato_id' => 1,
                'cantidad' => 1,
                'total' => 8500,
                'estado' => 'completado',
                'tipo_entrega' => 'delivery',
                'direccion' => 'Bolivia 55, Chascomús',
                'forma_pago' => 'efectivo',
                'notas' => 'Sin cebolla',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ],
            [
                'usuario_id' => $userIds[1], // María García
                'plato_id' => 2,
                'cantidad' => 6,
                'total' => 7200,
                'estado' => 'completado',
                'tipo_entrega' => 'retiro',
                'forma_pago' => 'qr',
                'notas' => 'Llevarlas calientes',
                'created_at' => Carbon::now()->subDays(1)->subHours(3),
                'updated_at' => Carbon::now()->subDays(1)->subHours(3)
            ],
            [
                'usuario_id' => $userIds[2], // Carlos Rodríguez
                'plato_id' => 3,
                'cantidad' => 1,
                'total' => 6800,
                'estado' => 'confirmado',
                'tipo_entrega' => 'delivery',
                'direccion' => 'Av. Italia 234',
                'forma_pago' => 'tarjeta',
                'notas' => 'Extra albahaca',
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(2)
            ],
            [
                'usuario_id' => $userIds[3], // Ana Martínez
                'plato_id' => 4,
                'cantidad' => 2,
                'total' => 9000,
                'estado' => 'en_camino',
                'tipo_entrega' => 'delivery',
                'direccion' => 'Pje. Las Flores 123',
                'forma_pago' => 'efectivo',
                'notas' => 'Retiro de vuelto con $1000',
                'created_at' => Carbon::now()->subHours(3),
                'updated_at' => Carbon::now()->subHours(1)
            ],
            [
                'usuario_id' => $userIds[4], // Lucas Fernández
                'plato_id' => 5,
                'cantidad' => 1,
                'total' => 7500,
                'estado' => 'pendiente',
                'tipo_entrega' => 'delivery',
                'direccion' => 'Calle Principal 456',
                'forma_pago' => 'transferencia',
                'notas' => 'Cliente habitual',
                'created_at' => Carbon::now()->subMinutes(30),
                'updated_at' => Carbon::now()->subMinutes(30)
            ]
        ];

        foreach ($pedidos as $index => $pedido) {
            $pedidoModel = Pedido::create($pedido);
            echo "✅ Pedido creado: Pedido #" . ($index + 1) . " - {$pedido['estado']}\n";
            
            // Actualizar stock de platos
            $plato = \App\Models\Plato::find($pedido['plato_id']);
            if ($plato) {
                $plato->stock = max(0, $plato->stock - $pedido['cantidad']);
                $plato->save();
                echo "   📦 Stock actualizado: {$plato->nombre} → {$plato->stock} unidades\n";
            }
        }

        echo "\nCreando movimientos de caja chica...\n";

        // Crear movimientos de caja chica
        echo "\nCreando movimientos de caja chica...\n";

        // Obtener ID del admin para movimientos
        $adminId = \App\Models\User::where('email', 'admin@focaccia.com')->value('id');

        $movimientos = [
            [
                'fecha' => Carbon::now()->subDays(2)->toDateString(),
                'hora' => '09:00:00',
                'concepto' => 'Apertura de caja - Efectivo',
                'tipo' => 'entrada',
                'monto' => 100000,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subDays(2)->startOfDay()->setTime(9, 0, 0),
                'updated_at' => Carbon::now()->subDays(2)->startOfDay()->setTime(9, 0, 0)
            ],
            [
                'fecha' => Carbon::now()->subDays(2)->toDateString(),
                'hora' => '10:30:00',
                'concepto' => 'Ingreso por venta - Pedido #1',
                'tipo' => 'entrada',
                'monto' => 50000,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subDays(2)->setTime(10, 30, 0),
                'updated_at' => Carbon::now()->subDays(2)->setTime(10, 30, 0)
            ],
            [
                'fecha' => Carbon::now()->subDays(2)->toDateString(),
                'hora' => '14:15:00',
                'concepto' => 'Compra proveedor - Insumos',
                'tipo' => 'salida',
                'monto' => 20000,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subDays(2)->setTime(14, 15, 0),
                'updated_at' => Carbon::now()->subDays(2)->setTime(14, 15, 0)
            ],
            [
                'fecha' => Carbon::now()->subDays(1)->toDateString(),
                'hora' => '11:00:00',
                'concepto' => 'Ingreso por venta - Pedido #2',
                'tipo' => 'entrada',
                'monto' => 7200,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subDays(1)->setTime(11, 0, 0),
                'updated_at' => Carbon::now()->subDays(1)->setTime(11, 0, 0)
            ],
            [
                'fecha' => Carbon::now()->subDays(1)->toDateString(),
                'hora' => '13:30:00',
                'concepto' => 'Pago QR - Cliente',
                'tipo' => 'entrada',
                'monto' => 9000,
                'es_digital' => true,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subDays(1)->setTime(13, 30, 0),
                'updated_at' => Carbon::now()->subDays(1)->setTime(13, 30, 0)
            ],
            [
                'fecha' => Carbon::now()->subHours(3)->toDateString(),
                'hora' => '10:00:00',
                'concepto' => 'Costo delivery - Envíos',
                'tipo' => 'salida',
                'monto' => 5000,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subHours(3)->setTime(10, 0, 0),
                'updated_at' => Carbon::now()->subHours(3)->setTime(10, 0, 0)
            ],
            [
                'fecha' => Carbon::now()->subHours(2)->toDateString(),
                'hora' => '12:00:00',
                'concepto' => 'Ingreso por venta - Pedido #3',
                'tipo' => 'entrada',
                'monto' => 4500,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subHours(2)->setTime(12, 0, 0),
                'updated_at' => Carbon::now()->subHours(2)->setTime(12, 0, 0)
            ],
            [
                'fecha' => Carbon::now()->subHours(1)->toDateString(),
                'hora' => '16:00:00',
                'concepto' => 'Ventas del día acumuladas',
                'tipo' => 'entrada',
                'monto' => 75000,
                'es_digital' => false,
                'user_id' => $adminId,
                'created_at' => Carbon::now()->subHours(1)->setTime(16, 0, 0),
                'updated_at' => Carbon::now()->subHours(1)->setTime(16, 0, 0)
            ]
        ];

        foreach ($movimientos as $movimiento) {
            CajaChica::create($movimiento);
            $tipoIcon = $movimiento['tipo'] === 'entrada' ? '💰' : '💸';
            $metodoPago = $movimiento['es_digital'] ? 'Digital' : 'Efectivo';
            echo "{$tipoIcon} Movimiento creado: {$movimiento['concepto']} - {$movimiento['monto']} - {$metodoPago}\n";
        }

        echo "\n=== 📊 DATOS DE PRUEBA CREADOS ===\n";
        echo "📋 Pedidos: " . count($pedidos) . "\n";
        echo "💰 Movimientos de caja: " . count($movimientos) . "\n";
        
        // Calcular balance de caja chica
        $totalEntradas = array_sum(array_column(array_filter($movimientos, fn($m) => $m['tipo'] === 'entrada'), 'monto'));
        $totalSalidas = array_sum(array_column(array_filter($movimientos, fn($m) => $m['tipo'] === 'salida'), 'monto'));
        $balance = $totalEntradas - $totalSalidas;
        
        echo "💵 Balance total: $" . number_format($balance, 0, ',', '.') . "\n";
        
        echo "\n🎯 Estados de pedidos:\n";
        foreach ($pedidos as $index => $pedido) {
            $plato = \App\Models\Plato::find($pedido['plato_id']);
            echo "  • Pedido #" . ($index + 1) . " - {$plato->nombre} - {$pedido['estado']}\n";
        }
        
        echo "\n💡 Para probar:\n";
        echo "1. Inicia sesión como admin: admin@focaccia.com / admin123\n";
        echo "2. Ve a Pedidos para ver los estados diferentes\n";
        echo "3. Ve a Caja Chica para ver los movimientos\n";
        echo "4. Prueba cambiar estados en los pedidos\n";
    }
}
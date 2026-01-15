<?php

namespace App\Http\Controllers;

use App\Models\Plato;
use App\Models\Pedido;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CarritoController extends Controller
{
    public function index()
    {
        $carrito = session('carrito', []);
        
        // Calcular total
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        return view('carrito.index', compact('carrito', 'total'));
    }

    public function agregar(Request $request)
    {
        try {
            $plato_id = $request->input('plato_id');
            $cantidad = (int) $request->input('cantidad');

            if (!$plato_id || !$cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ]);
            }

            $plato = Plato::find($plato_id);

            if (!$plato) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plato no encontrado'
                ]);
            }

            // Verificar disponibilidad
            if (!$plato->disponible) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este plato no está disponible actualmente'
                ]);
            }

            $carrito = session('carrito', []);

            // Calcular cantidad total que tendría en el carrito
            $cantidadActual = isset($carrito[$plato_id]) ? $carrito[$plato_id]['cantidad'] : 0;
            $cantidadTotal = $cantidadActual + $cantidad;

            // Verificar stock (solo si no es ilimitado)
            if ($plato->stock_ilimitado == 0) {
                if ($plato->stock <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este plato está agotado'
                    ]);
                }

                if ($cantidadTotal > $plato->stock) {
                    $disponible = $plato->stock - $cantidadActual;
                    return response()->json([
                        'success' => false,
                        'message' => "Solo puedes agregar {$disponible} unidad(es) más. Stock disponible: {$plato->stock}"
                    ]);
                }
            }

            if (isset($carrito[$plato_id])) {
                $carrito[$plato_id]['cantidad'] = $cantidadTotal;
            } else {
                $carrito[$plato_id] = [
                    'nombre' => $plato->nombre,
                    'precio' => $plato->precio,
                    'cantidad' => $cantidad
                ];
            }

            session(['carrito' => $carrito]);

            $cart_count = array_sum(array_column($carrito, 'cantidad'));

            return response()->json([
                'success' => true,
                'message' => 'Plato agregado al carrito',
                'cart_count' => $cart_count
            ]);
        } catch (\Exception $e) {
            Log::error('Error en CarritoController::agregar - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar al carrito: ' . $e->getMessage()
            ]);
        }
    }

    public function actualizar(Request $request)
    {
        try {
            $plato_id = $request->input('plato_id');
            $cantidad = (int) $request->input('cantidad');

            if ($cantidad < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cantidad debe ser al menos 1'
                ]);
            }

            $carrito = session('carrito', []);

            if (!isset($carrito[$plato_id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plato no encontrado en el carrito'
                ]);
            }

            // Verificar stock del plato
            $plato = Plato::find($plato_id);

            if (!$plato) {
                return response()->json([
                    'success' => false,
                    'message' => 'Plato no encontrado'
                ]);
            }

            // Verificar stock (solo si no es ilimitado)
            if ($plato->stock_ilimitado == 0) {
                if ($cantidad > $plato->stock) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente. Disponible: {$plato->stock} unidad(es)"
                    ]);
                }
            }

            $carrito[$plato_id]['cantidad'] = $cantidad;
            session(['carrito' => $carrito]);

            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en CarritoController::actualizar - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar cantidad: ' . $e->getMessage()
            ]);
        }
    }

    public function eliminar(Request $request)
    {
        try {
            $plato_id = $request->input('plato_id');
            $carrito = session('carrito', []);

            // Intentar eliminación directa
            if (isset($carrito[$plato_id])) {
                unset($carrito[$plato_id]);
                session(['carrito' => $carrito]);
                return response()->json([
                    'success' => true,
                    'message' => 'Plato eliminado del carrito'
                ]);
            }

            // Intentar búsqueda segura por tipos (string vs int)
            foreach ($carrito as $id => $item) {
                if ((string)$id === (string)$plato_id) {
                    unset($carrito[$id]);
                    session(['carrito' => $carrito]);
                    return response()->json([
                        'success' => true,
                        'message' => 'Plato eliminado del carrito'
                    ]);
                }
            }

            Log::warning("Intento de eliminar plato ID {$plato_id} fallido. IDs en carrito: " . implode(',', array_keys($carrito)));

            return response()->json([
                'success' => false,
                'message' => 'Plato no encontrado en el carrito'
            ]);
        } catch (\Exception $e) {
            Log::error('Error en CarritoController::eliminar - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar producto: ' . $e->getMessage()
            ]);
        }
    }

    public function vaciar()
    {
        session()->forget('carrito');
        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado');
    }

    public function finalizar(Request $request)
    {
        $isAjax = $request->ajax();

        $carrito = session('carrito', []);

        if (empty($carrito)) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'El carrito está vacío'
                ]);
            }
            return redirect()->route('carrito.index')->with('error', 'El carrito está vacío');
        }

        // Los pedidos públicos no tienen usuario asociado
        $usuarioId = null;

        $nombre_cliente = $request->input('nombre_cliente');
        $tipo_entrega = $request->input('tipo_entrega');
        $direccion = $request->input('direccion');
        $forma_pago = $request->input('forma_pago');

        // Validar datos requeridos
        if (empty($nombre_cliente) || empty($tipo_entrega) || empty($forma_pago)) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Todos los campos son obligatorios'
                ]);
            }
            return redirect()->route('carrito.index')->with('error', 'Todos los campos son obligatorios');
        }

        // Validar tipo de entrega
        if (!in_array($tipo_entrega, ['retiro', 'delivery'])) {
            return redirect()->route('carrito.index')->with('error', 'Tipo de entrega inválido');
        }

        // Validar forma de pago
        if (!in_array($forma_pago, ['efectivo', 'qr', 'mercado_pago', 'transferencia', 'tarjeta'])) {
            return redirect()->route('carrito.index')->with('error', 'Forma de pago inválida');
        }

        // Calcular total del carrito
        $total = 0;
        foreach ($carrito as $item) {
            $subtotal = $item['precio'] * $item['cantidad'];
            $total += $subtotal;
        }

        // Construir notas del pedido
        $notas = "A nombre de: {$nombre_cliente}\n";
        $notas .= "Tipo de entrega: {$tipo_entrega}\n";
        if ($tipo_entrega === 'delivery' && !empty($direccion)) {
            $notas .= "Direccion: {$direccion}\n";
        }
        $notas .= "Forma de pago: {$forma_pago}\n";

        // Crear un pedido por cada plato en el carrito
        $primer_pedido_id = null;

        foreach ($carrito as $plato_id => $item) {
            $subtotal = $item['precio'] * $item['cantidad'];

            $pedido = Pedido::create([
                'usuario_id' => null,
                'plato_id' => $plato_id,
                'cantidad' => $item['cantidad'],
                'total' => $subtotal,
                'estado' => 'pendiente',
                'tipo_entrega' => $tipo_entrega,
                'direccion' => $direccion,
                'forma_pago' => $forma_pago,
                'notas' => $notas
            ]);

            if (!$primer_pedido_id) {
                $primer_pedido_id = $pedido->id;
            }

            // Descontar stock y marcar como no disponible si se agota
            $plato = Plato::find($plato_id);

            if ($plato && $plato->stock_ilimitado == 0) {
                $nuevoStock = $plato->stock - $item['cantidad'];

                // Si el stock llega a 0 o menos, marcar como no disponible
                $updateData = ['stock' => max(0, $nuevoStock)];

                if ($nuevoStock <= 0) {
                    $updateData['disponible'] = 0;
                }

                $plato->update($updateData);

                // Limpiar caché de platos cuando cambia el stock
                Cache::forget('platos_disponibles');
            }
        }

        $pedido_id = $primer_pedido_id;

        // Limpiar carrito de la sesión
        session()->forget('carrito');

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $forma_pago === 'qr') {
            return response()->json([
                'success' => true,
                'pedido_id' => $pedido_id,
                'message' => 'Pedido realizado exitosamente'
            ]);
        }

        return redirect()->route('pedido.index')->with('success', 'Pedido realizado exitosamente');
    }

    public function getCount()
    {
        $carrito = session('carrito', []);
        $cart_count = array_sum(array_column($carrito, 'cantidad'));

        return response()->json(['cart_count' => $cart_count]);
    }
}

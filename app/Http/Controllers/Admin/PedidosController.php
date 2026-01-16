<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Plato;
use App\Models\CajaChica;
use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PedidosController extends Controller
{
    public function index(Request $request)
    {
        // Query param para búsqueda y fecha
        $busqueda = $request->input('busqueda');
        $fecha = $request->input('fecha');

        // Construir query base
        $query = DB::table('pedidos as p')
            ->select(
                'p.*',
                'u.name as username',
                'u.email',
                'pl.nombre as plato_nombre',
                'pl.precio',
                'pl.stock',
                'pl.stock_ilimitado'
            )
            ->leftJoin('users as u', 'u.id', '=', 'p.usuario_id')
            ->leftJoin('platos as pl', 'pl.id', '=', 'p.plato_id')
            ->orderBy('p.id', 'DESC');

        // Lógica de filtrado
        if (!empty($busqueda)) {
            // Si hay búsqueda, buscar en toda la DB
            $query->where(function ($q) use ($busqueda) {
                $q->where('p.id', 'like', "%{$busqueda}%")
                    ->orWhere('p.notas', 'like', "%{$busqueda}%") // Busca en nombre cliente, direccion, etc
                    ->orWhere('u.name', 'like', "%{$busqueda}%")
                    ->orWhere('pl.nombre', 'like', "%{$busqueda}%");
            });
        } elseif (!empty($fecha)) {
            // Si hay fecha, filtrar por esa fecha
            $query->whereDate('p.created_at', $fecha);
        } else {
            // Si NO hay búsqueda ni fecha, mostrar solo pedidos de HOY
            $query->whereDate('p.created_at', now()->toDateString());
        }

        $pedidos = $query->get();

        // Procesar las notas para extraer información
        foreach ($pedidos as $pedido) {
            $pedido->info_pedido = $this->extraerInfoPedido($pedido->notas);
        }

        return view('admin.pedidos.index', compact('pedidos', 'fecha'));
    }

    public function ver($id)
    {
        $pedido = DB::table('pedidos as p')
            ->select(
                'p.*',
                'u.name as username',
                'u.email',
                'pl.nombre as plato_nombre',
                'pl.precio'
            )
            ->leftJoin('users as u', 'u.id', '=', 'p.usuario_id')
            ->leftJoin('platos as pl', 'pl.id', '=', 'p.plato_id')
            ->where('p.id', $id)
            ->first();

        if (!$pedido) {
            return redirect()->route('admin.pedidos.index')->with('error', 'Pedido no encontrado');
        }

        $pedido->info_pedido = $this->extraerInfoPedido($pedido->notas);

        return view('admin.pedidos.ver', compact('pedido'));
    }

    public function editar($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return redirect()->route('admin.pedidos.index')->with('error', 'Pedido no encontrado');
        }

        if (request()->isMethod('post')) {
            $estado = request()->input('estado');

            $pedido->update(['estado' => $estado]);

            return redirect()->route('admin.pedidos.index')->with('success', 'Estado actualizado correctamente');
        }

        $pedidoData = (object)[
            'id' => $pedido->id,
            'estado' => $pedido->estado,
            'notas' => $pedido->notas,
            'info_pedido' => $this->extraerInfoPedido($pedido->notas)
        ];

        return view('admin.pedidos.editar', ['pedido' => $pedidoData]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $nuevoEstado = $request->input('estado');

        // Obtener el pedido actual
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ]);
        }

        $estadoAnterior = $pedido->estado;

        // 1. BLOQUEAR CAMBIOS SI YA ESTÁ CANCELADO
        if ($estadoAnterior === 'cancelado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede modificar un pedido cancelado'
            ]);
        }

        $motivo = $request->input('motivo');

        // 2. VALIDAR MOTIVO SI SE CANCELA
        if ($nuevoEstado === 'cancelado' && empty($motivo)) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar un motivo para cancelar el pedido'
            ]);
        }

        // Actualizar el estado del pedido
        $pedido->update(['estado' => $nuevoEstado]);

        // SI EL NUEVO ESTADO ES "COMPLETADO", DESCONTAR DEL STOCK
        if ($nuevoEstado === 'completado' && $estadoAnterior !== 'completado') {
            $this->descontarStock($pedido->plato_id, $pedido->cantidad);
        }

        // SI SE CANCELA UN PEDIDO QUE ESTABA COMPLETADO, DEVOLVER AL STOCK
        if ($nuevoEstado === 'cancelado' && $estadoAnterior === 'completado') {
            $this->devolverStock($pedido->plato_id, $pedido->cantidad);
        }

        // REGISTRAR EN CAJA CHICA cuando el estado cambia a "completado" o "cancelado"
        if ($nuevoEstado === 'completado' && $estadoAnterior !== 'completado') {
            $this->registrarEnCajaChica($pedido, 'entrada');
        } elseif ($nuevoEstado === 'cancelado') {
            // SIEMPRE que se cancela (venga de donde venga) se registra la salida o la nota
            // Pero la lógica del usuario dice: "descuenta si se cancelo" (asumiendo que hubo entrada previa o es perdida)
            // Si estaba completado => hubo entrada => corresponde Salida (Devolución)
            // Si estaba pendiente => no hubo entrada => no hay devolución de dinero, PERO el usuario quiere registrar "porque se cancelo".
            // Para mantener la consistencia contable: solo "Salida" si hubo "Entrada" (estado completado).
            // Si nunca se pagó (pendiente), no debería haber movimiento en caja, solo registro de stock (hecho arriba) o log.

            // Re-leendo requerimiento: "descuenta si se cancelo[aca tiene que poner porque se cancelo el pedido]"
            // Asumiré que se refiere a la devolución de dinero si ya estaba cobrado (completado).

            if ($estadoAnterior === 'completado') {
                $this->registrarEnCajaChica($pedido, 'salida', $motivo);
            }
        }

        // Crear notificación para el usuario del pedido
        $mensajesEstado = [
            'pendiente' => 'Tu pedido está pendiente de confirmación',
            'confirmado' => 'Tu pedido ha sido confirmado y está siendo preparado',
            'en_camino' => 'Tu pedido está en camino',
            'completado' => '¡Tu pedido ha sido completado!',
            'cancelado' => 'Tu pedido ha sido cancelado'
        ];

        $iconosEstado = [
            'pendiente' => 'bi-clock-fill',
            'confirmado' => 'bi-check-circle-fill',
            'en_camino' => 'bi-truck',
            'completado' => 'bi-check-circle-fill',
            'cancelado' => 'bi-x-circle-fill'
        ];

        // Solo crear notificación si hay un usuario_id válido (pedidos no públicos)
        if (isset($mensajesEstado[$nuevoEstado]) && !empty($pedido->usuario_id)) {
            try {
                Notificacion::crearNotificacion([
                    'user_id' => $pedido->usuario_id,
                    'tipo' => 'cambio_estado_pedido',
                    'titulo' => 'Actualización de Pedido #' . $id,
                    'mensaje' => $mensajesEstado[$nuevoEstado],
                    'icono' => $iconosEstado[$nuevoEstado] ?? 'bi-info-circle',
                    'url' => route('pedido.index'),
                    'leida' => 0,
                    'data' => json_encode([
                        'pedido_id' => $id,
                        'estado_anterior' => $estadoAnterior,
                        'estado_nuevo' => $nuevoEstado
                    ])
                ]);
            } catch (\Exception $e) {
                Log::error('Error al crear notificación para pedido #' . $id . ': ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function eliminar($id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ]);
        }

        $pedido->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pedido eliminado correctamente'
        ]);
    }

    public function actualizarItem(Request $request)
    {
        $itemId = $request->input('item_id');
        $cantidad = (int) $request->input('cantidad');

        if (!$itemId || $cantidad < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);
        }

        // Obtener el pedido actual
        $pedido = Pedido::find($itemId);

        if (!$pedido) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ]);
        }

        // Si la cantidad es 0, eliminar el pedido
        if ($cantidad === 0) {
            $pedido->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item eliminado',
                'subtotal' => 0,
                'cantidad' => 0
            ]);
        }

        // Obtener información del plato para verificar stock
        $plato = Plato::find($pedido->plato_id);

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

        // Calcular nuevo subtotal
        $nuevoTotal = $plato->precio * $cantidad;

        // Actualizar pedido
        $pedido->update([
            'cantidad' => $cantidad,
            'total' => $nuevoTotal
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cantidad actualizada',
            'subtotal' => $nuevoTotal,
            'cantidad' => $cantidad
        ]);
    }

    public function agregarPlato(Request $request)
    {
        $pedidoKey = $request->input('pedido_key');
        $platoId = $request->input('plato_id');
        $cantidad = (int) $request->input('cantidad');

        if (!$pedidoKey || !$platoId || $cantidad < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
        }

        // Obtener información del plato
        $plato = Plato::find($platoId);

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

        // Obtener un pedido existente del mismo grupo para copiar datos
        $referenceId = $request->input('reference_id');
        $pedidoExistente = null;

        if ($referenceId) {
            $pedidoExistente = Pedido::find($referenceId);
        }

        if (!$pedidoExistente) {
            // Fallback: búsqueda por key (legacy)
            $keyParts = explode('_', $pedidoKey, 2); // Limitar a 2 partes para no romper la fecha
            $nombreCliente = $keyParts[0];
            $fechaPedido = isset($keyParts[1]) ? $keyParts[1] : null;

            // Buscar un pedido existente de este cliente/grupo con la misma fecha
            $query = Pedido::where('notas', 'like', "%A nombre de: {$nombreCliente}%")
                ->orderBy('id', 'DESC');

            // Si tenemos la fecha, filtrar también por fecha para mayor precisión
            if ($fechaPedido) {
                $fechaFormateada = date('Y-m-d H:i', strtotime($fechaPedido));
                $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') = ?", [$fechaFormateada]);
            }

            $pedidoExistente = $query->first();

            if (!$pedidoExistente) {
                // Si no encontramos con fecha exacta, buscar solo por nombre
                $pedidoExistente = Pedido::where('notas', 'like', "%A nombre de: {$nombreCliente}%")
                    ->orderBy('id', 'DESC')
                    ->first();
            }
        }

        if (!$pedidoExistente) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo encontrar el pedido original'
            ]);
        }

        // Asegurar que tenemos el nombre del cliente para la búsqueda de duplicados
        if (!isset($nombreCliente)) {
            $info = $this->extraerInfoPedido($pedidoExistente->notas);
            $nombreCliente = $info['nombre_cliente'] ?? '';
        }

        // Verificar si ya existe este plato en el mismo pedido (mismo cliente, fecha y plato)
        $platoYaExiste = Pedido::where('plato_id', $platoId)
            ->where('notas', 'like', "%A nombre de: {$nombreCliente}%")
            ->where('created_at', $pedidoExistente->created_at)
            ->first();

        if ($platoYaExiste) {
            // Si ya existe, actualizar la cantidad en lugar de crear un nuevo registro
            $nuevaCantidad = $platoYaExiste->cantidad + $cantidad;
            $nuevoTotal = $plato->precio * $nuevaCantidad;

            $platoYaExiste->update([
                'cantidad' => $nuevaCantidad,
                'total' => $nuevoTotal
            ]);
        } else {
            // Crear nuevo registro en pedidos con los mismos datos del grupo
            $subtotal = $plato->precio * $cantidad;

            Pedido::create([
                'usuario_id' => $pedidoExistente->usuario_id,
                'plato_id' => $platoId,
                'cantidad' => $cantidad,
                'total' => $subtotal,
                'estado' => $pedidoExistente->estado,
                'tipo_entrega' => $pedidoExistente->tipo_entrega,
                'direccion' => $pedidoExistente->direccion,
                'forma_pago' => $pedidoExistente->forma_pago,
                'notas' => $pedidoExistente->notas,
                'created_at' => $pedidoExistente->created_at // Usar la misma fecha del grupo
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Plato agregado al pedido'
        ]);
    }

    public function imprimirTicket($id)
    {
        $pedido = DB::table('pedidos as p')
            ->select(
                'p.*',
                'u.name as username',
                'u.email',
                'pl.nombre as plato_nombre',
                'pl.precio'
            )
            ->leftJoin('users as u', 'u.id', '=', 'p.usuario_id')
            ->leftJoin('platos as pl', 'pl.id', '=', 'p.plato_id')
            ->where('p.id', $id)
            ->first();

        if (!$pedido) {
            return redirect()->route('admin.pedidos.index')->with('error', 'Pedido no encontrado');
        }

        $pedido->info_pedido = $this->extraerInfoPedido($pedido->notas);

        return view('admin.pedidos.ticket', compact('pedido'));
    }

    /**
     * DESCONTAR STOCK CUANDO UN PEDIDO SE COMPLETA
     */
    private function descontarStock($platoId, $cantidad)
    {
        // Obtener información del plato
        $plato = Plato::find($platoId);

        if (!$plato) {
            Log::error("Plato ID {$platoId} no encontrado para descontar stock");
            return false;
        }

        // Si el plato tiene stock ilimitado, no hacer nada
        if ($plato->stock_ilimitado == 1) {
            return true;
        }

        // Descontar del stock
        $nuevoStock = max(0, $plato->stock - $cantidad);

        $plato->update(['stock' => $nuevoStock]);

        // Limpiar caché de platos cuando cambia el stock
        Cache::forget('platos_disponibles');

        Log::info("Stock descontado: Plato #{$platoId} - Cantidad: {$cantidad} - Stock restante: {$nuevoStock}");

        return true;
    }

    /**
     * DEVOLVER STOCK CUANDO UN PEDIDO COMPLETADO SE CANCELA
     */
    private function devolverStock($platoId, $cantidad)
    {
        // Obtener información del plato
        $plato = Plato::find($platoId);

        if (!$plato) {
            Log::error("Plato ID {$platoId} no encontrado para devolver stock");
            return false;
        }

        // Si el plato tiene stock ilimitado, no hacer nada
        if ($plato->stock_ilimitado == 1) {
            return true;
        }

        // Devolver al stock
        $nuevoStock = $plato->stock + $cantidad;

        $plato->update(['stock' => $nuevoStock]);

        // Limpiar caché de platos cuando cambia el stock
        Cache::forget('platos_disponibles');

        Log::info("Stock devuelto: Plato #{$platoId} - Cantidad: {$cantidad} - Stock actual: {$nuevoStock}");

        return true;
    }

    /**
     * REGISTRAR MOVIMIENTO EN CAJA CHICA
     */
    private function registrarEnCajaChica($pedido, $tipo, $motivo = null)
    {
        try {
            // Extraer información del pedido
            $info = $this->extraerInfoPedido($pedido->notas);
            $nombreCliente = $info['nombre_cliente'] ?? 'Cliente';
            $formaPago = strtolower($info['forma_pago'] ?? 'efectivo');

            // Determinar si es digital o efectivo
            $esDigital = in_array($formaPago, ['qr', 'mercado_pago', 'mercadopago', 'tarjeta']) ? 1 : 0;

            // Preparar datos para caja chica
            $concepto = $tipo === 'entrada'
                ? "Pedido #{$pedido->id} - {$nombreCliente}"
                : "Devolución Pedido #{$pedido->id} - {$nombreCliente}" . ($motivo ? " (Motivo: $motivo)" : "");

            CajaChica::create([
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s'),
                'concepto' => $concepto,
                'tipo' => $tipo,
                'monto' => $pedido->total,
                'es_digital' => $esDigital,
                'user_id' => auth()->id()
            ]);

            Log::info("Movimiento en caja chica registrado: Pedido #{$pedido->id} - Tipo: {$tipo} - Monto: {$pedido->total}");

            return true;
        } catch (\Exception $e) {
            Log::error("Error al registrar en caja chica: " . $e->getMessage());
            return false;
        }
    }

    private function extraerInfoPedido($notas)
    {
        $info = [
            'nombre_cliente' => '',
            'tipo_entrega' => '',
            'direccion' => '',
            'forma_pago' => '',
            'detalle' => ''
        ];

        if (empty($notas)) {
            return $info;
        }

        // Extraer "A nombre de"
        if (preg_match('/A nombre de:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['nombre_cliente'] = trim($matches[1]);
        }

        // Extraer "Tipo de entrega"
        if (preg_match('/Tipo de entrega:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['tipo_entrega'] = trim($matches[1]);
        }

        // Extraer "Dirección"
        if (preg_match('/Direccion:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['direccion'] = trim($matches[1]);
        }

        // Extraer "Forma de pago"
        if (preg_match('/Forma de pago:\s*(.+?)[\n\r]/i', $notas, $matches)) {
            $info['forma_pago'] = trim($matches[1]);
        }

        // Extraer detalle del pedido
        if (preg_match('/Detalle del pedido:\s*(.+)/is', $notas, $matches)) {
            $info['detalle'] = trim($matches[1]);
        }

        return $info;
    }
}

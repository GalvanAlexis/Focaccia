<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CajaChica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CajaChicaController extends Controller
{
    public function index()
    {
        $fechaHoy = date('Y-m-d');
        return $this->ver($fechaHoy);
    }

    public function ver($fecha = null)
    {
        $fecha = $fecha ?? date('Y-m-d');

        // Obtener movimientos del día
        $movimientos = CajaChica::getMovimientosPorFecha($fecha);

        // Calcular totales
        $saldo = CajaChica::getSaldoDia($fecha);

        $data = [
            'fecha' => $fecha,
            'movimientos' => $movimientos,
            'entradas' => $saldo['entradas'],
            'salidas' => $saldo['salidas'],
            'saldo' => $saldo['saldo'],
            'efectivo' => $saldo['efectivo'],
            'digital' => $saldo['digital'],
            'esHoy' => ($fecha === date('Y-m-d')),
        ];

        return view('admin.caja_chica.index', $data);
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'concepto' => 'required|min:3|max:255',
            'tipo' => 'required|in:entrada,salida',
            'monto' => 'required|numeric|min:0.01'
        ]);

        CajaChica::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'concepto' => $request->concepto,
            'tipo' => $request->tipo,
            'monto' => $request->monto,
            'es_digital' => $request->has('es_digital') ? 1 : 0,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.caja-chica.ver', $request->fecha)
                        ->with('success', 'Movimiento agregado correctamente');
    }

    public function editar(Request $request, $id)
    {
        $movimiento = CajaChica::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'fecha' => 'required|date',
                'hora' => 'required',
                'concepto' => 'required|min:3|max:255',
                'tipo' => 'required|in:entrada,salida',
                'monto' => 'required|numeric|min:0.01'
            ]);

            $movimiento->update([
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'concepto' => $request->concepto,
                'tipo' => $request->tipo,
                'monto' => $request->monto,
                'es_digital' => $request->has('es_digital') ? 1 : 0,
            ]);

            return redirect()->route('admin.caja-chica.ver', $request->fecha)
                            ->with('success', 'Movimiento actualizado correctamente');
        }

        return view('admin.caja_chica.editar', compact('movimiento'));
    }

    public function eliminar($id)
    {
        $movimiento = CajaChica::findOrFail($id);
        $fecha = $movimiento->fecha;
        $movimiento->delete();

        return redirect()->route('admin.caja-chica.ver', $fecha)
                        ->with('success', 'Movimiento eliminado correctamente');
    }

    public function archivo()
    {
        // Obtener fechas con movimientos (últimos 30 días)
        $fechas = CajaChica::select('fecha')
                          ->selectRaw('COUNT(*) as cantidad')
                          ->groupBy('fecha')
                          ->orderBy('fecha', 'DESC')
                          ->limit(30)
                          ->get();

        return view('admin.caja_chica.archivo', compact('fechas'));
    }

    public function imprimir($fecha)
    {
        $movimientos = CajaChica::getMovimientosPorFecha($fecha);
        $saldo = CajaChica::getSaldoDia($fecha);

        $data = [
            'fecha' => $fecha,
            'movimientos' => $movimientos,
            'entradas' => $saldo['entradas'],
            'salidas' => $saldo['salidas'],
            'saldo' => $saldo['saldo'],
            'efectivo' => $saldo['efectivo'],
            'digital' => $saldo['digital'],
        ];

        return view('admin.caja_chica.imprimir', $data);
    }
}

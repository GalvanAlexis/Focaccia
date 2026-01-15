<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plato;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $platos = Plato::orderBy('categoria', 'ASC')
                      ->orderBy('nombre', 'ASC')
                      ->get();

        $categorias = Categoria::getActivas();

        return view('admin.menu.index', compact('platos', 'categorias'));
    }

    public function crear()
    {
        $categorias = Categoria::getActivas();
        return view('admin.menu.crear', compact('categorias'));
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:3|max:255',
            'precio' => 'required|numeric',
            'imagen' => 'required|image',
            'stock' => 'required|integer|min:0',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => (int) $request->input('stock', 99),
            'stock_ilimitado' => 0, // Removed feature
            'disponible' => $request->has('disponible') ? 1 : 0,
        ];

        // Manejo de imagen
        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $imagen = $request->file('imagen');
            $nombreImagen = bin2hex(random_bytes(8)) . '_' . time() . '.' . $imagen->getClientOriginalExtension();

            // Guardar en public/assets/images/platos
            $imagen->move(public_path('assets/images/platos'), $nombreImagen);
            $data['imagen'] = $nombreImagen;
        }

        Plato::create($data);

        // Limpiar caché de platos
        Cache::forget('platos_disponibles');

        return redirect()->route('admin.menu.index')->with('success', 'Plato agregado correctamente');
    }

    public function editar($id)
    {
        $plato = Plato::findOrFail($id);
        $categorias = Categoria::getActivas();

        return view('admin.menu.editar', compact('plato', 'categorias'));
    }

    public function actualizar(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|min:3|max:255',
            'precio' => 'required|numeric',
            'imagen' => 'nullable|image',
            'stock' => 'required|integer|min:0',
        ]);

        $plato = Plato::findOrFail($id);

        $data = [
            'nombre' => $request->nombre,
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => (int) $request->input('stock', 99),
            'stock_ilimitado' => 0, // Removed feature
            'disponible' => $request->has('disponible') ? 1 : 0,
        ];

        // Manejo de imagen
        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            // Eliminar imagen anterior si existe
            if ($plato->imagen) {
                $oldImagePath = public_path('assets/images/platos/' . $plato->imagen);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
            }

            $imagen = $request->file('imagen');
            $nombreImagen = bin2hex(random_bytes(8)) . '_' . time() . '.' . $imagen->getClientOriginalExtension();

            $imagen->move(public_path('assets/images/platos'), $nombreImagen);
            $data['imagen'] = $nombreImagen;
        }

        $plato->update($data);

        // Limpiar caché de platos
        Cache::forget('platos_disponibles');

        return redirect()->route('admin.menu.index')->with('success', 'Plato actualizado correctamente');
    }

    public function eliminar($id)
    {
        $plato = Plato::findOrFail($id);

        // Eliminar imagen física si existe
        if ($plato->imagen) {
            $imagePath = public_path('assets/images/platos/' . $plato->imagen);
            if (file_exists($imagePath)) {
                @unlink($imagePath);
            }
        }

        $plato->delete();

        // Limpiar caché de platos
        Cache::forget('platos_disponibles');

        return redirect()->route('admin.menu.index')->with('success', 'Plato eliminado correctamente');
    }

    public function obtenerPlatos()
    {
        $platos = Plato::select('id', 'nombre', 'precio', 'categoria', 'disponible', 'stock', 'stock_ilimitado')
                      ->where('disponible', true)
                      ->orderBy('categoria', 'ASC')
                      ->orderBy('nombre', 'ASC')
                      ->get();

        return response()->json([
            'success' => true,
            'platos' => $platos
        ]);
    }
}

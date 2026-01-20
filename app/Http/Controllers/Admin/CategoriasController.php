<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('orden', 'ASC')
                              ->orderBy('nombre', 'ASC')
                              ->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'nombre' => 'required|min:2|max:100|unique:categorias,nombre',
            'orden' => 'nullable|integer|min:0'
        ]);

        Categoria::create([
            'nombre' => $request->nombre,
            'orden' => $request->orden ?? 0,
            'activa' => $request->has('activa') ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada correctamente'
        ]);
    }

    public function actualizar(Request $request, $id)
    {
        $rules = [];
        if ($request->has('nombre')) {
            $rules['nombre'] = 'required|min:2|max:100|unique:categorias,nombre,' . $id;
        }
        if ($request->has('orden')) {
            $rules['orden'] = 'nullable|integer|min:0';
        }
        $request->validate($rules);

        $categoria = Categoria::findOrFail($id);

        $data = [];
        if ($request->has('nombre')) $data['nombre'] = $request->nombre;
        if ($request->has('orden')) $data['orden'] = $request->orden;
        if ($request->has('activa')) $data['activa'] = $request->activa;

        $categoria->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada correctamente'
        ]);
    }

    public function eliminar($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente'
        ]);
    }

    public function obtenerTodas()
    {
        $categorias = Categoria::orderBy('orden', 'ASC')
                              ->orderBy('nombre', 'ASC')
                              ->get();

        return response()->json([
            'success' => true,
            'categorias' => $categorias
        ]);
    }
}

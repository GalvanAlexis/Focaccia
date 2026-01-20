<?php

namespace App\Http\Controllers;

use App\Models\Plato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Intentar obtener platos del caché (caché de 5 minutos)
        $platos = Cache::remember('platos_disponibles', 300, function () {
            return Plato::where('disponible', true)
                ->where(function ($query) {
                    $query->where('stock_ilimitado', true)
                        ->orWhere('stock', '>', 0);
                })
                ->orderBy('categoria', 'ASC')
                ->orderBy('nombre', 'ASC')
                ->get();
        });

        // Pasar el carrito de la sesión para restaurarlo
        $carrito = session('carrito', []);

        return view('home', compact('platos', 'carrito'));
    }
}

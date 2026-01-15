<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Las categorías ya existen, solo crear platos
        // $categorias = [
        //     ['nombre' => 'Bebidas', 'orden' => 1, 'activa' => true],
        //     ['nombre' => 'Empanadas', 'orden' => 2, 'activa' => true],
        //     ['nombre' => 'Pizzas', 'orden' => 3, 'activa' => true],
        //     ['nombre' => 'Tartas', 'orden' => 4, 'activa' => true],
        //     ['nombre' => 'Postres', 'orden' => 5, 'activa' => true],
        // ];
        //
        // foreach ($categorias as $cat) {
        //     Categoria::create($cat);
        // }

        // Crear platos de ejemplo
        $platos = [
            // Bebidas
            ['nombre' => 'Coca Cola 1.5L', 'descripcion' => 'Gaseosa Coca Cola 1.5 litros', 'precio' => 1500, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 50, 'stock_ilimitado' => false],
            ['nombre' => 'Agua Mineral', 'descripcion' => 'Agua mineral sin gas 500ml', 'precio' => 800, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 30, 'stock_ilimitado' => false],
            
            // Empanadas
            ['nombre' => 'Empanada de Carne', 'descripcion' => 'Empanada criolla de carne cortada a cuchillo', 'precio' => 600, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
            ['nombre' => 'Empanada de Jamón y Queso', 'descripcion' => 'Empanada de jamón y queso', 'precio' => 550, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
            ['nombre' => 'Empanada de Pollo', 'descripcion' => 'Empanada de pollo con verduras', 'precio' => 580, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
            
            // Pizzas
            ['nombre' => 'Pizza Muzzarella', 'descripcion' => 'Pizza muzzarella grande (8 porciones)', 'precio' => 4500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
            ['nombre' => 'Pizza Napolitana', 'descripcion' => 'Pizza napolitana con tomate, ajo y albahaca', 'precio' => 5000, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
            ['nombre' => 'Pizza Especial', 'descripcion' => 'Pizza con jamón, morrón, aceitunas y huevo', 'precio' => 5500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
            
            // Tartas
            ['nombre' => 'Tarta de Verdura', 'descripcion' => 'Tarta casera de verdura y queso', 'precio' => 3500, 'categoria' => 'Tartas', 'disponible' => true, 'stock' => 5, 'stock_ilimitado' => false],
            ['nombre' => 'Tarta de Jamón y Queso', 'descripcion' => 'Tarta casera de jamón y queso', 'precio' => 3800, 'categoria' => 'Tartas', 'disponible' => true, 'stock' => 5, 'stock_ilimitado' => false],
            
            // Postres
            ['nombre' => 'Flan Casero', 'descripcion' => 'Flan casero con dulce de leche y crema', 'precio' => 1200, 'categoria' => 'Postres', 'disponible' => true, 'stock' => 10, 'stock_ilimitado' => false],
            ['nombre' => 'Helado', 'descripcion' => 'Helado artesanal 1/4 kg', 'precio' => 2000, 'categoria' => 'Postres', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true],
        ];

        foreach ($platos as $plato) {
            Plato::create($plato);
        }
    }
}

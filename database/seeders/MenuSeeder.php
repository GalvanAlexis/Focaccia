<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Plato;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $platos = [
            // Empanadas (Precios entre 12.000 y 22.000)
            ['nombre' => 'Empanada Criolla', 'descripcion' => 'Unidad tradicional de carne', 'precio' => 1208.33, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'empanada_carne.png'],
            ['nombre' => 'Empanada Capresse', 'descripcion' => 'Unidad de tomate, queso y albahaca', 'precio' => 1208.33, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'caprese empa.avif'],
            ['nombre' => 'Empanada Pollo', 'descripcion' => 'Unidad de pollo con verdeo', 'precio' => 1208.33, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'empanada pollo.jpg'],
            ['nombre' => 'Empanada Jamón y Queso', 'descripcion' => 'Unidad clásica', 'precio' => 1208.33, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'empanada_jyq.jpg'],
            ['nombre' => 'Empanada Humita', 'descripcion' => 'Unidad de choclo y queso', 'precio' => 1208.33, 'categoria' => 'Empanadas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'empanada_humita.jpg'],

            // Pizzas (Precios entre 12.000 y 22.000)
            ['nombre' => 'Pizza Muzzarella', 'descripcion' => 'Doble muzzarella, aceitunas y orégano', 'precio' => 12500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_muzzarella.png'],
            ['nombre' => 'Pizza Italiana', 'descripcion' => 'Salsa, muzzarella y condimento italiano', 'precio' => 13800, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_italiana.png'],
            ['nombre' => 'Pizza Especial', 'descripcion' => 'Jamón, morrón y aceitunas', 'precio' => 15500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza-especial-jamon.jpg'],
            ['nombre' => 'Pizza Provolone', 'descripcion' => 'Muzzarella y queso provolone gratinado', 'precio' => 16500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_muzzarella.png'],
            ['nombre' => 'Pizza Napolitana', 'descripcion' => 'Tomate natural, ajo y albahaca', 'precio' => 14200, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza tomate y albaca.webp'],
            ['nombre' => 'Pizza Ajillo', 'descripcion' => 'Salsa roja y mucho ajo frito', 'precio' => 12800, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_cat.png'],
            ['nombre' => 'Pizza Calabresa', 'descripcion' => 'Muzzarella y longaniza calabresa', 'precio' => 17200, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_italiana.png'],
            ['nombre' => 'Pizza de Verdura', 'descripcion' => 'Acelga fresca y salsa blanca', 'precio' => 16000, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_verdura.png'],
            ['nombre' => 'Pizza 4 Quesos', 'descripcion' => 'El mejor mix de quesos premium', 'precio' => 18500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_verdura.png'],
            ['nombre' => 'Pizza Fugazzeta', 'descripcion' => 'Cebolla blanca y muzzarella', 'precio' => 13500, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'fugazza-argentina_web.jpg.webp'],
            ['nombre' => 'Pizza Focaccia', 'descripcion' => 'Especialidad de la casa con masa focaccia', 'precio' => 22000, 'categoria' => 'Pizzas', 'disponible' => true, 'stock' => 0, 'stock_ilimitado' => true, 'imagen' => 'pizza_cat.png'],

            // Tartas (Precios entre 12.000 y 22.000)
            ['nombre' => 'Tarta de Verdura XL', 'descripcion' => 'Gigante de acelga, huevo y queso', 'precio' => 12500, 'categoria' => 'Tartas', 'disponible' => true, 'stock' => 10, 'stock_ilimitado' => false, 'imagen' => 'tarta_verdura.png'],
            ['nombre' => 'Tarta Capresse XL', 'descripcion' => 'Tomate cherry, muzzarella y albahaca', 'precio' => 13800, 'categoria' => 'Tartas', 'disponible' => true, 'stock' => 10, 'stock_ilimitado' => false, 'imagen' => 'tarta_caprese.png'],
            ['nombre' => 'Tarta Completa', 'descripcion' => 'Jamón, queso, huevo y vegetales', 'precio' => 15500, 'categoria' => 'Tartas', 'disponible' => true, 'stock' => 10, 'stock_ilimitado' => false, 'imagen' => 'tarta_completa.png'],

            // Bebidas (Precios de mercado)
            ['nombre' => 'Coca Cola 1.5L', 'descripcion' => 'Gaseosa original fría', 'precio' => 3800, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 100, 'stock_ilimitado' => false, 'imagen' => 'cocacola.png'],
            ['nombre' => 'Sprite 1.5L', 'descripcion' => 'Lima limón súper refrescante', 'precio' => 3600, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 100, 'stock_ilimitado' => false, 'imagen' => 'Gaseosa-Sprite-2-25-Lt-1-1183.webp'],
            ['nombre' => 'Seven Up 1.5L', 'descripcion' => 'Sabor a lima-limón clásico', 'precio' => 3600, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 100, 'stock_ilimitado' => false, 'imagen' => 'bebidas_cat.png'],
            ['nombre' => 'Cerveza Imperial 473ml', 'descripcion' => 'Lata bien helada', 'precio' => 3000, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 200, 'stock_ilimitado' => false, 'imagen' => 'Cerveza-Golden-Imperial-Lata-473.jpg'],
            ['nombre' => 'Agua Mineral Villavicencio 500ml', 'descripcion' => 'Sin gas, directo de manantial', 'precio' => 1800, 'categoria' => 'Bebidas', 'disponible' => true, 'stock' => 50, 'stock_ilimitado' => false, 'imagen' => 'bebidas_cat.png'],
        ];

        foreach ($platos as $plato) {
            Plato::create($plato);
        }
    }
}

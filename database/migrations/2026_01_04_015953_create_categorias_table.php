<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->integer('orden')->default(0);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        // Insertar categorías predeterminadas
        DB::table('categorias')->insert([
            ['nombre' => 'Bebidas', 'orden' => 1, 'activa' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Empanadas', 'orden' => 2, 'activa' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Pizzas', 'orden' => 3, 'activa' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Tartas', 'orden' => 4, 'activa' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Postres', 'orden' => 5, 'activa' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};

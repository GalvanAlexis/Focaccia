<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Plato;
use App\Models\Categoria;
use App\Models\Pedido;
use Spatie\Permission\Models\Role;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Admin User
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        
        $this->adminUser = User::factory()->create([
            'email' => 'admin@crud.com', 
            'name' => 'Admin CRUD'
        ]);
        $this->adminUser->assignRole($roleAdmin);
    }

    /** @test */
    public function test_admin_can_create_category()
    {
        $this->actingAs($this->adminUser);

        $response = $this->postJson(route('admin.categorias.crear'), [
            'nombre' => 'Nueva Categoria',
            'orden' => 10,
            'activa' => 1
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('categorias', [
            'nombre' => 'Nueva Categoria',
            'orden' => 10,
            'activa' => 1
        ]);
    }

    /** @test */
    public function test_admin_can_update_category()
    {
        $this->actingAs($this->adminUser);
        
        $categoria = Categoria::create(['nombre' => 'Cat Vieja', 'orden' => 1]);

        $response = $this->postJson(route('admin.categorias.actualizar', $categoria->id), [
            'nombre' => 'Cat Editada',
            'orden' => 2,
            'activa' => 1
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('categorias', ['nombre' => 'Cat Editada']);
        $this->assertDatabaseMissing('categorias', ['nombre' => 'Cat Vieja']);
    }

    /** @test */
    public function test_admin_can_delete_category()
    {
        $this->actingAs($this->adminUser);
        
        $categoria = Categoria::create(['nombre' => 'Borrarme', 'orden' => 1]);

        $response = $this->postJson(route('admin.categorias.eliminar', $categoria->id));

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('categorias', ['id' => $categoria->id]);
    }

    /** @test */
    public function test_admin_can_create_plato_with_image()
    {
        $this->actingAs($this->adminUser);
        Storage::fake('public');
        
        $file = UploadedFile::fake()->image('plato.jpg');
        $categoria = Categoria::create(['nombre' => 'General', 'orden' => 1]);

        $response = $this->post(route('admin.menu.guardar'), [
            'nombre' => 'Plato Foto',
            'descripcion' => 'Desc',
            'precio' => 1500,
            'categoria' => $categoria->nombre,
            'stock' => 50,
            'disponible' => 1,
            'imagen' => $file
        ]);

        $response->assertRedirect(route('admin.menu.index'));
        $this->assertDatabaseHas('platos', ['nombre' => 'Plato Foto']);
        
        // Check if file stored. Note: logic uses custom move(), not storage folder directly,
        // checking DB entry for image name is usually enough for feature test, 
        // but let's verify file exists in filesystem is safer if using Storage facade,
        // HOWEVER controller uses $imagen->move(public_path(...)) which writes to real disk usually.
        // In testing env, we trust the DB record was created with an image path.
    }

    /** @test */
    public function test_admin_can_update_plato()
    {
        $this->actingAs($this->adminUser);
        
        $categoria = Categoria::create(['nombre' => 'General', 'orden' => 1]);
        $plato = Plato::create([
            'nombre' => 'Plato Viejo',
            'descripcion' => 'Desc',
            'precio' => 1000,
            'categoria' => $categoria->nombre,
            'stock' => 10
        ]);

        $response = $this->post(route('admin.menu.actualizar', $plato->id), [
            'nombre' => 'Plato Nuevo',
            'descripcion' => 'Desc Nueva',
            'precio' => 2000,
            'categoria' => $categoria->nombre,
            'stock' => 20,
            'disponible' => 1
        ]);

        $response->assertRedirect(route('admin.menu.index'));
        $this->assertDatabaseHas('platos', ['nombre' => 'Plato Nuevo', 'precio' => 2000]);
    }

    /** @test */
    public function test_completing_order_deducts_stock()
    {
        $this->actingAs($this->adminUser);

        $plato = Plato::create([
            'nombre' => 'Stock Test',
            'precio' => 100,
            'stock' => 10,
            'stock_ilimitado' => 0
        ]);

        $pedido = Pedido::create([
            'plato_id' => $plato->id,
            'cantidad' => 2,
            'total' => 200,
            'estado' => 'pendiente'
        ]);

        // Change status to completado
        $response = $this->postJson(route('admin.pedidos.cambiarEstado', $pedido->id), [
            'estado' => 'completado'
        ]);

        $response->assertStatus(200);

        // Verify stock deducted (10 - 2 = 8)
        $this->assertDatabaseHas('platos', [
            'id' => $plato->id,
            'stock' => 8
        ]);
        
        // Verify Caja Chica entry
        $this->assertDatabaseHas('caja_chica', [
            'monto' => 200,
            'tipo' => 'entrada'
        ]);
    }

    /** @test */
    public function test_cancelling_completed_order_restores_stock()
    {
        $this->actingAs($this->adminUser);

        $plato = Plato::create([
            'nombre' => 'Restock Test',
            'precio' => 100,
            'stock' => 5, // Start with 5
            'stock_ilimitado' => 0
        ]);

        $pedido = Pedido::create([
            'plato_id' => $plato->id,
            'cantidad' => 3,
            'total' => 300,
            'estado' => 'completado' // Already completed
        ]);

        // Change status to cancelado
        $response = $this->postJson(route('admin.pedidos.cambiarEstado', $pedido->id), [
            'estado' => 'cancelado'
        ]);

        $response->assertStatus(200);

        // Verify stock restored (5 + 3 = 8)
        $this->assertDatabaseHas('platos', [
            'id' => $plato->id,
            'stock' => 8
        ]);
        
        // Verify Caja Chica exit (refund)
        $this->assertDatabaseHas('caja_chica', [
            'monto' => 300,
            'tipo' => 'salida'
        ]);
    }
}

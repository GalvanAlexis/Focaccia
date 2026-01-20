<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Plato;
use App\Models\Categoria;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MigrationHealthCheckTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $normalUser;
    protected $vendedorUser;
    protected $testPlato;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Roles
        // Ensure roles exist for testing (Spatie)
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleVendedor = Role::firstOrCreate(['name' => 'vendedor']);

        // 2. Setup Users
        $this->adminUser = User::factory()->create([
            'email' => 'admin@test.com',
            'name' => 'Admin Test'
        ]);
        $this->adminUser->assignRole($roleAdmin);

        $this->vendedorUser = User::factory()->create([
            'email' => 'vendedor@test.com',
            'name' => 'Vendedor Test'
        ]);
        $this->vendedorUser->assignRole($roleVendedor);

        $this->normalUser = User::factory()->create([
            'email' => 'user@test.com',
            'name' => 'User Test'
        ]);

        // 3. Setup Data
        Categoria::create(['nombre' => 'Test Cat', 'activa' => true, 'orden' => 1]);
        
        $this->testPlato = Plato::create([
            'nombre' => 'Plato Test',
            'descripcion' => 'Descripción test',
            'precio' => 1000,
            'categoria' => 'Test Cat',
            'disponible' => true,
            'stock' => 10,
            'stock_ilimitado' => false
        ]);
    }

    /** @test */
    public function public_home_page_is_accessible()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('La Bartola');
    }

    /** @test */
    public function public_cart_page_is_accessible()
    {
        $response = $this->get('/carrito');
        $response->assertStatus(200);
    }

    /** @test */
    public function cart_api_add_item_works()
    {
        // Test adding item to session cart
        $response = $this->postJson(route('carrito.agregar'), [
            'plato_id' => $this->testPlato->id,
            'cantidad' => 2
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        // Verify session data
        $cart = session('carrito');
        $this->assertArrayHasKey($this->testPlato->id, $cart);
        $this->assertEquals(2, $cart[$this->testPlato->id]['cantidad']);
    }

    /** @test */
    public function cart_api_count_works()
    {
        // Add item first
        $this->postJson(route('carrito.agregar'), [
            'plato_id' => $this->testPlato->id,
            'cantidad' => 3
        ]);

        $response = $this->getJson(route('carrito.getCount'));
        
        $response->assertStatus(200)
                 ->assertJson(['cart_count' => 3]);
    }

    /** @test */
    public function cart_api_remove_item_works()
    {
        // Add item first
        $this->postJson(route('carrito.agregar'), [
            'plato_id' => $this->testPlato->id,
            'cantidad' => 1
        ]);

        // Remove item
        $response = $this->postJson(route('carrito.eliminar'), [
            'plato_id' => $this->testPlato->id
        ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $cart = session('carrito');
        $this->assertArrayNotHasKey($this->testPlato->id, $cart ?? []);
    }

    /** @test */
    public function admin_routes_are_protected_from_guests()
    {
        $response = $this->get(route('admin.pedidos.index'));
        $response->assertRedirect('/login');

        $response = $this->get(route('admin.caja-chica.index'));
        $response->assertRedirect('/login');
    }

    /** @test */
    public function admin_routes_are_protected_from_normal_users()
    {
        $this->actingAs($this->normalUser);

        $response = $this->get(route('admin.pedidos.index'));
        // Spatie usually throws 403 UnauthorizedException
        $response->assertStatus(403); 
    }

    /** @test */
    public function admin_can_access_restricted_routes()
    {
        $this->actingAs($this->adminUser);

        // Pedidos
        $response = $this->get(route('admin.pedidos.index'));
        $response->assertStatus(200);

        // Caja Chica
        $response = $this->get(route('admin.caja-chica.index'));
        $response->assertStatus(200);

        // Menu
        $response = $this->get(route('admin.menu.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function vendedor_can_access_menu_but_not_pedidos()
    {
        // Assuming Vendedor only has access to menu/categorias based on routes/web.php
        // Route::middleware(['auth', 'role:admin|vendedor']) -> menu, categorias
        // Route::middleware(['auth', 'role:admin']) -> pedidos, caja-chica

        $this->actingAs($this->vendedorUser);

        // Should access Menu
        $response = $this->get(route('admin.menu.index'));
        $response->assertStatus(200);

        // Should NOT access Pedidos (Admin only)
        $response = $this->get(route('admin.pedidos.index'));
        $response->assertStatus(403);
    }
}

# La Bartola - Sistema de Gestión de Pedidos y Menú

## Descripción del Proyecto

La Bartola es una aplicación web completa para gestión de pedidos, menú y caja chica de un restaurante/casa de comidas. El sistema permite realizar pedidos públicos (sin login), gestionar stock automáticamente, integrar con WhatsApp, y llevar control de caja chica con separación de pagos digitales y efectivo.

**Stack Tecnológico:**
- Laravel 12 (PHP 8.2+)
- MySQL 8.0
- Bootstrap 5.3.3
- JavaScript Vanilla
- Spatie Laravel Permission (roles y permisos)

**Ubicación:** `C:\Dev\labartolalaravel`

---

## Arquitectura del Sistema

### Base de Datos (6 tablas principales)

1. **users** - Usuarios del sistema
   - Campos: id, name, email, password, timestamps
   - Relaciones: hasMany(Pedido), hasMany(Notificacion), hasMany(CajaChica)

2. **categorias** - Categorías de platos
   - Campos: id, nombre (unique), orden, activa (boolean), timestamps
   - 5 categorías iniciales: Bebidas, Empanadas, Pizzas, Tartas, Postres
   - Scope: `activas()` - filtra por activa = true

3. **platos** - Menú de platos disponibles
   - Campos: id, nombre, descripcion, precio (decimal 10,2), categoria, disponible (boolean), imagen, stock (int), stock_ilimitado (boolean), timestamps
   - Relaciones: hasMany(Pedido)
   - Cache: 5 minutos en `platos_disponibles`
   - Lógica: Si stock_ilimitado=1, no se descuenta stock. Si stock<=0 y stock_ilimitado=0, se marca disponible=0

4. **pedidos** - Pedidos realizados
   - Campos: id, usuario_id (nullable - permite pedidos públicos), plato_id, cantidad, total, estado (enum: pendiente, confirmado, en_camino, completado, cancelado), tipo_entrega, direccion, forma_pago, notas (text), timestamps
   - Relaciones: belongsTo(User), belongsTo(Plato)
   - **Lógica crítica:** Al cambiar estado a "completado" se descuenta stock y registra en caja_chica. Al cancelar pedido completado se devuelve stock y registra salida en caja_chica.

5. **caja_chica** - Movimientos de caja
   - Campos: id, fecha, hora, concepto, tipo (enum: entrada, salida), monto (decimal), es_digital (boolean), user_id, timestamps
   - **Lógica:** es_digital=1 si forma_pago es 'qr', 'mercado_pago', 'tarjeta'. es_digital=0 si es 'efectivo', 'transferencia'.
   - Métodos estáticos: `getMovimientosPorFecha($fecha)`, `getSaldoDia($fecha)` retorna array con entradas, salidas, saldo, efectivo, digital

6. **notificaciones** - Sistema de notificaciones
   - Campos: id, user_id, tipo, titulo, mensaje, icono (clase Bootstrap Icons), url, leida (boolean), data (json), timestamps
   - Métodos estáticos: `crearNotificacion($data)`, `getByUser($userId)`, `countUnread($userId)`, `markAsRead($id)`, `markAllAsRead($userId)`

### Modelos Eloquent (app/Models/)

**User.php**
- Traits: HasFactory, Notifiable, HasRoles (Spatie)
- Relaciones: hasMany pedidos, notificaciones, cajaChica

**Categoria.php**
```php
// Scope para categorías activas
public function scopeActivas($query) {
    return $query->where('activa', true)->orderBy('orden');
}

// Helper estático
public static function getActivas() {
    return self::activas()->get();
}
```

**Plato.php**
- Casts: disponible => boolean, stock_ilimitado => boolean, precio => decimal:2
- Relaciones: hasMany(Pedido::class)

**Pedido.php**
- Relaciones: belongsTo(User, 'usuario_id'), belongsTo(Plato, 'plato_id')

**CajaChica.php**
```php
public static function getSaldoDia($fecha) {
    // Retorna ['entradas' => $total, 'salidas' => $total, 'saldo' => $saldo,
    //          'efectivo' => $efectivo, 'digital' => $digital]
}
```

**Notificacion.php**
```php
public static function crearNotificacion($data) {
    // $data = ['user_id', 'tipo', 'titulo', 'mensaje', 'icono', 'url', 'data']
}
```

---

## Controladores (app/Http/Controllers/)

### HomeController.php
- `index()`: Retorna home con platos disponibles (cached 5 min) y carrito de sesión

### CarritoController.php (322 líneas)
- `index()`: Muestra carrito de sesión
- `agregar(Request)`: Agrega plato al carrito con validación de stock
- `actualizar(Request)`: Actualiza cantidad con validación de stock
- `eliminar(Request)`: Elimina item del carrito
- `vaciar()`: Limpia carrito de sesión
- `finalizar(Request)`: Crea pedidos, descuenta stock, limpia carrito. Acepta pedidos sin login (usuario_id = null). Campos requeridos: nombre_cliente, tipo_entrega, direccion, forma_pago
- `getCount()`: Retorna JSON con cart_count

**Lógica de stock en agregar/actualizar:**
```php
if ($plato->stock_ilimitado == 0) {
    if ($cantidad > $plato->stock) {
        return error "Stock insuficiente. Disponible: {$plato->stock}";
    }
}
```

### Admin/MenuController.php
- Middleware: auth, role:admin|vendedor
- `index()`: Lista platos con categorías
- `crear()`: Formulario nuevo plato
- `guardar(Request)`: Upload imagen a public/assets/images/platos/, crea plato, limpia cache
- `editar($id)`: Formulario editar
- `actualizar(Request, $id)`: Update plato, reemplaza imagen si hay nueva, limpia cache
- `eliminar($id)`: Elimina plato y su imagen física, limpia cache
- `obtenerPlatos()`: JSON con platos disponibles

**Upload de imágenes:**
```php
$nombreImagen = bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;
$imagen->move(public_path('assets/images/platos'), $nombreImagen);
Cache::forget('platos_disponibles');
```

### Admin/CategoriasController.php
- Middleware: auth, role:admin|vendedor
- CRUD completo con respuestas JSON
- `index()`: Vista con todas las categorías
- `crear(Request)`: Valida nombre unique, crea categoría
- `actualizar(Request, $id)`: Valida nombre unique excepto id actual
- `eliminar($id)`: Elimina categoría
- `obtenerTodas()`: JSON con todas las categorías

### Admin/PedidosController.php (540 líneas - EL MÁS COMPLEJO)
- Middleware: auth, role:admin

**Métodos principales:**
- `index()`: Lista pedidos con joins a users y platos, procesa notas con extraerInfoPedido()
- `ver($id)`: Detalle de pedido
- `editar($id)`: GET muestra form, POST actualiza estado
- `cambiarEstado(Request, $id)`: **CRÍTICO** - Lógica de negocio principal
- `eliminar($id)`: Elimina pedido
- `actualizarItem(Request)`: Actualiza cantidad de item con validación stock
- `agregarPlato(Request)`: Agrega plato a pedido existente
- `imprimirTicket($id)`: Vista para imprimir

**Lógica cambiarEstado() - CORE BUSINESS LOGIC:**
```php
// 1. Si nuevo estado = "completado" Y anterior != "completado":
//    - descontarStock($plato_id, $cantidad)
//    - registrarEnCajaChica($pedido, 'entrada')

// 2. Si nuevo estado = "cancelado" Y anterior = "completado":
//    - devolverStock($plato_id, $cantidad)
//    - registrarEnCajaChica($pedido, 'salida')

// 3. Crear notificación si usuario_id no es null
$mensajes = [
    'pendiente' => 'Tu pedido está pendiente de confirmación',
    'confirmado' => 'Tu pedido ha sido confirmado y está siendo preparado',
    'en_camino' => 'Tu pedido está en camino',
    'completado' => '¡Tu pedido ha sido completado!',
    'cancelado' => 'Tu pedido ha sido cancelado'
];

Notificacion::crearNotificacion([
    'user_id' => $pedido->usuario_id,
    'tipo' => 'cambio_estado_pedido',
    'titulo' => 'Actualización de Pedido #' . $id,
    'mensaje' => $mensajes[$nuevoEstado],
    'icono' => $iconos[$nuevoEstado],
    'url' => route('pedido.index')
]);
```

**Métodos privados:**
```php
private function descontarStock($platoId, $cantidad) {
    // Si stock_ilimitado=1, return true
    // Sino: stock = max(0, stock - cantidad)
    // Limpiar cache
}

private function devolverStock($platoId, $cantidad) {
    // Si stock_ilimitado=1, return true
    // Sino: stock = stock + cantidad
    // Limpiar cache
}

private function registrarEnCajaChica($pedido, $tipo) {
    // Extraer forma_pago de notas
    // es_digital = in_array(forma_pago, ['qr', 'mercado_pago', 'tarjeta'])
    // concepto = "Pedido #{id} - {nombre_cliente}" o "Devolución Pedido #{id}"
    // CajaChica::create([...])
}

private function extraerInfoPedido($notas) {
    // Parse con regex:
    // - "A nombre de: (.+)"
    // - "Tipo de entrega: (.+)"
    // - "Direccion: (.+)"
    // - "Forma de pago: (.+)"
    // Retorna array con info parseada
}
```

### Admin/CajaChicaController.php
- Middleware: auth, role:admin
- `index()`: Lista movimientos del día actual con saldo
- `ver($fecha)`: Movimientos de fecha específica
- `agregar(Request)`: Crea movimiento manual
- `editar($id)`: GET form, POST actualiza
- `eliminar($id)`: Elimina movimiento
- `archivo()`: Vista histórico por fechas
- `imprimir($fecha)`: Vista de impresión

### Auth/LoginController.php
- `showLoginForm()`: Guarda redirect URL en sesión si viene en query
- `login(Request)`: Valida credenciales, regenera sesión, redirige a URL guardada o /admin/pedidos

### Auth/LogoutController.php
- `logout(Request)`: Logout, invalida sesión, regenera token, redirige a home

### Auth/RegisterController.php
- `showRegistrationForm()`: Vista registro
- `register(Request)`: Crea usuario, asigna rol 'cliente', auto-login, redirige home

---

## Rutas (routes/web.php)

**Públicas:**
- GET `/` → HomeController@index
- GET `/carrito` → CarritoController@index
- POST `/carrito/agregar` → CarritoController@agregar
- POST `/carrito/actualizar` → CarritoController@actualizar
- POST `/carrito/eliminar` → CarritoController@eliminar
- POST `/carrito/vaciar` → CarritoController@vaciar
- GET `/carrito/getCount` → CarritoController@getCount
- POST `/carrito/finalizar` → CarritoController@finalizar (SIN AUTH)

**Auth requerido:**
- GET `/pedido` → view('pedido.index') - Pedidos del usuario

**Admin (role:admin):**
- Prefix: `/admin`
- Pedidos: 8 rutas (index, ver, editar, cambiarEstado, actualizarItem, agregarPlato, eliminar, imprimir)
- Caja Chica: 7 rutas (index, ver, agregar, editar, eliminar, archivo, imprimir)

**Admin o Vendedor (role:admin|vendedor):**
- Prefix: `/admin`
- Menú: 7 rutas (index, crear, guardar, editar, actualizar, eliminar, obtenerPlatos)
- Categorías: 5 rutas (index, crear, actualizar, eliminar, obtenerTodas)

**Auth (routes/auth.php):**
- GET/POST `/login`, `/register`
- POST `/logout`

---

## Vistas Blade (resources/views/)

### Layouts
- `layouts/main.blade.php`: Navbar con lógica de roles (PHP if statements dentro de Blade), cart count, notificaciones

### Home & Carrito
- `home.blade.php`: Vista optimizada, preload de CSS, lazy loading imágenes, organización por categorías
- `carrito/index.blade.php` (1,160 líneas): Carrito completo, modal confirmación, modal pedido, integración WhatsApp

### Admin
- `admin/pedidos/`: index, ver, editar, ticket
- `admin/caja_chica/`: index, archivo, imprimir
- `admin/menu/`: index, crear, editar
- `admin/categorias/`: index

### Auth
- `auth/login.blade.php`

**Nota:** Las vistas usan mezcla de sintaxis Blade (@if, @foreach, {{ }}) y PHP nativo (<?php ?>) porque fueron convertidas automáticamente. Ambas funcionan correctamente.

---

## Assets (public/assets/)

### CSS
- `css/main.css`: Estilos globales, navbar, footer
- `css/home.css`: Estilos específicos home (categorías, platos, carrito flotante)

### JavaScript
- `js/main.js`: Funciones globales, cart count, notificaciones
- `js/home.js`: Lógica carrito, búsqueda, toggleCategory, addToCart, changeQuantity

### Imágenes
- `images/logo.png`: Logo circular de La Bartola
- `images/platos/`: Directorio para uploads (formato: {random}_{timestamp}.{ext})

---

## Flujos de Negocio Importantes

### 1. Flujo Pedido Público (SIN LOGIN)
1. Usuario navega home → selecciona platos → carrito
2. Click "Confirmar Pedido" → modal con formulario
3. Llena: nombre, domicilio, forma_pago
4. POST `/carrito/finalizar` → CarritoController:
   - Crea un pedido por cada item en carrito
   - usuario_id = null
   - Descuenta stock si stock_ilimitado=0
   - Si stock<=0, marca disponible=0
   - Limpia cache y sesión
5. Construye mensaje WhatsApp y abre ventana
6. Redirige a home con mensaje success

### 2. Flujo Cambio Estado Pedido (ADMIN)
1. Admin entra `/admin/pedidos` → ve lista
2. Click estado dropdown → cambia a "completado"
3. POST `/admin/pedidos/cambiarEstado/{id}`:
   - Verifica estado anterior
   - Si pendiente → completado:
     - descontarStock()
     - registrarEnCajaChica('entrada') con es_digital según forma_pago
     - Crear notificación al usuario (si no es null)
   - Si completado → cancelado:
     - devolverStock()
     - registrarEnCajaChica('salida')
4. Retorna JSON success

### 3. Flujo Gestión Menú (ADMIN/VENDEDOR)
1. Admin entra `/admin/menu`
2. Click "Crear Plato" → formulario
3. POST `/admin/menu/guardar`:
   - Valida imagen requerida
   - Upload a public/assets/images/platos/
   - Cache::forget('platos_disponibles')
4. Redirige con mensaje success

### 4. Flujo Caja Chica (SOLO ADMIN)
1. Admin entra `/admin/caja-chica` → ve movimientos del día
2. Visualiza: entradas (verde), salidas (rojo), saldo total
3. Separación: efectivo vs digital
4. Puede ver archivo histórico por fechas
5. Puede imprimir reporte del día

---

## Configuración (.env crítico)

```env
APP_NAME="La Bartola"
APP_LOCALE=es

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=labartola
DB_USERNAME=root
DB_PASSWORD=root_password_2024

CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## Comandos Artisan Importantes

```bash
# Migraciones
php artisan migrate:fresh --seed  # Reset completo

# Cache
php artisan cache:clear
php artisan view:clear

# Crear usuario admin
php artisan tinker
>>> $u = User::create(['name'=>'Admin','email'=>'admin@test.com','password'=>Hash::make('pass')]);
>>> $u->assignRole('admin');

# Ver rutas
php artisan route:list --except-vendor
```

---

## Reglas de Negocio Críticas

1. **Stock Management:**
   - Si stock_ilimitado=1: NO se toca el campo stock nunca
   - Si stock_ilimitado=0:
     - Al agregar carrito: validar cantidad <= stock
     - Al finalizar pedido: NO descuenta (se descuenta al completar)
     - Al completar pedido: descuenta stock
     - Si stock<=0: marca disponible=0 automáticamente

2. **Caja Chica Automática:**
   - Solo registra al completar o cancelar pedidos completados
   - es_digital determinado por forma_pago:
     - Digital: 'qr', 'mercado_pago', 'tarjeta'
     - Efectivo: 'efectivo', 'transferencia'

3. **Pedidos Públicos:**
   - usuario_id puede ser NULL
   - No crear notificaciones si usuario_id es NULL
   - Notas contienen toda la info parseada del cliente

4. **Cache:**
   - Platos: 5 minutos, key 'platos_disponibles'
   - Invalidar en: crear/editar/eliminar plato, cambiar stock

5. **Roles:**
   - admin: TODO
   - vendedor: Menú + Pedidos (NO caja chica)
   - cliente: Ver sus pedidos + notificaciones

---

## Testing Checklist

- [ ] Pedido público sin login funciona
- [ ] Validación de stock en carrito
- [ ] Descuento automático al completar pedido
- [ ] Registro automático en caja chica
- [ ] Devolución de stock al cancelar
- [ ] Notificaciones se crean correctamente
- [ ] Cache se limpia al cambiar stock
- [ ] Upload de imágenes funciona
- [ ] Roles restringen acceso correctamente
- [ ] WhatsApp se abre con mensaje correcto

---

**Para Google Antigravity:** Este documento describe completamente el sistema para que puedas entender la arquitectura, modificar código, agregar features o debuggear sin necesitar contexto adicional.

# 📋 Ficha Técnica - Focaccia

> **Sistema de Gestión de Pedidos para Restaurante**  
> Migrado de CodeIgniter 4 a Laravel 12

---

## 🎯 Información General

| Campo                   | Detalle                                               |
| ----------------------- | ----------------------------------------------------- |
| **Nombre del Proyecto** | Focaccia (anteriormente "La Bartola")                 |
| **Versión**             | 1.0                                                   |
| **Framework**           | Laravel 12                                            |
| **PHP**                 | 8.2+                                                  |
| **Base de Datos**       | SQLite / MySQL 8.0                                    |
| **Servidor Local**      | http://127.0.0.1:8000                                 |
| **Ubicación**           | `C:\Users\PC Blado\Desktop\AIDO\3-Proyectos\Focaccia` |

---

## 🛠️ Stack Tecnológico

### Backend

- **Framework:** Laravel 12
- **Lenguaje:** PHP 8.2+
- **ORM:** Eloquent
- **Autenticación:** Laravel Auth + Spatie Permission
- **Cache:** File-based (configurable)
- **Sesiones:** File-based

### Frontend

- **CSS Framework:** Bootstrap 5.3.8
- **Iconos:** Bootstrap Icons 1.13.1
- **JavaScript:** Vanilla JS
- **Fuentes:** Poppins (Google Fonts via @fontsource)
- **Build Tool:** Vite 7.0.7
- **CSS Adicional:** TailwindCSS 4.0

### Base de Datos

- **Actual:** SQLite (`database/database.sqlite`)
- **Alternativa:** MySQL 8.0 (puerto 3307)
- **Migraciones:** Laravel Migrations
- **Seeders:** Incluidos para datos iniciales

---

## 📊 Arquitectura de Base de Datos

### Tablas Principales (6)

#### 1. **users**

- Gestión de usuarios del sistema
- Campos: `id`, `name`, `email`, `password`, `timestamps`
- Relaciones: `hasMany(Pedido)`, `hasMany(Notificacion)`, `hasMany(CajaChica)`

#### 2. **categorias**

- Categorías de platos del menú
- Campos: `id`, `nombre` (unique), `orden`, `activa` (boolean), `timestamps`
- Categorías iniciales: Bebidas, Empanadas, Pizzas, Tartas, Postres
- Scope: `activas()` - filtra categorías activas

#### 3. **platos**

- Menú de platos disponibles
- Campos: `id`, `nombre`, `descripcion`, `precio`, `categoria`, `disponible`, `imagen`, `stock`, `stock_ilimitado`, `timestamps`
- Cache: 5 minutos en clave `platos_disponibles`
- Lógica: Si `stock_ilimitado=1` no se descuenta stock

#### 4. **pedidos**

- Pedidos realizados (con o sin usuario)
- Campos: `id`, `usuario_id` (nullable), `plato_id`, `cantidad`, `total`, `estado`, `tipo_entrega`, `direccion`, `forma_pago`, `notas`, `timestamps`
- Estados: `pendiente`, `confirmado`, `en_camino`, `completado`, `cancelado`
- Permite pedidos públicos (`usuario_id = null`)

#### 5. **caja_chica**

- Movimientos de caja automáticos
- Campos: `id`, `fecha`, `hora`, `concepto`, `tipo` (entrada/salida), `monto`, `es_digital`, `user_id`, `timestamps`
- Lógica: `es_digital=1` para QR, MercadoPago, Tarjeta

#### 6. **notificaciones**

- Sistema de notificaciones para usuarios
- Campos: `id`, `user_id`, `tipo`, `titulo`, `mensaje`, `icono`, `url`, `leida`, `data` (json), `timestamps`

---

## 🔐 Sistema de Roles y Permisos

### Roles Disponibles

| Rol          | Permisos | Acceso                                    |
| ------------ | -------- | ----------------------------------------- |
| **Admin**    | Total    | Pedidos, Menú, Categorías, Caja Chica     |
| **Vendedor** | Limitado | Menú, Categorías, Pedidos (NO Caja Chica) |
| **Cliente**  | Básico   | Ver sus pedidos, Notificaciones           |

### Credenciales de Prueba

- **Email:** admin@labartola.com
- **Password:** admin123

---

## ✨ Funcionalidades Principales

### 1. Pedidos Públicos (Sin Login)

- Los clientes pueden realizar pedidos sin registrarse
- `usuario_id = null` en la base de datos
- Formulario con: nombre, domicilio, tipo de entrega, forma de pago
- Integración automática con WhatsApp

### 2. Gestión de Stock Automática

- **Stock Ilimitado:** `stock_ilimitado=1` → No se descuenta
- **Stock Limitado:** `stock_ilimitado=0` → Se descuenta al completar pedido
- Validación en tiempo real al agregar al carrito
- Si `stock <= 0` → marca `disponible=0` automáticamente

### 3. Caja Chica Automática

- Registro automático al completar pedidos
- Registro de devolución al cancelar pedidos completados
- Separación: Efectivo vs Digital
- Métodos digitales: QR, MercadoPago, Tarjeta
- Métodos efectivo: Efectivo, Transferencia

### 4. Sistema de Notificaciones

- Notificaciones automáticas al cambiar estado de pedido
- Solo para usuarios registrados (`usuario_id != null`)
- Mensajes personalizados por estado
- Iconos Bootstrap Icons

### 5. Integración WhatsApp

- Mensaje automático al confirmar pedido
- Incluye: items, total, dirección, forma de pago

---

## 🗂️ Estructura de Controladores

### Públicos

- **HomeController** - Vista pública con menú (cached)
- **CarritoController** - Gestión completa del carrito y finalización

### Admin (Solo Admin)

- **Admin/PedidosController** - Gestión de pedidos (540 líneas - el más complejo)
- **Admin/CajaChicaController** - Visualización y gestión de caja

### Admin/Vendedor

- **Admin/MenuController** - CRUD de platos con upload de imágenes
- **Admin/CategoriasController** - CRUD de categorías

### Autenticación

- **Auth/LoginController** - Login con redirect URL
- **Auth/RegisterController** - Registro con asignación de rol "cliente"
- **Auth/LogoutController** - Logout con regeneración de sesión

---

## 🔄 Flujos de Negocio Críticos

### Flujo 1: Pedido Público

1. Usuario navega home → selecciona platos → carrito
2. Click "Confirmar Pedido" → modal con formulario
3. Llena datos (nombre, domicilio, forma de pago)
4. `POST /carrito/finalizar`:
    - Crea pedidos (uno por item)
    - `usuario_id = null`
    - Valida stock (NO descuenta aún)
    - Limpia cache y sesión
5. Abre WhatsApp con mensaje
6. Redirige a home con mensaje de éxito

### Flujo 2: Cambio de Estado (Admin)

1. Admin accede `/admin/pedidos`
2. Cambia estado a "completado"
3. `POST /admin/pedidos/cambiarEstado/{id}`:
    - **Si pendiente → completado:**
        - Descuenta stock (si `stock_ilimitado=0`)
        - Registra entrada en caja chica
        - Crea notificación (si `usuario_id != null`)
    - **Si completado → cancelado:**
        - Devuelve stock
        - Registra salida en caja chica
4. Retorna JSON success

### Flujo 3: Gestión de Menú

1. Admin/Vendedor accede `/admin/menu`
2. Click "Crear Plato" → formulario
3. `POST /admin/menu/guardar`:
    - Valida imagen requerida
    - Upload a `public/assets/images/platos/`
    - Formato: `{random}_{timestamp}.{ext}`
    - Limpia cache `platos_disponibles`
4. Redirige con mensaje de éxito

---

## 🎨 Assets y Recursos

### CSS

- `public/assets/css/main.css` - Estilos globales
- `public/assets/css/home.css` - Estilos específicos home

### JavaScript

- `public/assets/js/main.js` - Funciones globales, cart count
- `public/assets/js/home.js` - Lógica carrito, búsqueda

### Imágenes

- `public/assets/images/logo.png` - Logo circular
- `public/assets/images/platos/` - Uploads de platos

---

## ⚙️ Configuración (.env)

```env
APP_NAME="Focaccia"
APP_LOCALE=es
APP_URL=http://127.0.0.1:8000

# SQLite (actual)
DB_CONNECTION=sqlite

# MySQL (alternativa - comentado)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3307
# DB_DATABASE=labartola
# DB_USERNAME=root
# DB_PASSWORD=root_password_2024

CACHE_DRIVER=file
SESSION_DRIVER=file
```

---

## 🚀 Comandos Importantes

### Iniciar Servidor

```bash
php artisan serve
# Acceso: http://127.0.0.1:8000
```

### Base de Datos

```bash
# Reset completo
php artisan migrate:fresh --seed

# Solo migraciones
php artisan migrate
```

### Cache

```bash
# Limpiar cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Crear Usuario Admin

```bash
php artisan tinker
>>> $u = User::create(['name'=>'Admin','email'=>'admin@test.com','password'=>Hash::make('pass')]);
>>> $u->assignRole('admin');
```

---

## 📝 Reglas de Negocio Críticas

### 1. Stock Management

- ✅ `stock_ilimitado=1`: NO se toca el campo stock
- ✅ `stock_ilimitado=0`:
    - Validar cantidad al agregar carrito
    - **NO** descuenta al crear pedido
    - **SÍ** descuenta al completar pedido
    - Si `stock <= 0`: marca `disponible=0`

### 2. Caja Chica Automática

- Solo registra al **completar** o **cancelar** pedidos completados
- `es_digital=1`: QR, MercadoPago, Tarjeta
- `es_digital=0`: Efectivo, Transferencia

### 3. Pedidos Públicos

- `usuario_id` puede ser `NULL`
- NO crear notificaciones si `usuario_id` es `NULL`
- Toda la info del cliente va en campo `notas`

### 4. Cache

- Platos: 5 minutos, clave `platos_disponibles`
- Invalidar en: crear/editar/eliminar plato, cambiar stock

---

## 🔍 Rutas Principales

### Públicas

- `GET /` - Home con menú
- `GET /carrito` - Vista del carrito
- `POST /carrito/finalizar` - Finalizar pedido (sin auth)

### Admin (role:admin)

- `GET /admin/pedidos` - Lista de pedidos
- `POST /admin/pedidos/cambiarEstado/{id}` - Cambiar estado
- `GET /admin/caja-chica` - Caja del día

### Admin/Vendedor (role:admin|vendedor)

- `GET /admin/menu` - Gestión de platos
- `GET /admin/categorias` - Gestión de categorías

### Autenticado

- `GET /pedido` - Pedidos del usuario
- `GET /login` - Login
- `POST /logout` - Logout

---

## 📦 Dependencias Principales

### Composer (PHP)

- `laravel/framework: ^12.0`
- `spatie/laravel-permission: ^6.24`
- `laravel/socialite: ^5.24`
- `league/oauth2-google: ^4.1`

### NPM (JavaScript)

- `bootstrap: ^5.3.8`
- `bootstrap-icons: ^1.13.1`
- `@fontsource/poppins: ^5.2.7`
- `vite: ^7.0.7`
- `tailwindcss: ^4.0.0`

---

## 🐛 Testing Checklist

- [x] Pedido público sin login funciona
- [x] Validación de stock en carrito
- [x] Descuento automático al completar pedido
- [x] Registro automático en caja chica
- [x] Devolución de stock al cancelar
- [x] Notificaciones se crean correctamente
- [x] Cache se limpia al cambiar stock
- [x] Upload de imágenes funciona
- [x] Roles restringen acceso correctamente
- [x] WhatsApp se abre con mensaje correcto

---

## 📚 Documentación Adicional

- **README.md** - Inicio rápido
- **agents.md** - Documentación completa para IA
- **MIGRACION_COMPLETA.md** - Proceso de migración desde CodeIgniter
- **RESUMEN-EJECUTIVO.md** - Resumen ejecutivo del proyecto
- **SOLUCION_CDN.md** - Soluciones de CDN y assets

---

## 🔧 Mantenimiento

### Archivos de Desarrollo (en carpeta `varios/`)

Scripts de utilidad para desarrollo y testing:

- `complete_orders.php` - Completar pedidos pendientes
- `crear_pedidos.php` - Generar pedidos de prueba
- `debug_caja.php` - Debug de caja chica
- `export_db.php` - Exportar SQLite a SQL
- `fix_filters.php` - Fix de filtros en vistas
- `replace_filters.php` - Reemplazo de filtros
- `test.php` - Página de prueba del servidor

**Nota:** Estos archivos NO son necesarios para el funcionamiento en producción.

---

**Última actualización:** 2026-01-20  
**Creado por:** Google Antigravity AI Agent

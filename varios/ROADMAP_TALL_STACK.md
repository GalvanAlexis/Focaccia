# 🚀 Roadmap: Migración a TALL Stack - Focaccia

> **Objetivo:** Migrar el frontend de Vanilla JavaScript a TALL Stack (Tailwind + Alpine.js + Livewire + Laravel)  
> **Duración estimada:** 8-12 horas de desarrollo  
> **Nivel de complejidad:** Medio

---

## 📋 Índice

1. [Preparación y Análisis](#fase-1-preparación-y-análisis)
2. [Instalación y Configuración](#fase-2-instalación-y-configuración)
3. [Migración de Componentes](#fase-3-migración-de-componentes)
4. [Optimización y Animaciones](#fase-4-optimización-y-animaciones)
5. [Testing y Validación](#fase-5-testing-y-validación)
6. [Deployment y Documentación](#fase-6-deployment-y-documentación)

---

## Fase 1: Preparación y Análisis

**Duración:** 30 minutos  
**Objetivo:** Entender el estado actual y planificar la migración

### 1.1 Backup del Proyecto

```bash
# Crear branch de backup
git checkout -b backup-vanilla-js
git add .
git commit -m "Backup: Estado actual antes de migración TALL Stack"
git push origin backup-vanilla-js

# Volver a blado
git checkout blado
```

### 1.2 Crear Branch de Desarrollo

```bash
git checkout -b feature/tall-stack-migration
```

### 1.3 Inventario de Componentes Actuales

**Componentes a migrar:**

- [ ] Header fijo con logo y redes sociales
- [ ] Buscador sticky con filtrado en vivo
- [ ] Categorías colapsables
- [ ] Items de plato con controles de cantidad
- [ ] Carrito flotante
- [ ] Sistema de validación de stock
- [ ] Sincronización con backend

**Archivos afectados:**

- `resources/views/home.blade.php` (189 líneas)
- `public/assets/js/home.js` (283 líneas)
- `public/assets/css/home.css` (486 líneas)
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/CarritoController.php`

---

## Fase 2: Instalación y Configuración

**Duración:** 1 hora  
**Objetivo:** Instalar y configurar TALL Stack

### 2.1 Instalar Livewire 3

```bash
# Instalar Livewire
composer require livewire/livewire

# Publicar assets
php artisan livewire:publish --config
php artisan livewire:publish --assets
```

**Verificar instalación:**

```bash
php artisan livewire:version
# Debe mostrar: Livewire 3.x
```

### 2.2 Configurar Alpine.js

Alpine.js viene incluido con Livewire 3, pero necesitamos configurarlo:

**Archivo:** `resources/js/app.js`

```javascript
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import * as bootstrap from "bootstrap";

window.bootstrap = bootstrap;

// Importar fuentes Poppins
import "@fontsource/poppins/300.css";
import "@fontsource/poppins/400.css";
import "@fontsource/poppins/500.css";
import "@fontsource/poppins/600.css";
import "@fontsource/poppins/700.css";

// Alpine.js ya viene con Livewire, no necesita import adicional
```

### 2.3 Configurar Tailwind CSS 4.0

Ya está instalado, solo necesitamos verificar la configuración:

**Archivo:** `resources/css/app.css`

```css
@import "bootstrap/dist/css/bootstrap.min.css";
@import "bootstrap-icons/font/bootstrap-icons.min.css";

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
  --font-sans: "Poppins", ui-sans-serif, system-ui, sans-serif;

  /* Colores Focaccia */
  --color-focaccia-red: #d92534;
  --color-focaccia-red-dark: #590902;
  --color-focaccia-gold: #d4b68a;
  --color-focaccia-gold-dark: #c9a770;
}
```

### 2.4 Actualizar Layout Principal

**Archivo:** `resources/views/layouts/app.blade.php` (crear si no existe)

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Focaccia - Delivery' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Italiana&display=swap" rel="stylesheet">

    @livewireStyles
</head>
<body>
    {{ $slot }}

    @livewireScripts
</body>
</html>
```

### 2.5 Compilar Assets

```bash
npm run build
```

**Verificar que no hay errores de compilación.**

---

## Fase 3: Migración de Componentes

**Duración:** 4-5 horas  
**Objetivo:** Convertir componentes Vanilla JS a Livewire + Alpine

### 3.1 Crear Componente Livewire: Cart

```bash
php artisan make:livewire Cart
```

**Archivo:** `app/Livewire/Cart.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use Livewire\Attributes\On;

class Cart extends Component
{
    public $items = [];
    public $totalItems = 0;
    public $totalPrice = 0;

    public function mount()
    {
        // Cargar carrito de la sesión
        $this->items = session('cart', []);
        $this->calculateTotals();
    }

    #[On('add-to-cart')]
    public function addItem($platoId)
    {
        $plato = Plato::find($platoId);

        if (!$plato || !$plato->disponible) {
            $this->dispatch('cart-error', message: 'Plato no disponible');
            return;
        }

        // Validar stock
        if ($plato->stock_ilimitado == 0) {
            $currentQty = $this->items[$platoId]['cantidad'] ?? 0;
            if ($currentQty >= $plato->stock) {
                $this->dispatch('stock-error', platoId: $platoId, stock: $plato->stock);
                return;
            }
        }

        if (!isset($this->items[$platoId])) {
            $this->items[$platoId] = [
                'nombre' => $plato->nombre,
                'precio' => $plato->precio,
                'cantidad' => 0,
                'stock' => $plato->stock,
                'stock_ilimitado' => $plato->stock_ilimitado
            ];
        }

        $this->items[$platoId]['cantidad']++;
        $this->saveCart();
        $this->calculateTotals();

        $this->dispatch('cart-updated');
    }

    #[On('update-quantity')]
    public function updateQuantity($platoId, $delta)
    {
        if (!isset($this->items[$platoId])) {
            return;
        }

        $newQuantity = $this->items[$platoId]['cantidad'] + $delta;

        // Validar stock
        if ($delta > 0 && $this->items[$platoId]['stock_ilimitado'] == 0) {
            if ($newQuantity > $this->items[$platoId]['stock']) {
                $this->dispatch('stock-error',
                    platoId: $platoId,
                    stock: $this->items[$platoId]['stock']
                );
                return;
            }
        }

        if ($newQuantity <= 0) {
            unset($this->items[$platoId]);
        } else {
            $this->items[$platoId]['cantidad'] = $newQuantity;
        }

        $this->saveCart();
        $this->calculateTotals();
        $this->dispatch('cart-updated');
    }

    private function saveCart()
    {
        session(['cart' => $this->items]);
    }

    private function calculateTotals()
    {
        $this->totalItems = 0;
        $this->totalPrice = 0;

        foreach ($this->items as $item) {
            $this->totalItems += $item['cantidad'];
            $this->totalPrice += $item['precio'] * $item['cantidad'];
        }
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
```

**Archivo:** `resources/views/livewire/cart.blade.php`

```blade
<div>
    <!-- Carrito Flotante -->
    @if($totalItems > 0)
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             @cart-updated.window="show = false; setTimeout(() => show = true, 50)"
             wire:navigate
             href="{{ route('carrito.index') }}"
             class="fixed bottom-5 left-1/2 -translate-x-1/2 bg-gradient-to-r from-[#D92534] to-[#590902] text-white px-8 py-4 rounded-full shadow-lg flex items-center gap-3 cursor-pointer hover:scale-105 transition-transform duration-200 z-50 min-w-[280px] justify-center">

            <i class="bi bi-cart3 text-2xl"></i>
            <span class="font-semibold text-lg">Ver tu pedido</span>

            <div class="bg-black text-white rounded-full w-6 h-6 flex items-center justify-center text-sm font-bold">
                {{ $totalItems }}
            </div>

            <span class="text-xl font-bold">
                ${{ number_format($totalPrice, 0, ',', '.') }}
            </span>
        </div>
    @endif
</div>
```

### 3.2 Crear Componente Livewire: MenuList

```bash
php artisan make:livewire MenuList
```

**Archivo:** `app/Livewire/MenuList.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Categoria;
use Illuminate\Support\Facades\Cache;

class MenuList extends Component
{
    public $search = '';
    public $platos = [];
    public $categorias = [];

    public function mount()
    {
        $this->loadPlatos();
    }

    public function updatedSearch()
    {
        $this->loadPlatos();
    }

    private function loadPlatos()
    {
        $query = Plato::where('disponible', true);

        if ($this->search) {
            $searchTerms = explode(' ', strtolower(trim($this->search)));

            $query->where(function($q) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $q->where(function($subQ) use ($term) {
                        $subQ->whereRaw('LOWER(nombre) LIKE ?', ["%{$term}%"])
                             ->orWhereRaw('LOWER(descripcion) LIKE ?', ["%{$term}%"]);
                    });
                }
            });
        }

        $platos = $query->get();

        // Organizar por categorías
        $this->categorias = [
            'Bebidas' => [],
            'Empanadas' => [],
            'Pizzas' => [],
            'Tartas' => [],
            'Postres' => []
        ];

        foreach ($platos as $plato) {
            if (isset($this->categorias[$plato->categoria])) {
                $this->categorias[$plato->categoria][] = $plato;
            }
        }

        // Filtrar categorías vacías
        $this->categorias = array_filter($this->categorias, fn($items) => !empty($items));
    }

    public function render()
    {
        return view('livewire.menu-list');
    }
}
```

**Archivo:** `resources/views/livewire/menu-list.blade.php`

```blade
<div>
    <!-- Buscador Sticky -->
    <div class="sticky top-0 z-40 bg-white border-b-2 border-gray-200 p-4">
        <div class="relative">
            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-[#D4B68A] text-xl"></i>

            <input type="text"
                   wire:model.live.debounce.300ms="search"
                   placeholder="Ingresá lo que estás buscando..."
                   class="w-full pl-12 pr-12 py-3 border-2 border-[#D4B68A] rounded-full text-base outline-none focus:ring-4 focus:ring-[#D4B68A]/20 transition-shadow">

            @if($search)
                <button wire:click="$set('search', '')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl hover:text-gray-600">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            @endif
        </div>

        <!-- Loading indicator -->
        <div wire:loading wire:target="search"
             class="absolute top-full left-0 right-0 h-1 bg-gradient-to-r from-[#D92534] to-[#590902] animate-pulse"></div>
    </div>

    <!-- Menú por Categorías -->
    <div class="p-4 space-y-5">
        @forelse($categorias as $nombreCategoria => $platosCategoria)
            <div x-data="{ open: true }"
                 class="bg-white rounded-xl overflow-hidden shadow-md">

                <!-- Header de Categoría -->
                <button @click="open = !open"
                        class="w-full bg-gradient-to-r from-[#D92534] to-[#590902] p-4 flex justify-between items-center text-white">
                    <h2 class="text-xl font-semibold">{{ $nombreCategoria }}</h2>
                    <i class="bi bi-chevron-up text-2xl transition-transform duration-300"
                       :class="{ 'rotate-180': !open }"></i>
                </button>

                <!-- Contenido de Categoría -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-screen"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-screen"
                     x-transition:leave-end="opacity-0 max-h-0"
                     class="overflow-hidden">

                    @foreach($platosCategoria as $plato)
                        <livewire:plato-item :plato="$plato" :key="$plato->id" />
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-10 text-gray-400">
                <i class="bi bi-inbox text-6xl mb-4"></i>
                <p>No hay platos disponibles</p>
            </div>
        @endforelse
    </div>
</div>
```

### 3.3 Crear Componente Livewire: PlatoItem

```bash
php artisan make:livewire PlatoItem
```

**Archivo:** `app/Livewire/PlatoItem.php`

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;

class PlatoItem extends Component
{
    public Plato $plato;
    public $cantidad = 0;
    public $showControls = false;

    public function mount()
    {
        // Cargar cantidad del carrito si existe
        $cart = session('cart', []);
        if (isset($cart[$this->plato->id])) {
            $this->cantidad = $cart[$this->plato->id]['cantidad'];
            $this->showControls = true;
        }
    }

    public function addToCart()
    {
        $this->showControls = true;
        $this->cantidad = 1;
        $this->dispatch('add-to-cart', platoId: $this->plato->id);
    }

    public function increment()
    {
        $this->dispatch('update-quantity', platoId: $this->plato->id, delta: 1);
    }

    public function decrement()
    {
        $this->dispatch('update-quantity', platoId: $this->plato->id, delta: -1);
    }

    public function render()
    {
        return view('livewire.plato-item');
    }
}
```

**Archivo:** `resources/views/livewire/plato-item.blade.php`

```blade
<div x-data="{
        stockError: false,
        added: false
     }"
     @stock-error.window="if ($event.detail.platoId == {{ $plato->id }}) {
         stockError = true;
         setTimeout(() => stockError = false, 1500)
     }"
     @cart-updated.window="
         if ({{ $cantidad }} > 0) {
             added = true;
             setTimeout(() => added = false, 300);
         }
         @if($cantidad == 0)
             $wire.showControls = false;
         @else
             $wire.cantidad = {{ $cantidad }};
             $wire.showControls = true;
         @endif
     "
     class="flex items-center gap-3 p-3 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">

    <!-- Imagen -->
    <div class="w-[70px] h-[70px] rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
        @if($plato->imagen)
            @php
                $imagenUrl = (strpos($plato->imagen, 'http') === 0)
                    ? $plato->imagen
                    : asset('assets/images/platos/' . $plato->imagen);
            @endphp
            <img src="{{ $imagenUrl }}"
                 alt="{{ $plato->nombre }}"
                 class="w-full h-full object-cover"
                 loading="lazy">
        @else
            <i class="bi bi-image text-4xl text-gray-400 flex items-center justify-center h-full"></i>
        @endif
    </div>

    <!-- Info -->
    <div class="flex-grow min-w-0">
        <div class="font-semibold text-base text-gray-800 mb-1">{{ $plato->nombre }}</div>
        <div class="text-sm text-gray-600 mb-1.5 line-clamp-2">{{ $plato->descripcion }}</div>
        <div class="text-lg font-bold text-[#D92534]">${{ number_format($plato->precio, 0, ',', '.') }}</div>
    </div>

    <!-- Controles -->
    <div class="flex-shrink-0">
        @if(!$showControls)
            <!-- Botón Agregar -->
            <button wire:click="addToCart"
                    :class="{ 'scale-110 bg-green-500': added }"
                    class="w-9 h-9 rounded-full bg-[#D4B68A] text-white flex items-center justify-center text-xl font-bold hover:scale-105 active:scale-95 transition-all duration-200">
                +
            </button>
        @else
            <!-- Controles de Cantidad -->
            <div class="flex items-center gap-2">
                <button wire:click="decrement"
                        class="w-9 h-9 rounded-full border-2 border-[#D4B68A] bg-white text-[#D4B68A] flex items-center justify-center text-xl hover:bg-[#D4B68A] hover:text-white active:scale-90 transition-all duration-200">
                    -
                </button>

                <div class="text-xl font-semibold min-w-[30px] text-center"
                     :class="{ 'text-red-500': stockError }">
                    <span x-show="!stockError">{{ $cantidad }}</span>
                    <span x-show="stockError" class="text-sm">Max: {{ $plato->stock }}</span>
                </div>

                <button wire:click="increment"
                        class="w-9 h-9 rounded-full border-2 border-[#D4B68A] bg-white text-[#D4B68A] flex items-center justify-center text-xl hover:bg-[#D4B68A] hover:text-white active:scale-90 transition-all duration-200">
                    +
                </button>
            </div>
        @endif
    </div>
</div>
```

### 3.4 Actualizar Vista Principal

**Archivo:** `resources/views/home.blade.php`

```blade
<x-layouts.app title="Focaccia - Delivery">
    <div class="min-h-screen bg-gray-50 pb-24">

        <!-- Header Fijo -->
        @include('partials.header')

        <!-- Menú con Livewire -->
        <livewire:menu-list />

        <!-- Carrito Flotante -->
        <livewire:cart />

    </div>
</x-layouts.app>
```

### 3.5 Crear Partial del Header

**Archivo:** `resources/views/partials/header.blade.php`

```blade
<header class="bg-gradient-to-b from-gray-100 to-gray-200 p-4 shadow-md relative">

    <!-- Carrito Header (absoluto) -->
    <a href="{{ route('carrito.index') }}"
       wire:navigate
       class="absolute top-4 right-4 w-11 h-11 rounded-full bg-gradient-to-br from-[#D4B68A] to-[#c9a770] text-black flex items-center justify-center text-xl shadow-lg hover:scale-105 active:scale-95 transition-transform z-10">
        <i class="bi bi-cart-fill"></i>
    </a>

    <!-- Redes Sociales -->
    <div class="flex justify-center gap-4 mb-4">
        <a href="https://instagram.com/focacciapizzeria_ch"
           target="_blank"
           class="w-10 h-10 rounded-full bg-[#D92534] text-white flex items-center justify-center text-xl hover:scale-105 active:scale-95 transition-transform">
            <i class="bi bi-instagram"></i>
        </a>
        <a href="https://wa.me/542241693947"
           target="_blank"
           class="w-10 h-10 rounded-full bg-[#D92534] text-white flex items-center justify-center text-xl hover:scale-105 active:scale-95 transition-transform">
            <i class="bi bi-whatsapp"></i>
        </a>
        <a href="https://www.facebook.com/p/Focaccia-Pizeria-100023398373286"
           target="_blank"
           class="w-10 h-10 rounded-full bg-[#D92534] text-white flex items-center justify-center text-xl hover:scale-105 active:scale-95 transition-transform">
            <i class="bi bi-facebook"></i>
        </a>
    </div>

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.png') }}"
             alt="Focaccia"
             class="w-[220px] h-[220px] mx-auto scale-110">
    </div>

    <!-- Info del Local -->
    <div class="bg-[#D92534]/5 border border-[#D92534]/10 rounded-2xl p-5 mb-4 space-y-3">
        <a href="https://www.google.com/maps/search/?api=1&query=Bolivia+55,+Chascomus,+Buenos+Aires"
           target="_blank"
           class="flex items-center justify-center gap-3 text-[#590902] hover:bg-[#D4B68A]/20 rounded-lg p-1 transition-colors">
            <i class="bi bi-geo-alt-fill text-[#D92534] text-xl"></i>
            <span class="font-medium">Bolivia 55, Chascomús</span>
        </a>

        <div class="flex items-center justify-center gap-3 text-[#590902]">
            <i class="bi bi-clock-fill text-[#D92534] text-xl"></i>
            <span class="font-medium text-center">
                <span class="inline-block">Mar a Jue: 20-23 |</span>
                <span class="inline-block">Vie a Dom: 20-23:30 hs</span>
            </span>
        </div>

        <div class="flex items-center justify-center gap-3 text-[#590902]">
            <i class="bi bi-bicycle text-[#D92534] text-xl"></i>
            <span class="font-medium">Envíos a Domicilio</span>
        </div>

        <div class="flex items-center justify-center gap-3 text-[#590902]">
            <i class="bi bi-credit-card-fill text-[#D92534] text-xl"></i>
            <span class="font-medium">Transferencia y Efectivo</span>
        </div>
    </div>

    <!-- Título y Tagline -->
    <div class="text-center">
        <h1 class="font-['Italiana'] text-[#D92534] text-6xl font-bold mb-2 tracking-wider">
            Focaccia
        </h1>
        <p class="text-[#590902] italic font-['Georgia'] text-lg">
            ¡Deliciosa y con todo el sabor que solo vos sabes!
        </p>
    </div>

</header>
```

---

## Fase 4: Optimización y Animaciones

**Duración:** 2 horas  
**Objetivo:** Agregar animaciones modernas y optimizar performance

### 4.1 Configurar Animaciones Globales

**Archivo:** `resources/css/app.css` (agregar al final)

```css
/* Animaciones personalizadas */
@keyframes slideInUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes pulse-scale {
  0%,
  100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
}

@keyframes shake {
  0%,
  100% {
    transform: translateX(0);
  }
  25% {
    transform: translateX(-5px);
  }
  75% {
    transform: translateX(5px);
  }
}

.animate-slide-in-up {
  animation: slideInUp 0.3s ease-out;
}

.animate-pulse-scale {
  animation: pulse-scale 0.5s ease-in-out;
}

.animate-shake {
  animation: shake 0.3s ease-in-out;
}
```

### 4.2 Agregar Loading States

**Archivo:** `resources/views/livewire/menu-list.blade.php` (actualizar)

Agregar después del buscador:

```blade
<!-- Loading Skeleton -->
<div wire:loading.delay wire:target="search" class="p-4 space-y-5">
    @for($i = 0; $i < 3; $i++)
        <div class="bg-white rounded-xl p-4 animate-pulse">
            <div class="h-6 bg-gray-200 rounded w-1/3 mb-4"></div>
            <div class="space-y-3">
                @for($j = 0; $j < 2; $j++)
                    <div class="flex gap-3">
                        <div class="w-[70px] h-[70px] bg-gray-200 rounded-lg"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-full"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endfor
</div>
```

### 4.3 Agregar Toast Notifications

**Crear componente:** `resources/views/components/toast.blade.php`

```blade
<div x-data="{
        show: false,
        message: '',
        type: 'success'
     }"
     @toast.window="
         message = $event.detail.message;
         type = $event.detail.type || 'success';
         show = true;
         setTimeout(() => show = false, 3000);
     "
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm"
     style="display: none;">

    <div class="rounded-lg shadow-lg p-4 flex items-center gap-3"
         :class="{
             'bg-green-500 text-white': type === 'success',
             'bg-red-500 text-white': type === 'error',
             'bg-blue-500 text-white': type === 'info'
         }">
        <i class="text-2xl"
           :class="{
               'bi bi-check-circle-fill': type === 'success',
               'bi bi-exclamation-circle-fill': type === 'error',
               'bi bi-info-circle-fill': type === 'info'
           }"></i>
        <span x-text="message" class="font-medium"></span>
    </div>
</div>
```

Incluir en `app.blade.php`:

```blade
<x-toast />
```

---

## Fase 5: Testing y Validación

**Duración:** 1-2 horas  
**Objetivo:** Verificar que todo funciona correctamente

### 5.1 Checklist de Testing

**Funcionalidad del Carrito:**

- [ ] Agregar plato al carrito
- [ ] Incrementar cantidad
- [ ] Decrementar cantidad
- [ ] Validación de stock
- [ ] Mensaje de stock insuficiente
- [ ] Eliminar item (cantidad = 0)
- [ ] Total se actualiza correctamente
- [ ] Persistencia en sesión

**Buscador:**

- [ ] Búsqueda en tiempo real
- [ ] Debounce funciona (300ms)
- [ ] Búsqueda multi-palabra
- [ ] Clear button funciona
- [ ] Categorías vacías se ocultan
- [ ] Loading state visible

**Categorías:**

- [ ] Toggle colapsar/expandir
- [ ] Animación suave
- [ ] Icono rota correctamente
- [ ] Estado persiste durante búsqueda

**Animaciones:**

- [ ] Transiciones suaves
- [ ] Loading skeletons
- [ ] Feedback visual al agregar
- [ ] Toast notifications
- [ ] Hover effects

**Performance:**

- [ ] Tiempo de carga < 2s
- [ ] Búsqueda responsive
- [ ] Sin lag en animaciones
- [ ] Imágenes lazy load

### 5.2 Testing Manual

```bash
# Limpiar cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Recompilar assets
npm run build

# Reiniciar servidor
php artisan serve
```

**Probar en navegador:**

1. Abrir http://127.0.0.1:8000
2. Verificar que el header se ve correctamente
3. Probar búsqueda con diferentes términos
4. Agregar varios platos al carrito
5. Modificar cantidades
6. Verificar validación de stock
7. Ir al carrito y verificar sincronización

### 5.3 Testing de Responsive

**Dispositivos a probar:**

- [ ] Mobile (375px)
- [ ] Tablet (768px)
- [ ] Desktop (1024px+)

**Chrome DevTools:**

```
F12 → Toggle Device Toolbar → Probar diferentes dispositivos
```

---

## Fase 6: Deployment y Documentación

**Duración:** 1 hora  
**Objetivo:** Preparar para producción y documentar cambios

### 6.1 Optimización para Producción

```bash
# Optimizar assets
npm run build

# Optimizar autoload
composer dump-autoload --optimize

# Cachear configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6.2 Actualizar Documentación

**Crear archivo:** `varios/MIGRACION_TALL_STACK.md`

Documentar:

- Cambios realizados
- Nuevos componentes Livewire
- Configuración de Alpine.js
- Guía de uso para desarrolladores
- Troubleshooting común

### 6.3 Commit y Push

```bash
# Verificar cambios
git status

# Agregar archivos
git add .

# Commit
git commit -m "feat: Migración completa a TALL Stack

- Instalado Livewire 3
- Configurado Alpine.js y Tailwind CSS 4.0
- Migrado carrito a componente Livewire reactivo
- Migrado menú y búsqueda a Livewire
- Agregadas animaciones modernas con Alpine
- Optimizado performance con lazy loading
- Agregados loading states y toast notifications

BREAKING CHANGES:
- Removido home.js (reemplazado por Livewire)
- Actualizado home.blade.php a componentes Livewire
- Modificado flujo de carrito (ahora reactivo)"

# Push
git push origin feature/tall-stack-migration
```

### 6.4 Crear Pull Request

1. Ir a GitHub: https://github.com/GalvanAlexis/Focaccia
2. Crear PR: `feature/tall-stack-migration` → `blado`
3. Título: "feat: Migración a TALL Stack (Livewire 3 + Alpine.js)"
4. Descripción detallada con screenshots
5. Solicitar review

### 6.5 Merge a Blado

Una vez aprobado:

```bash
git checkout blado
git merge feature/tall-stack-migration
git push origin blado
```

---

## 📊 Checklist Final

### Pre-Migración

- [ ] Backup creado (branch backup-vanilla-js)
- [ ] Branch de desarrollo creada
- [ ] Inventario de componentes completo

### Instalación

- [ ] Livewire 3 instalado
- [ ] Alpine.js configurado
- [ ] Tailwind CSS 4.0 verificado
- [ ] Assets compilados sin errores

### Componentes

- [ ] Cart component creado y funcional
- [ ] MenuList component creado y funcional
- [ ] PlatoItem component creado y funcional
- [ ] Header partial creado
- [ ] Layout app.blade.php creado

### Animaciones

- [ ] Transiciones configuradas
- [ ] Loading states agregados
- [ ] Toast notifications implementadas
- [ ] Hover effects funcionando

### Testing

- [ ] Funcionalidad del carrito validada
- [ ] Buscador funcionando correctamente
- [ ] Categorías colapsables OK
- [ ] Validación de stock funciona
- [ ] Responsive en todos los dispositivos

### Deployment

- [ ] Assets optimizados para producción
- [ ] Cache de Laravel configurado
- [ ] Documentación actualizada
- [ ] Commit y push realizados
- [ ] Pull Request creado

---

## 🚨 Troubleshooting

### Error: "Livewire component not found"

```bash
php artisan livewire:discover
php artisan view:clear
```

### Error: "Alpine is not defined"

Verificar que `@livewireScripts` está antes del cierre de `</body>`

### Error: "Class 'Livewire\Component' not found"

```bash
composer dump-autoload
php artisan clear-compiled
```

### Animaciones no funcionan

Verificar que Tailwind está compilando correctamente:

```bash
npm run build
# Verificar public/build/manifest.json
```

### Carrito no se actualiza

```bash
php artisan cache:clear
# Verificar que session está configurada en .env
```

---

## 📚 Recursos Adicionales

**Documentación oficial:**

- Livewire 3: https://livewire.laravel.com/docs
- Alpine.js: https://alpinejs.dev/
- Tailwind CSS 4: https://tailwindcss.com/docs

**Ejemplos de código:**

- Livewire Examples: https://livewire.laravel.com/examples
- Alpine.js Examples: https://alpinejs.dev/examples

---

**Última actualización:** 2026-01-20  
**Creado por:** Google Antigravity AI Agent  
**Versión:** 1.0

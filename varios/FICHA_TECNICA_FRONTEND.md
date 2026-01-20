# 🎨 Ficha Técnica Frontend - Focaccia

> **Sistema de Interfaz de Usuario para Aplicación de Pedidos**  
> Diseño Mobile-First con Identidad Visual Italiana

---

## 🎯 Información General

| Campo             | Detalle                          |
| ----------------- | -------------------------------- |
| **Proyecto**      | Focaccia - Sistema de Pedidos    |
| **Tipo**          | Mobile-First Progressive Web App |
| **Framework CSS** | Bootstrap 5.3.8 + CSS Custom     |
| **Framework JS**  | Vanilla JavaScript (ES6+)        |
| **Build Tool**    | Vite 7.0.7                       |
| **Fuentes**       | Poppins, Italiana, Georgia       |
| **Iconos**        | Bootstrap Icons 1.13.1           |

---

## 🎨 Sistema de Diseño

### Paleta de Colores

#### Colores Principales

```css
/* Rojo Focaccia (Principal) */
#D92534  /* Rojo vibrante - Botones, headers */
#590902  /* Rojo oscuro - Gradientes, textos */

/* Dorado/Beige (Secundario) */
#D4B68A  /* Dorado claro - Bordes, acentos */
#c9a770  /* Dorado medio - Hover states */

/* Neutros */
#F2F2F2  /* Gris muy claro - Backgrounds */
#E8E8E8  /* Gris claro - Gradientes */
#f5f5f5  /* Gris suave - Body background */
#fff     /* Blanco - Cards, inputs */
```

#### Colores de Estado

```css
/* Textos */
#333     /* Negro suave - Títulos */
#666     /* Gris medio - Descripciones */
#999     /* Gris claro - Placeholders */
#590902  /* Rojo oscuro - Textos importantes */

/* Feedback */
#dc3545  /* Rojo error - Validaciones */
#000     /* Negro - Badges */
```

### Tipografía

#### Familias Tipográficas

```css
/* Principal */
font-family: "Poppins", sans-serif;
/* Pesos: 300, 400, 500, 600, 700 */

/* Título Italiano */
font-family: "Italiana", serif;
/* Uso: Logo "Focaccia" */

/* Tagline */
font-family: "Georgia", serif;
/* Uso: Frase motivacional */
```

#### Escala Tipográfica

```css
/* Títulos */
h1 (Italiana): 3.5rem (56px) - Logo principal
h2: 1.3rem (20.8px) - Headers de categorías

/* Cuerpo */
Body: 1rem (16px) - Texto base
Plato nombre: 1rem (16px) - Peso 600
Plato descripción: 0.85rem (13.6px)
Plato precio: 1.1rem (17.6px) - Peso 700

/* UI Elements */
Botones: 1.1rem (17.6px) - Peso 600
Info items: 1rem (16px) - Peso 500
Tagline: 1.1rem (17.6px) - Italic
```

### Espaciado y Layout

#### Sistema de Espaciado

```css
/* Padding */
Cards: 12px
Headers: 15px
Sections: 15px-20px
Info items: 4px vertical

/* Gaps */
Flex gaps: 8px-15px
Category gap: 20px
Plato items: 12px

/* Margins */
Bottom spacing: 100px (para botón flotante)
Category sections: 20px
```

#### Border Radius

```css
/* Elementos */
Cards: 12px
Botones circulares: 50%
Imágenes platos: 10px
Inputs: 25px
Info section: 15px
```

---

## 📱 Componentes UI

### 1. Header Fijo

**Archivo:** `home.blade.php` + `home.css`

**Estructura:**

```
.fixed-header
├── .social-icons (Instagram, WhatsApp, Facebook)
├── .cart-header-absolute (Botón carrito superior)
├── .header-brand
│   └── .header-logo (220x220px, circular)
├── .info-section
│   ├── Dirección (con link a Google Maps)
│   ├── Horarios (Mar-Jue, Vie-Dom)
│   ├── Envíos a domicilio
│   └── Métodos de pago
└── .header-tagline-container
    ├── .italian-title ("Focaccia")
    └── .header-tagline (Frase motivacional)
```

**Características:**

- ✅ Gradiente gris claro (#F2F2F2 → #E8E8E8)
- ✅ Logo circular 220x220px con zoom 1.1x
- ✅ Redes sociales con iconos circulares rojos
- ✅ Info section con fondo rojo translúcido
- ✅ Título "Focaccia" con fuente Italiana 3.5rem

### 2. Buscador Sticky

**Archivo:** `home.js` + `home.css`

**Funcionalidad:**

```javascript
// Búsqueda multi-palabra
- Divide término en palabras individuales
- Busca en nombre y descripción
- Oculta categorías vacías
- Botón clear icon animado
```

**Características:**

- ✅ Sticky top (z-index: 100)
- ✅ Border dorado 2px (#D4B68A)
- ✅ Border radius 25px
- ✅ Iconos: search (izq), clear (der)
- ✅ Focus shadow dorado translúcido

### 3. Categorías Colapsables

**Archivo:** `home.blade.php` + `home.css`

**Estructura:**

```
.category-section
├── .category-header (clickeable)
│   ├── h2 (Nombre categoría)
│   └── i.bi-chevron-up (Icono toggle)
└── .category-content (colapsable)
    └── .plato-item (múltiples)
```

**Características:**

- ✅ Header con gradiente rojo (#D92534 → #590902)
- ✅ Toggle suave con transition 0.3s
- ✅ Icono rotate 180deg al colapsar
- ✅ Max-height animation

### 4. Item de Plato

**Archivo:** `home.blade.php` + `home.css`

**Estructura:**

```
.plato-item
├── .plato-image (70x70px)
├── .plato-info
│   ├── .plato-name (600 weight)
│   ├── .plato-description (2 líneas max)
│   └── .plato-price (rojo, 700 weight)
├── .add-btn (botón +)
└── .quantity-controls (oculto inicialmente)
    ├── .quantity-btn (-)
    ├── .quantity-display
    └── .quantity-btn (+)
```

**Características:**

- ✅ Layout flex horizontal
- ✅ Imagen 70x70px border-radius 10px
- ✅ Descripción truncada a 2 líneas
- ✅ Botón + dorado circular 35px
- ✅ Controles de cantidad con validación de stock

### 5. Carrito Flotante

**Archivo:** `home.js` + `home.css`

**Estructura:**

```
.cart-float (fixed bottom)
├── .cart-icon (bi-cart3)
├── span "Ver tu pedido"
├── .cart-badge (contador items)
└── .cart-total (precio total)
```

**Características:**

- ✅ Fixed bottom center (transform translateX(-50%))
- ✅ Gradiente rojo (#D92534 → #590902)
- ✅ Shadow 0 4px 15px
- ✅ Border-radius 50px
- ✅ Min-width 280px
- ✅ Badge negro circular
- ✅ Animación scale al click

---

## 🔧 JavaScript - Funcionalidades

### Archivo: `home.js` (283 líneas)

#### 1. Gestión de Carrito

```javascript
// Variable global
let cart = {};

// Funciones principales
addToCartFromData(element); // Agregar desde data attributes
addToCart(id, nombre, precio, stock); // Agregar al carrito
changeQuantity(id, delta); // Cambiar cantidad (+/-)
updateCartDisplay(); // Actualizar UI del carrito
goToCart(); // Sincronizar y redirigir
initCarrito(carritoServidor); // Cargar desde sesión
```

**Características:**

- ✅ Carrito en memoria (objeto JavaScript)
- ✅ Validación de stock en tiempo real
- ✅ Sincronización con backend vía fetch API
- ✅ Feedback visual de stock insuficiente
- ✅ Persistencia en sesión Laravel

#### 2. Buscador Inteligente

```javascript
initSearch()
- Búsqueda multi-palabra
- Filtrado en tiempo real
- Oculta categorías vacías
- Clear button animado
```

**Características:**

- ✅ Split de términos por espacios
- ✅ Búsqueda en nombre y descripción
- ✅ Case insensitive
- ✅ Muestra/oculta categorías dinámicamente

#### 3. Toggle de Categorías

```javascript
toggleCategory(header)
- Colapsa/expande contenido
- Rota icono chevron
- Transition suave
```

#### 4. Acceso Admin Discreto

```javascript
initAdminAccess()
- 5 clicks en logo → Caja Chica
- Timer de 2 segundos reset
- Acceso rápido para administradores
```

---

## 📦 Estructura de Archivos

### CSS

```
resources/css/
├── app.css (13 líneas)
│   ├── Bootstrap 5.3.8 import
│   ├── Bootstrap Icons import
│   └── TailwindCSS config
└── carrito.css

public/assets/css/
├── home.css (486 líneas) ⭐ Principal
├── main.css (2,340 bytes)
└── admin-dashboard.css (8,750 bytes)
```

### JavaScript

```
resources/js/
├── app.js (10 líneas)
│   ├── Bootstrap bundle import
│   └── Poppins fonts import (300-700)
├── bootstrap.js
└── carrito.js

public/assets/js/
├── home.js (283 líneas) ⭐ Principal
└── main.js (1,245 bytes)
```

### Vistas Blade

```
resources/views/
├── home.blade.php (189 líneas) ⭐ Vista principal
├── carrito/index.blade.php
├── layouts/main.blade.php
├── admin/ (13 archivos)
└── auth/ (2 archivos)
```

---

## 🎯 Características Principales

### 1. Mobile-First Design

```css
/* Base: Mobile (< 768px) */
- Layout vertical
- Touch-friendly (45px botones)
- Padding optimizado para móvil

/* Desktop (≥ 768px) */
@media (min-width: 768px) {
    body {
        max-width: 600px;
        margin: 0 auto;
    }
}
```

### 2. Animaciones y Transiciones

```css
/* Slide In */
@keyframes slideIn {
  from { transform: translateY(100px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}

/* Transitions */
- Botones: 0.2s all
- Categorías: 0.3s max-height
- Hover effects: 0.2s transform/shadow
```

### 3. Estados Interactivos

```css
/* Active States */
:active {
    transform: scale(0.95); /* Botones */
    transform: scale(0.9); /* Quantity controls */
}

/* Hover States */
:hover {
    background-color: rgba(212, 182, 138, 0.2);
}

/* Focus States */
:focus {
    box-shadow: 0 0 0 3px rgba(212, 182, 138, 0.2);
}
```

### 4. Validaciones Visuales

```javascript
// Stock insuficiente
qtyDisplay.style.color = "#dc3545";
qtyDisplay.textContent = "Max: " + stock;

// Timeout de 1.5s para volver a normal
setTimeout(() => {
    qtyDisplay.style.color = "";
    qtyDisplay.textContent = cantidad;
}, 1500);
```

---

## 🔄 Flujos de Usuario

### Flujo 1: Agregar al Carrito

```
1. Usuario ve plato
2. Click en botón "+" dorado
3. Botón "+" se oculta
4. Aparecen controles de cantidad
5. Cantidad inicial: 1
6. Carrito flotante aparece en bottom
7. Total se actualiza en tiempo real
```

### Flujo 2: Modificar Cantidad

```
1. Usuario click en "+" o "-"
2. Validación de stock
3. Si stock OK: actualiza cantidad
4. Si stock insuficiente: mensaje rojo "Max: X"
5. Actualiza total del carrito
6. Si cantidad = 0: vuelve a botón "+"
```

### Flujo 3: Ir al Carrito

```
1. Usuario click en carrito flotante
2. Opacity 0.5 (loading state)
3. Fetch POST a /carrito/sincronizar
4. Envía objeto cart completo
5. Backend valida y guarda en sesión
6. Redirect a /carrito
```

### Flujo 4: Búsqueda

```
1. Usuario escribe en search input
2. Split por espacios → palabras
3. Filtra platos en tiempo real
4. Oculta categorías sin resultados
5. Muestra clear icon
6. Click en clear → reset todo
```

---

## 🎨 Componentes Reutilizables

### Botón Circular

```css
.circular-btn {
    width: 35px-45px;
    height: 35px-45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
```

**Variantes:**

- `.add-btn` - Dorado (#D4B68A)
- `.quantity-btn` - Blanco con borde dorado
- `.cart-header-absolute` - Dorado 45px
- `.social-icons a` - Rojo (#D92534) 40px

### Card Container

```css
.card-container {
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}
```

**Uso:**

- `.category-section`
- Cards de admin
- Modales

### Gradiente Rojo

```css
background: linear-gradient(135deg, #d92534 0%, #590902 100%);
```

**Uso:**

- Headers de categorías
- Carrito flotante
- Botones principales

---

## 📊 Performance y Optimización

### Lazy Loading

```html
<img loading="lazy" src="..." alt="..." />
```

- ✅ Imágenes de platos cargadas bajo demanda
- ✅ Reduce tiempo de carga inicial

### CSS Optimizado

- ✅ Selectores específicos (bajo peso)
- ✅ Transitions solo en propiedades necesarias
- ✅ Sin !important innecesarios
- ✅ Mobile-first (menos overrides)

### JavaScript Eficiente

- ✅ Event delegation donde posible
- ✅ Debounce en búsqueda (implícito por input event)
- ✅ Cache de elementos DOM
- ✅ Validaciones tempranas (early return)

---

## 🔧 Build y Compilación

### Vite Configuration

**Archivo:** `vite.config.js`

```javascript
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
```

### Comandos

```bash
# Desarrollo (hot reload)
npm run dev

# Producción (minificado)
npm run build

# Preview
npm run preview
```

### Assets Compilados

```
public/build/
├── manifest.json
├── assets/
│   ├── app-[hash].css
│   └── app-[hash].js
```

---

## 🎯 Accesibilidad

### ARIA Labels

```html
<a aria-label="Instagram">...</a>
<a aria-label="WhatsApp">...</a>
<a aria-label="Mi Carrito">...</a>
```

### Contraste de Colores

- ✅ Texto oscuro (#333, #590902) sobre fondo claro
- ✅ Texto blanco sobre rojo (#D92534)
- ✅ Ratio de contraste WCAG AA compliant

### Navegación por Teclado

- ✅ Todos los botones son focusables
- ✅ Focus visible con outline
- ✅ Tab order lógico

---

## 📱 Responsive Design

### Breakpoints

```css
/* Mobile First */
Base: < 768px (diseño principal)

/* Tablet/Desktop */
@media (min-width: 768px) {
  - Max-width: 600px centrado
  - Mejor experiencia en pantallas grandes
}
```

### Touch Targets

```css
/* Mínimo 44x44px (Apple HIG) */
Botones: 35px-45px (aceptable para mobile)
Info items: 40px+ altura
Headers: 50px+ altura
```

---

## 🔍 SEO y Meta Tags

### Meta Tags Esenciales

```html
<meta charset="UTF-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
/>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Focaccia - Delivery</title>
```

### Fuentes Externas

```html
<!-- Google Fonts -->
<link
    href="https://fonts.googleapis.com/css2?family=Italiana&display=swap"
    rel="stylesheet"
/>
```

---

## 🎨 Identidad Visual

### Logo

- **Archivo:** `public/img/logo.png`
- **Tamaño:** 220x220px
- **Formato:** PNG circular
- **Zoom:** 1.1x para eliminar bordes

### Imágenes de Platos

- **Ubicación:** `public/assets/images/platos/`
- **Formato:** JPG, PNG, WEBP
- **Tamaño:** Variable (optimizado para 70x70px display)
- **Border-radius:** 10px

### Iconos

- **Librería:** Bootstrap Icons 1.13.1
- **Formato:** Icon font
- **Uso:** `<i class="bi bi-[nombre]"></i>`

**Iconos principales:**

- `bi-instagram` - Redes sociales
- `bi-whatsapp` - Redes sociales
- `bi-facebook` - Redes sociales
- `bi-cart-fill` - Carrito header
- `bi-cart3` - Carrito flotante
- `bi-search` - Buscador
- `bi-x-circle-fill` - Clear search
- `bi-chevron-up` - Toggle categorías
- `bi-geo-alt-fill` - Ubicación
- `bi-clock-fill` - Horarios
- `bi-bicycle` - Delivery
- `bi-credit-card-fill` - Pagos

---

## 📝 Convenciones de Código

### CSS

```css
/* Nomenclatura BEM-like */
.component-name { }
.component-name__element { }
.component-name--modifier { }

/* Orden de propiedades */
1. Positioning (position, top, left, z-index)
2. Box model (display, width, height, padding, margin)
3. Typography (font, color, text-align)
4. Visual (background, border, box-shadow)
5. Misc (cursor, transition, animation)
```

### JavaScript

```javascript
// Camel case para variables y funciones
let cartTotal = 0;
function updateCartDisplay() {}

// Pascal case para clases (no usadas en este proyecto)
class CartManager {}

// UPPER_CASE para constantes
const MAX_ITEMS = 99;
```

---

## 🚀 Próximas Mejoras Sugeridas

### Performance

1. Implementar Service Worker para PWA
2. Agregar cache de imágenes
3. Lazy load de categorías

### UX

1. Animación de agregado al carrito
2. Toast notifications
3. Skeleton loaders

### Accesibilidad

1. Mejorar ARIA labels
2. Agregar modo alto contraste
3. Soporte completo de teclado

---

**Última actualización:** 2026-01-20  
**Creado por:** Google Antigravity AI Agent

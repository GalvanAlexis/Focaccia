<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Focaccia - Delivery</title>

  <!-- Recursos locales vía Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Estilos externos -->
  <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Italiana&display=swap" rel="stylesheet">
</head>
<body>

<!-- Header Fijo -->
<header class="fixed-header">
  <!-- Redes Sociales - Solo Iconos -->
  <div class="social-icons">
    <a href="https://instagram.com/focacciapizzeria_ch" target="_blank" aria-label="Instagram">
      <i class="bi bi-instagram"></i>
    </a>
    <a href="https://wa.me/542241693947" target="_blank" aria-label="WhatsApp">
      <i class="bi bi-whatsapp"></i>
    </a>
    <a href="https://www.facebook.com/p/Focaccia-Pizeria-100023398373286/?locale=es_LA" target="_blank" aria-label="Facebook">
      <i class="bi bi-facebook"></i>
    </a>
  </div>

  <a href="javascript:void(0)" onclick="goToCart()" aria-label="Mi Carrito" class="cart-header-absolute">
    <i class="bi bi-cart-fill"></i>
  </a>

  <!-- Logo Circular y Título -->
  <div class="header-brand">
    <img src="{{ asset('img/logo.png') }}" alt="Focaccia" class="header-logo" id="adminLogo" data-caja-chica-url="{{ url('admin/caja-chica') }}">
  </div>

  <!-- Información del Local -->
  <div class="info-section">
    <a href="https://www.google.com/maps/search/?api=1&query=Bolivia+55,+Chascomus,+Buenos+Aires" target="_blank" class="info-item info-item-link">
      <i class="bi bi-geo-alt-fill"></i>
      <span>Bolivia 55, Chascomús</span>
    </a>
    <div class="info-item">
      <i class="bi bi-clock-fill"></i>
      <span class="hours-text">
        <span class="hours-part">Mar a Jue: 20-23 |</span>
        <span class="hours-part">Vie a Dom: 20-23:30 hs</span>
      </span>
    </div>
    <div class="info-item">
      <i class="bi bi-bicycle"></i>
      <span>Envíos a Domicilio</span>
    </div>
    <div class="info-item">
      <i class="bi bi-credit-card-fill"></i>
      <span>Transferencia y Efectivo</span>
    </div>
  </div>

  <!-- Frase Motivacional -->
  <div class="header-tagline-container" style="text-align: center; margin-top: 20px;">
    <h1 class="italian-title">Focaccia</h1>
    <p class="header-tagline">¡Deliciosa y con todo el sabor que solo vos sabes!</p>
  </div>
</header>

<!-- Buscador -->
<div class="search-container">
  <div class="search-box">
    <i class="bi bi-search search-icon"></i>
    <input type="text" id="searchInput" placeholder="Ingresá lo que estás buscando...">
    <i class="bi bi-x-circle-fill clear-icon" id="clearSearch"></i>
  </div>
</div>

<!-- Menú por Categorías -->
<div class="menu-container">
  <?php
  // Organizar platos por categoría
  $categorias = [
    'Bebidas' => [],
    'Empanadas' => [],
    'Pizzas' => [],
    'Tartas' => [],
    'Postres' => []
  ];

  if (!empty($platos)) {
    foreach ($platos as $plato) {
      $cat = $plato['categoria'] ?? 'Otros';
      if (isset($categorias[$cat])) {
        $categorias[$cat][] = $plato;
      }
    }
  }

  foreach ($categorias as $nombreCategoria => $platosCategoria):
    if (empty($platosCategoria)) continue;
  ?>

  <div class="category-section">
    <div class="category-header" onclick="toggleCategory(this)">
      <h2>{{ $nombreCategoria }}</h2>
      <i class="bi bi-chevron-up"></i>
    </div>

    <div class="category-content">
      @foreach($platosCategoria as $plato)
        <div class="plato-item" data-name="{{ strtolower($plato['nombre']) }}" data-desc="{{ strtolower($plato['descripcion']) }}">
          <div class="plato-image">
            @if(!empty($plato['imagen']))
              <?php
              // Detectar si es URL externa o archivo local
              $imagenUrl = (strpos($plato['imagen'], 'http') === 0)
                ? $plato['imagen']
                : asset('assets/images/platos/' . $plato['imagen']);
              ?>
              <img src="{{ $imagenUrl }}"
                   alt="{{ $plato['nombre'] }}"
                   loading="lazy"
                   style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
            @else
              <i class="bi bi-image"></i>
            @endif
          </div>

          <div class="plato-info">
            <div class="plato-name">{{ $plato['nombre'] }}</div>
            <div class="plato-description">{{ $plato['descripcion'] }}</div>
            <div class="plato-price">${{ number_format($plato['precio'], 0, ',', '.') }}</div>
          </div>

          <div class="add-btn"
               id="add-btn-{{ $plato['id'] }}"
               data-plato-id="{{ $plato['id'] }}"
               data-plato-nombre="{{ $plato['nombre'] }}"
               data-plato-precio="{{ $plato['precio'] }}"
               data-plato-stock="{{ $plato['stock'] ?? 999 }}"
               onclick="addToCartFromData(this)">
            +
          </div>

          <div class="quantity-controls" id="controls-{{ $plato['id'] }}" data-plato-id="{{ $plato['id'] }}" data-stock="{{ $plato['stock'] ?? 999 }}">
            <div class="quantity-btn" onclick="changeQuantity({{ $plato['id'] }}, -1)">-</div>
            <div class="quantity-display" id="qty-{{ $plato['id'] }}">0</div>
            <div class="quantity-btn" onclick="changeQuantity({{ $plato['id'] }}, 1)">+</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  @endforeach

  @if(empty($platos))
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <p>No hay platos disponibles en este momento</p>
    </div>
  @endif
</div>

<!-- Botón Flotante del Carrito -->
<div class="cart-float" onclick="goToCart()" id="cartFloat" style="display: none;" 
  data-carrito-url="{{ url('carrito') }}" 
  data-agregar-url="{{ url('carrito/agregar') }}"
  data-sincronizar-url="{{ url('carrito/sincronizar') }}">
  <i class="bi bi-cart3 cart-icon"></i>
  <span>Ver tu pedido</span>
  <div class="cart-badge" id="cartCount">0</div>
  <span class="cart-total" id="cartTotal">$0</span>
</div>


<script src="{{ asset('assets/js/home.js') }}"></script>
<script>
  // Inicializar carrito con datos del servidor
  const carritoServidor = {{ json_encode($carrito ?? []) }};
  initCarrito(carritoServidor);
</script>

</body>
</html>

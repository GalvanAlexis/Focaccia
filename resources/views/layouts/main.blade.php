<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Focaccia | Casa de Comidas & Delivery</title>

  <!-- Recursos locales vía Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Estilos externos -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  @yield('styles')
</head>

<body data-cart-count-url="{{ url('carrito/getCount') }}">

  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}" id="logo-link" data-caja-chica-url="{{ url('admin/caja-chica') }}">
        Focaccia
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          @if(auth()->check() && auth()->user()->hasRole('admin'))
          <li class="nav-item"><a class="nav-link" href="{{ url('admin/menu') }}">Gestión Menú</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('admin/pedidos') }}">Pedidos</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('admin/caja-chica') }}">Caja Chica</a></li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="nav-link btn btn-link text-decoration-none" style="border:none; background:none; padding:0.5rem 1rem;">Logout</button>
            </form>
          </li>
          @elseif(auth()->check() && auth()->user()->hasRole('vendedor'))
          <li class="nav-item"><a class="nav-link" href="{{ url('admin/menu') }}">Gestión Menú</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('admin/pedidos') }}">Pedidos</a></li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('carrito') }}">
              <i class="bi bi-cart3"></i> Carrito
              <span class="badge badge-cart" id="cart-count">0</span>
            </a>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="nav-link btn btn-link text-decoration-none" style="border:none; background:none; padding:0.5rem 1rem;">Logout</button>
            </form>
          </li>
          @else
          <li class="nav-item">
            <a class="nav-link" href="{{ url('carrito') }}">
              <i class="bi bi-cart3"></i> Carrito
              <span class="badge badge-cart" id="cart-count">0</span>
            </a>
          </li>
          @if(auth()->check())
          <li class="nav-item"><a class="nav-link" href="{{ url('pedido') }}">Mis Pedidos</a></li>
          <li class="nav-item position-relative">
            <a class="nav-link notification-bell" id="notificationBell" onclick="toggleNotifications()">
              <i class="bi bi-bell-fill"></i>
              <span class="notification-badge d-none" id="notificationCount">0</span>
            </a>
            <div class="notification-dropdown d-none" id="notificationDropdown">
              <div class="notification-header">
                <h6 class="mb-0 text-warning">Notificaciones</h6>
                <button class="btn btn-sm btn-link text-beige p-0" onclick="marcarTodasLeidas()">
                  Marcar todas como leídas
                </button>
              </div>
              <div id="notificationList">
                <div class="text-center text-muted p-3">
                  <i class="bi bi-inbox"></i> No hay notificaciones
                </div>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="nav-link btn btn-link text-decoration-none" style="border:none; background:none; padding:0.5rem 1rem;">Logout</button>
            </form>
          </li>
          @else
          <li class="nav-item"><a class="nav-link" href="{{ url('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ url('register') }}">Registrarse</a></li>
          @endif
          @endif
        </ul>
      </div>
    </div>
  </nav>

  <main>
    @yield('content')
  </main>

  <footer class="text-center mt-5">
    <div class="container">
      <p class="mb-1">© {{ date('Y') }} Focaccia | Pizzería & Delivery</p>
      <p class="small text-beige">Bolivia 55, Chascomús, Buenos Aires</p>
      <div class="mt-2">
        <a href="https://www.instagram.com/aido_agenciaweb/" target="_blank" class="text-warning me-3"><i class="bi bi-instagram"></i></a>
        <a href="https://www.facebook.com/p/Focaccia-Pizeria-100023398373286/?locale=es_LA" target="_blank" class="text-warning me-3"><i class="bi bi-facebook"></i></a>
        <a href="https://aidoagencia.com/" target="_blank" class="text-warning"><i class="bi bi-globe"></i></a>
      </div>
    </div>
  </footer>

  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
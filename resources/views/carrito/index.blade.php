<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Mi Carrito - Focaccia (TEST RENDER)</title>

  <!-- Google Fonts: Italiana & Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Recursos locales vía Vite -->
  @vite(['resources/css/carrito.css', 'resources/js/carrito.js'])

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background-color: #F2F2F2;
      padding-bottom: 80px;
      overflow-x: hidden;
    }

    /* Header */
    .cart-header {
      background: #F2F2F2;
      padding: 20px;
      text-align: center;
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .header-logo-text {
      font-family: 'Italiana', serif;
      font-size: 3rem;
      color: #D92534;
      text-decoration: none;
      font-weight: 700;
      letter-spacing: 1px;
      display: block;
      line-height: 1;
    }

    /* Items del Carrito */
    .cart-items {
      padding: 15px;
      padding-bottom: 200px;
    }

    .cart-item {
      background: #fff;
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .item-header {
      display: flex;
      justify-content: space-between;
      align-items: start;
      margin-bottom: 12px;
    }

    .item-name {
      font-weight: 600;
      font-size: 1.05rem;
      color: #333;
      flex: 1;
      padding-right: 10px;
    }

    .item-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: #D92534;
    }

    .item-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .quantity-controls-cart {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .qty-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid #D92534;
      background-color: #fff;
      color: #D92534;
      font-size: 1.2rem;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      user-select: none;
      transition: all 0.2s;
      font-weight: 700;
    }

    .qty-btn:active {
      transform: scale(0.9);
      background-color: #D92534;
      color: #fff;
    }

    .qty-display-cart {
      font-size: 1.2rem;
      font-weight: 600;
      min-width: 35px;
      text-align: center;
    }

    .item-subtotal {
      font-size: 1rem;
      font-weight: 600;
      color: #666;
    }

    .delete-btn {
      background: none;
      border: none;
      color: #dc3545;
      font-size: 1.3rem;
      cursor: pointer;
      padding: 5px;
      margin-left: 10px;
    }

    /* Resumen Total */
    .cart-summary {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #fff;
      border-top: 3px solid #D92534;
      padding: 15px;
      box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
    }

    .total-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .total-label {
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
    }

    .total-value {
      font-size: 1.4rem;
      font-weight: 700;
      color: #D92534;
    }

    .btn-update {
      background: #fff;
      color: #D92534;
      border: 2px solid #D92534;
      padding: 10px 20px;
      border-radius: 25px;
      font-size: 0.95rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .btn-update:active {
      transform: scale(0.95);
      background-color: #D92534;
      color: #fff;
    }

    .btn-update i {
      font-size: 1.1rem;
    }

    .total-update-row {
      display: grid;
      grid-template-columns: 1fr auto;
      grid-template-rows: auto auto;
      gap: 12px;
      margin-bottom: 15px;
    }

    .total-info {
      grid-column: 1 / 2;
      grid-row: 1 / 2;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .btn-update {
      grid-column: 2 / 3;
      grid-row: 1 / 2;
      align-self: center;
    }

    .btn-confirm-grid {
      grid-column: 1 / 3;
      grid-row: 2 / 3;
    }

    .btn-back-grid {
      grid-column: 1 / 3;
      grid-row: 3 / 4;
    }

    .btn-confirm,
    .btn-back {
      width: 100%;
      padding: 15px;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
      margin-bottom: 8px;
    }

    .btn-confirm {
      background: linear-gradient(135deg, #D92534 0%, #b31d2a 100%);
      color: #fff;
    }

    .btn-back {
      background: #fff;
      color: #590902;
      border: 2px solid #590902;
    }

    .btn-confirm:active,
    .btn-back:active {
      transform: scale(0.98);
    }

    /* Modal de confirmación de eliminación */
    .delete-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }

    .delete-modal.active {
      display: flex;
    }

    .delete-modal-content {
      background: #fff;
      border-radius: 16px;
      padding: 25px;
      max-width: 320px;
      width: 90%;
      text-align: center;
    }

    .delete-modal-icon {
      font-size: 3rem;
      color: #dc3545;
      margin-bottom: 15px;
    }

    .delete-modal-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 10px;
      color: #333;
    }

    .delete-modal-text {
      color: #666;
      margin-bottom: 20px;
    }

    .delete-modal-buttons {
      display: flex;
      gap: 10px;
    }

    .delete-modal-btn {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .delete-modal-btn:active {
      transform: scale(0.95);
    }

    .delete-modal-btn.cancel {
      background: #e0e0e0;
      color: #666;
    }

    .delete-modal-btn.confirm {
      background: #dc3545;
      color: #fff;
    }

    /* Modal de formulario de pedido */
    .order-modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 9998;
      overflow-y: auto;
      padding: 20px;
    }

    .order-modal.active {
      display: block;
    }

    .order-modal-content {
      background: #fff;
      border-radius: 16px;
      padding: 25px;
      max-width: 500px;
      margin: 20px auto;
    }

    .order-modal-header {
      text-align: center;
      margin-bottom: 20px;
    }

    .order-modal-logo {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #D92534;
      margin-bottom: 10px;
    }

    .order-modal-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #D4B68A;
      margin: 0;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-label {
      display: block;
      font-weight: 600;
      color: #333;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .form-input {
      width: 100%;
      padding: 12px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 1rem;
      transition: border-color 0.2s;
    }

    .form-input:focus {
      outline: none;
      border-color: #F2B05E;
    }

    textarea.form-input {
      resize: vertical;
      min-height: 80px;
    }

    .order-summary-box {
      background: #f9f9f9;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
    }

    .order-summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 0.95rem;
    }

    .order-summary-total {
      display: flex;
      justify-content: space-between;
      font-weight: 700;
      font-size: 1.2rem;
      color: #D4B68A;
      padding-top: 10px;
      border-top: 2px solid #e0e0e0;
      margin-top: 10px;
    }

    .btn-whatsapp {
      width: 100%;
      padding: 15px;
      background: #25D366;
      color: #fff;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 8px;
    }

    .btn-whatsapp:active {
      transform: scale(0.98);
    }

    .btn-retiro-local {
      background: #F2B05E;
      color: #000;
      border: none;
      padding: 12px 15px;
      border-radius: 8px;
      cursor: pointer;
      white-space: nowrap;
      font-weight: 500;
    }

    /* Estado Vacío */
    .empty-cart {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-cart i {
      font-size: 5rem;
      color: #ddd;
      margin-bottom: 20px;
    }

    .empty-cart h3 {
      color: #666;
      margin-bottom: 10px;
    }

    .empty-cart p {
      color: #999;
      margin-bottom: 25px;
    }

    /* Responsive */
    @media (min-width: 768px) {
      body {
        max-width: 600px;
        margin: 0 auto;
      }
    }

    /* Alerts */
    .alert-mobile {
      margin: 15px;
      border-radius: 12px;
      padding: 12px;
      font-size: 0.95rem;
    }

    /* Loading */
    .loading {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 10000;
      align-items: center;
      justify-content: center;
    }

    .loading.active {
      display: flex;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #D92534;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    /* Sistema de Notificaciones */
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      left: 20px;
      max-width: 400px;
      margin: 0 auto;
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      z-index: 10001;
      display: flex;
      align-items: center;
      gap: 12px;
      transform: translateY(-100px);
      opacity: 0;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .notification.show {
      transform: translateY(0);
      opacity: 1;
    }

    .notification.success {
      background: #d4edda;
      color: #155724;
      border-left: 4px solid #28a745;
    }

    .notification.error {
      background: #f8d7da;
      color: #721c24;
      border-left: 4px solid #dc3545;
    }

    .notification.warning {
      background: #fff3cd;
      color: #856404;
      border-left: 4px solid #ffc107;
    }

    .notification.info {
      background: #d1ecf1;
      color: #0c5460;
      border-left: 4px solid #17a2b8;
    }

    .notification i {
      font-size: 1.3rem;
    }
  </style>
</head>

<body>

  <!-- Loading Spinner -->
  <div class="loading" id="loading">
    <div class="spinner"></div>
  </div>


  <!-- Modal de formulario de pedido -->
  <div class="order-modal" id="orderModal">
    <div class="order-modal-content">
      <div class="order-modal-header">
        <!-- <img src="{{ asset('assets/images/logo.png') }}" alt="Focaccia" class="order-modal-logo"> -->
        <h2 class="order-modal-title" style="font-family: 'Italiana', serif; font-size: 2rem;">Focaccia</h2>
        <h3 class="order-modal-subtitle" style="font-size: 1.2rem; color: #333; margin-top: 5px;">Confirmar Pedido</h3>
      </div>

      <form id="orderForm" onsubmit="return false;">
        <div class="form-group">
          <label class="form-label">Nombre *</label>
          <input type="text" class="form-input" id="nombre" placeholder="Tu nombre" required>
        </div>

        <div class="form-group">
          <label class="form-label">Domicilio *</label>
          <div style="display: flex; gap: 10px; align-items: flex-start;">
            <input type="text" class="form-input" id="domicilio" placeholder="Tu dirección completa" required style="flex: 1;">
            <button type="button" class="btn-retiro-local" onclick="usarDireccionLocal()">
              Retiro por el local
            </button>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Entre Calles</label>
          <input type="text" class="form-input" id="entreCalles" placeholder="Entre qué calles (opcional)">
        </div>

        <div class="form-group">
          <label class="form-label">Comentarios Adicionales</label>
          <textarea class="form-input" id="comentarios" placeholder="Timbre, piso, apartamento, etc. (opcional)"></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Forma de Pago *</label>
          <select class="form-input" id="formaPago" required>
            <option value="">Seleccionar método de pago</option>
            <option value="efectivo">💵 Efectivo</option>
            <option value="mercado_pago">💳 Mercado Pago</option>
            <option value="transferencia">🏦 Transferencia</option>
            <option value="qr">📱 QR</option>
          </select>
        </div>

        <div class="order-summary-box">
          <div class="order-summary-item">
            <span>Subtotal:</span>
            <span id="modalSubtotal">${{ number_format($total ?? 0, 0, ',', '.') }}</span>
          </div>
          <div class="order-summary-total">
            <span>Total:</span>
            <span id="modalTotal">${{ number_format($total ?? 0, 0, ',', '.') }}</span>
          </div>
        </div>

        <button type="button" class="btn-whatsapp" onclick="enviarPorWhatsApp()">
          <i class="bi bi-whatsapp" style="font-size: 1.5rem;"></i>
          Enviar por WhatsApp
        </button>

        <button type="button" class="btn-back" onclick="closeOrderModal()">
          Cancelar
        </button>
      </form>
    </div>
  </div>

  <!-- Header -->
  <!-- Header -->
  <header class="cart-header">
    <a href="{{ url('/') }}" class="header-logo-text">Focaccia</a>
  </header>

  <!-- Alerts -->
  @if(session('success'))
  <div class="alert alert-success alert-mobile">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
  </div>
  @endif

  @if(session('error'))
  <div class="alert alert-danger alert-mobile">
    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
  </div>
  @endif

  <!-- Carrito Vacío -->
  @if(empty($carrito))
  <div class="empty-cart">
    <i class="bi bi-cart-x"></i>
    <h3>Tu carrito está vacío</h3>
    <p>Agrega productos desde el menú</p>
    <button onclick="window.location.href='{{ url('/') }}'" class="btn-confirm">
      Ver Menú
    </button>
  </div>

  <!-- Carrito con Productos -->
  @else
  <div class="cart-items">
    @php
    $total = 0;
    @endphp

    @foreach($carrito as $id => $item)
    @php
    $subtotal = $item['precio'] * $item['cantidad'];
    $total += $subtotal;

    $plato = \App\Models\Plato::find($id);
    $stockMax = ($plato && $plato->stock_ilimitado == 0) ? $plato->stock : 99;
    @endphp

    <div class="cart-item" id="item-{{ $id }}">
      <div class="item-header">
        <div class="item-name">{{ $item['nombre'] }}</div>
        <div class="item-price">${{ number_format($item['precio'], 0, ',', '.') }}</div>
      </div>

      <div class="item-controls">
        <div class="quantity-controls-cart">
          <form action="{{ route('carrito.actualizar') }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="plato_id" value="{{ $id }}">
            <input type="hidden" name="cantidad" value="{{ $item['cantidad'] - 1 }}">
            <button type="submit" class="qty-btn" {{ $item['cantidad'] <= 1 ? 'disabled' : '' }}>-</button>
          </form>

          <div class="qty-display-cart">{{ $item['cantidad'] }}</div>

          <form action="{{ route('carrito.actualizar') }}" method="POST" style="display:inline;">
            @csrf
            <input type="hidden" name="plato_id" value="{{ $id }}">
            <input type="hidden" name="cantidad" value="{{ $item['cantidad'] + 1 }}">
            <button type="submit" class="qty-btn" {{ $item['cantidad'] >= $stockMax ? 'disabled' : '' }}>+</button>
          </form>
        </div>

        <div class="d-flex align-items-center">
          <div class="item-subtotal">
            ${{ number_format($subtotal, 0, ',', '.') }}
          </div>

          <form action="{{ route('carrito.eliminar') }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar {{ $item['nombre'] }}?')">
            @csrf
            <input type="hidden" name="plato_id" value="{{ $id }}">
            <button type="submit" class="delete-btn">
              <i class="bi bi-trash"></i>
            </button>
          </form>
        </div>
      </div>
    </div>

    @endforeach
  </div>

  <!-- Resumen y Botones -->
  <div class="cart-summary">
    <div class="total-update-row">
      <div class="total-info">
        <div class="total-label">Total:</div>
        <div class="total-value" id="totalGeneral">${{ number_format($total, 0, ',', '.') }}</div>
      </div>

      <button onclick="location.reload()" class="btn-update" style="background-color: #666; color: white; border-color: #666;">
        <i class="bi bi-arrow-clockwise"></i>
        Refrescar
      </button>

      <button onclick="document.getElementById('orderModal').style.display='block'" class="btn-confirm btn-confirm-grid">
        <i class="bi bi-check-circle"></i> Confirmar tu Pedido
      </button>

      <button onclick="window.location.href='{{ url('/') }}'" class="btn-back btn-back-grid">
        <i class="bi bi-arrow-left"></i> Volver al Menú
      </button>
    </div>
  </div>

  @endif

  <script>
    const WHATSAPP_NUMBER = '542241517665';

    function closeOrderModal() {
      document.getElementById('orderModal').style.display = 'none';
    }

    function usarDireccionLocal() {
      document.getElementById('domicilio').value = 'Newbery 356, Buenos Aires (RETIRO POR EL LOCAL)';
      document.getElementById('entreCalles').value = '';
    }

    async function enviarPorWhatsApp() {
      const nombre = document.getElementById('nombre').value.trim();
      const domicilio = document.getElementById('domicilio').value.trim();
      const entreCalles = document.getElementById('entreCalles').value.trim();
      const comentarios = document.getElementById('comentarios').value.trim();
      const formaPago = document.getElementById('formaPago').value;

      if (!nombre || !domicilio || !formaPago) {
        alert('Por favor completa los campos obligatorios (*)');
        return;
      }

      document.getElementById('loading').classList.add('active');

      try {
        const formData = new FormData();
        formData.append('nombre_cliente', nombre);
        formData.append('tipo_entrega', 'delivery');
        formData.append('direccion', domicilio);
        formData.append('forma_pago', formaPago);
        formData.append('_token', '{{ csrf_token() }}');

        let notasCompletas = (entreCalles ? `Entre calles: ${entreCalles}. ` : '') + comentarios;
        formData.append('notas', notasCompletas);

        const response = await fetch('{{ route("carrito.finalizar") }}', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          // Generar mensaje de WhatsApp
          let mensaje = `*🍽️ NUEVO PEDIDO - FOCACCIA*\n\n`;
          mensaje += `👤 *Nombre:* ${nombre}\n`;
          mensaje += `📍 *Domicilio:* ${domicilio}\n`;
          mensaje += `💰 *Pago:* ${formaPago}\n\n`;
          mensaje += `*📋 Detalle del pedido:* En breves nos comunicamos para confirmar los platos.\n`;
          mensaje += `\n*💰 TOTAL DEL PEDIDO:* El total de su carrito se guardó correctamente.`;

          const urlWhatsApp = `https://wa.me/${WHATSAPP_NUMBER}?text=${encodeURIComponent(mensaje)}`;
          window.open(urlWhatsApp, '_blank');

          alert('¡Pedido enviado exitosamente!');
          window.location.href = '{{ url("/") }}';
        } else {
          alert(data.message || 'Error al guardar pedido');
        }
      } catch (e) {
        console.error(e);
        alert('Ocurrió un error al procesar el pedido.');
      } finally {
        document.getElementById('loading').classList.remove('active');
      }
    }
  </script>
</body>

</html>
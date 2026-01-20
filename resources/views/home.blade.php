<x-layouts.app title="Focaccia - Delivery">
  <div class="min-h-screen bg-gray-50 pb-24">

    <!-- Header Fijo -->
    @include('partials.header')

    <!-- Menú con Livewire -->
    <livewire:menu-list />

    <!-- Carrito Flotante -->
    <livewire:cart />

    <!-- Toasts -->
    <x-toast />

  </div>
</x-layouts.app>
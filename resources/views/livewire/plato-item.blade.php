<div x-data="{
        stockError: false,
        added: false
     }"
    @stock-error.window="if ($event.detail.platoId == {{ $plato->id }}) {
         stockError = true;
         setTimeout(() => stockError = false, 1500)
     }"
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
        <div class="flex items-center justify-center h-full text-gray-400">
            <i class="bi bi-image text-3xl"></i>
        </div>
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
                <span x-show="stockError" class="text-xs transition-all">Max: {{ $plato->stock }}</span>
            </div>

            <button wire:click="increment"
                class="w-9 h-9 rounded-full border-2 border-[#D4B68A] bg-white text-[#D4B68A] flex items-center justify-center text-xl hover:bg-[#D4B68A] hover:text-white active:scale-90 transition-all duration-200">
                +
            </button>
        </div>
        @endif
    </div>
</div>
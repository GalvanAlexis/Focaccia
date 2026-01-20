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
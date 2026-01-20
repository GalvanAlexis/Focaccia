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
            x-transition:enter-end="opacity-100 max-h-[2000px]"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 max-h-[2000px]"
            x-transition:leave-end="opacity-0 max-h-0"
            class="overflow-hidden">

            @foreach($platosCategoria as $plato)
            <livewire:plato-item :plato="$plato" :key="'plato-'.$plato->id" />
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center py-10 text-gray-400">
        <i class="bi bi-inbox text-6xl mb-4"></i>
        <p>No hay platos que coincidan con tu búsqueda</p>
    </div>
    @endforelse
</div>
</div>
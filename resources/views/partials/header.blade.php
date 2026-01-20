<header class="bg-[#efefef] p-4 shadow-md relative">

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
        <a href="https://www.facebook.com/p/Focaccia-Pizeria-100023398373286/?locale=es_LA"
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
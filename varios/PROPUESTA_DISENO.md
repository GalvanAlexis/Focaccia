# Propuesta de Modernización Visual - Focaccia 🚀

Podemos aprovechar **Tailwind CSS 4.0** y **Alpine.js** para transformar la web en algo mucho más impactante, manteniendo la esencia de la marca pero con un toque moderno y "neon".

Aquí tienes 3 opciones de dirección visual:

## Opción 1: "Modern Glow" (Elegancia Nocturna)

Ideal para resaltar los platos con un look premium.

- **Fondo:** Dark Mode profundo (`#0c0c0c`) o Neutro muy oscuro.
- **Efecto Neon:** Sombras difusas rojas (`shadow-[0_0_20px_rgba(217,37,52,0.4)]`) alrededor de los platos.
- **Vidrio (Glassmorphism):** El buscador y el carrito con fondo semitransparente y desenfoque (`backdrop-blur-md`).
- **Animaciones:** Los platos aparecen suavemente con un leve desplazamiento hacia arriba al hacer scroll.

## Opción 2: "Cyber Pizzeria" (Vibrante y Energético)

Un estilo más atrevido con colores que "saltan".

- **Bordes Animados:** Los botones de "Agregar" con un borde que brilla y recorre el perímetro.
- **Tipografía Neon:** El título "Focaccia" con un efecto de `text-shadow` que simula un tubo de neon.
- **Gradientes Líquidos:** En lugar de colores planos, usamos gradientes que se mueven suavemente en el header.
- **Hover Dinámico:** Al pasar el mouse, el plato se inclina levemente (`perspective`) y el brillo neon aumenta.

## Opción 3: "Minimal Neon" (Limpio pero Eléctrico)

Mantiene la claridad actual pero con acentos de luz.

- **Líneas de Carga:** Líneas neon rojas muy finas que separan las categorías.
- **Micro-interacciones:** Al agregar al carrito, un pulso de luz se expande desde el botón.
- **Tarjetas Flotantes:** Los items del menú tienen sombras muy sutiles que parecen estar suspendidas sobre el fondo gris claro.

---

### 🎨 ¿Qué podemos implementar de inmediato?

1. **Neon Borders**: Usar `ring` y `shadow` de Tailwind para que los botones y el carrito "brillen".
2. **Alpine.js Parallax/Tilt**: Que el logo o los platos tengan un leve movimiento con el mouse.
3. **Scroll Reveal**: Animaciones fluidas al navegar.
4. **Neon Typography**: Hacer que el título "Focaccia" en el header palpite como un neon real.

**¿Cuál de estos estilos te atrae más?** Puedo empezar aplicando un toque de **Neon Rojo** al carrito y al título para que veas el efecto.

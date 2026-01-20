<?php

$file = __DIR__ . '/resources/views/admin/pedidos/index.blade.php';
$content = file_get_contents($file);

// Buscar y reemplazar la sección de filtros
$oldFilters = <<<'HTML'
        <!-- Filtros -->
        <div class="filtros-container">
            <button class="filtro-btn active" data-filter="todos">
                Todos ({{ count($pedidos) }})
            </button>
            <button class="filtro-btn" data-filter="pendiente">
                🟡 Pendientes
            </button>

            <button class="filtro-btn" data-filter="completado">
                🟢 Completados
            </button>
            <button class="filtro-btn" data-filter="cancelado">
                🔴 Cancelados
            </button>
        </div>
HTML;

$newFilters = <<<'HTML'
        <!-- Filtros -->
        <div class="mb-3">
            <select class="form-select" id="filtroEstado" style="background: #2a2a2a; border: 2px solid #D4B68A; color: #D4B68A; font-weight: 600; padding: 12px; border-radius: 8px; font-size: 1rem;">
                <option value="todos" selected>📋 Todos los pedidos ({{ count($pedidos) }})</option>
                <option value="pendiente">🟡 Pendientes</option>
                <option value="completado">🟢 Completados</option>
                <option value="cancelado">🔴 Cancelados</option>
            </select>
        </div>
HTML;

$content = str_replace($oldFilters, $newFilters, $content);

// Buscar y reemplazar el JavaScript de filtros
$oldJS = <<<'JS'
// Filtrar pedidos
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const filtro = this.dataset.filter;

        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        document.querySelectorAll('.pedido-card').forEach(card => {
            if (filtro === 'todos' || card.dataset.estado === filtro) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
JS;

$newJS = <<<'JS'
// Filtrar pedidos con select
document.getElementById('filtroEstado').addEventListener('change', function() {
    const filtro = this.value;
    
    document.querySelectorAll('.pedido-card').forEach(card => {
        if (filtro === 'todos' || card.dataset.estado === filtro) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
JS;

$content = str_replace($oldJS, $newJS, $content);

file_put_contents($file, $content);

echo "✓ Filtros reemplazados por select dropdown\n";
echo "✓ JavaScript actualizado\n";

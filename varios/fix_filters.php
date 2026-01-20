<?php

$file = 'resources/views/admin/pedidos/index.blade.php';
$content = file_get_contents($file);

// Reemplazar los botones de filtro por un select
$pattern = '/<!-- Filtros -->.*?<\/div>\s*<!-- Buscador/s';
$replacement = <<<'HTML'
<!-- Filtros -->
        <div class="mb-3">
            <select class="form-select" id="filtroEstado" style="background: #2a2a2a; border: 2px solid #D4B68A; color: #D4B68A; font-weight: 600; padding: 12px; border-radius: 8px; font-size: 1rem;">
                <option value="todos" selected>📋 Todos los pedidos ({{ count($pedidos) }})</option>
                <option value="pendiente">🟡 Pendientes</option>
                <option value="completado">🟢 Completados</option>
                <option value="cancelado">🔴 Cancelados</option>
            </select>
        </div>

        <!-- Buscador
HTML;

$newContent = preg_replace($pattern, $replacement, $content);

if ($newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "✓ Select de filtros agregado\n";
} else {
    echo "✗ No se pudo encontrar la sección de filtros\n";
}

// Actualizar el JavaScript
$jsPattern = '/\/\/ Filtrar pedidos\s*document\.querySelectorAll\(\'\.filtro-btn\'\)\.forEach.*?\}\);/s';
$jsReplacement = <<<'JS'
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

$newContent2 = preg_replace($jsPattern, $jsReplacement, $newContent);

if ($newContent2 !== $newContent) {
    file_put_contents($file, $newContent2);
    echo "✓ JavaScript actualizado\n";
} else {
    echo "✗ No se pudo actualizar el JavaScript\n";
}

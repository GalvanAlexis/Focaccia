@extends('layouts.main')

@section('content')
<section class="py-4" style="background-color: #000; min-height: 80vh;">
    <div class="container">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div class="mb-3 mb-md-0">
                <h1 style="color: #D4B68A;" class="mb-1"><i class="bi bi-tags"></i> Gestión de Categorías</h1>
                <p class="text-light mb-0">Administra las categorías del menú</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ url('admin/menu') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver al Menú
                </a>
                <button type="button" class="btn btn-warning btn-sm" onclick="abrirModalCrear()">
                    <i class="bi bi-plus-circle"></i> Nueva Categoría
                </button>
            </div>
        </div>

        <!-- Alertas -->
        <div id="alertContainer"></div>

        <!-- Tabla de Categorías -->
        <div class="card bg-dark text-light">
            <div class="card-header" style="background-color: #1a1a1a; border-bottom: 2px solid #D4B68A;">
                <h5 class="mb-0" style="color: #D4B68A;"><i class="bi bi-list-ul"></i> Categorías Registradas</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover mb-0">
                        <thead style="background-color: #2a2a2a;">
                            <tr>
                                <th style="color: #D4B68A; width: 60px;">#</th>
                                <th style="color: #D4B68A;">Nombre</th>
                                <th style="color: #D4B68A; width: 100px;">Orden</th>
                                <th style="color: #D4B68A; width: 100px;">Estado</th>
                                <th style="color: #D4B68A; width: 120px;">Platos</th>
                                <th class="text-center" style="color: #D4B68A; width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCategorias">
                            @if(empty($categorias))
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-light" style="opacity: 0.7;">
                                        <i class="bi bi-inbox"></i> No hay categorías registradas
                                    </td>
                                </tr>
                            @else
                                @foreach($categorias as $cat)
                                    <tr data-categoria-id="{{ $cat['id'] }}">
                                        <td class="text-light">{{ $cat['id'] }}</td>
                                        <td class="fw-bold text-light">{{ $cat['nombre'] }}</td>
                                        <td class="text-light">{{ $cat['orden'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $cat['activa'] ? 'success' : 'secondary' }}">
                                                {{ $cat['activa'] ? 'Activa' : 'Inactiva' }}
                                            </span>
                                        </td>
                                        <td class="text-light">
                                            <i class="bi bi-basket"></i> {{ $cat['total_platos'] }}
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-warning me-1 btn-editar"
                                                    data-id="{{ $cat['id'] }}"
                                                    data-nombre="{{ $cat['nombre'] }}"
                                                    data-orden="{{ $cat['orden'] }}"
                                                    title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-{{ $cat['activa'] ? 'secondary' : 'success' }} me-1"
                                                    onclick="toggleEstado({{ $cat['id'] }}, {{ $cat['activa'] }})"
                                                    title="{{ $cat['activa'] ? 'Desactivar' : 'Activar' }}">
                                                <i class="bi bi-{{ $cat['activa'] ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                            @if($cat['total_platos'] == 0)
                                                <button class="btn btn-sm btn-outline-danger"
                                                        onclick="prepararEliminar({{ $cat['id'] }}, '{{ $cat['nombre'] }}')"
                                                        title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Crear Categoría -->
<div class="modal fade" id="modalCrearCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle"></i> Nueva Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCrearCategoria">
                    <div class="mb-3">
                        <label for="nombreNuevaCategoria" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="nombreNuevaCategoria" required>
                    </div>
                    <div class="mb-3">
                        <label for="ordenNuevaCategoria" class="form-label">Orden (opcional)</label>
                        <input type="number" class="form-control" id="ordenNuevaCategoria" value="0" min="0">
                        <small class="text-light" style="opacity: 0.7;">Determina el orden de aparición en el menú</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="crearCategoria()">
                    <i class="bi bi-check-lg"></i> Crear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header" style="background-color: #D4B68A; color: #000;">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil"></i> Editar Categoría</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarCategoria">
                    <input type="hidden" id="editarCategoriaId">
                    <div class="mb-3">
                        <label for="editarNombre" class="form-label">Nombre de la Categoría</label>
                        <input type="text" class="form-control" id="editarNombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="editarOrden" class="form-label">Orden</label>
                        <input type="number" class="form-control" id="editarOrden" min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="actualizarCategoria()">
                    <i class="bi bi-check-lg"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal Eliminar Categoría -->
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-light" style="border: 1px solid #dc3545;">
            <div class="modal-header" style="background-color: #dc3545; color: #fff;">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-trash text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3 fs-5" id="mensajeEliminar">¿Estás seguro de que deseas eliminar esta categoría?</p>
                <p class="text-light small" style="opacity: 0.7;">Esta acción es irreversible.</p>
                <input type="hidden" id="eliminarCategoriaId">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger px-4" onclick="confirmarEliminar()">
                    <i class="bi bi-trash-fill"></i> Eliminar Definitivamente
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Obtener CSRF Token
// Obtener CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Helper para obtener instancia de Modal segura
function getModalInstance(id) {
    const el = document.getElementById(id);
    if (!el) return null;
    
    // Check if bootstrap is defined
    if (typeof bootstrap === 'undefined') {
        alert('Error crítico: Bootstrap no se ha cargado correctamente. Intenta recargar la página (Ctrl+F5).');
        return null;
    }
    
    return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
}

function abrirModalCrear() {
    const modal = getModalInstance('modalCrearCategoria');
    if(modal) modal.show();
}

function mostrarAlerta(mensaje, tipo = 'success') {
    const alertHtml = `
        <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
            <i class="bi bi-${tipo === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.getElementById('alertContainer').innerHTML = alertHtml;
    // Auto-ocultar después de 3 segundos
    setTimeout(() => {
        const alertElement = document.getElementById('alertContainer').querySelector('.alert');
        if (alertElement) {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }
    }, 3000);
}

function crearCategoria() {
    const nombre = document.getElementById('nombreNuevaCategoria').value.trim();
    const orden = document.getElementById('ordenNuevaCategoria').value;

    if (!nombre) {
        mostrarAlerta('El nombre es obligatorio', 'danger');
        return;
    }

    fetch('{{ url('admin/categorias/crear') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: new URLSearchParams({
            nombre: nombre,
            orden: orden
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            // Manejar errores de validación (422) u otros
            throw new Error(data.message || 'Error en la petición');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            const modal = getModalInstance('modalCrearCategoria');
            if(modal) modal.hide();
            document.getElementById('formCrearCategoria').reset();
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta(error.message || 'Error al crear la categoría', 'danger');
        console.error(error);
    });
}

// Event delegation robusto para botones de editar
document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-editar');
        if (btn) {
            const id = btn.dataset.id;
            const nombre = btn.dataset.nombre;
            const orden = btn.dataset.orden;
            
            document.getElementById('editarCategoriaId').value = id;
            document.getElementById('editarNombre').value = nombre;
            document.getElementById('editarOrden').value = orden;
            
            document.getElementById('editarOrden').value = orden;
            
            const modal = getModalInstance('modalEditarCategoria');
            if(modal) modal.show();
        }
    });
});

function actualizarCategoria() {
    const id = document.getElementById('editarCategoriaId').value;
    const nombre = document.getElementById('editarNombre').value.trim();
    const orden = document.getElementById('editarOrden').value;

    if (!nombre) {
        mostrarAlerta('El nombre es obligatorio', 'danger');
        return;
    }

    fetch(`{{ url('admin/categorias/actualizar') }}/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: new URLSearchParams({
            nombre: nombre,
            orden: orden
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error en la actualización');
        return data;
    })
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            const modal = getModalInstance('modalEditarCategoria');
            if(modal) modal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta(error.message || 'Error al actualizar la categoría', 'danger');
        console.error(error);
    });
}

function toggleEstado(id, estadoActual) {
    const nuevoEstado = estadoActual ? 0 : 1;

    // Solo enviamos "activa", el controlador ya no valida "nombre" si no está presente
    fetch(`{{ url('admin/categorias/actualizar') }}/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: new URLSearchParams({
            activa: nuevoEstado
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error al cambiar estado');
        return data;
    })
    .then(data => {
        if (data.success) {
            mostrarAlerta(data.message, 'success');
            setTimeout(() => location.reload(), 500);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta(error.message || 'Error al cambiar el estado', 'danger');
        console.error(error);
    });
}

function prepararEliminar(id, nombre) {
    document.getElementById('eliminarCategoriaId').value = id;
    document.getElementById('mensajeEliminar').textContent = `¿Estás seguro de que deseas eliminar la categoría "${nombre}"?`;
    const modal = getModalInstance('modalEliminarCategoria');
    if(modal) modal.show();
}

function confirmarEliminar() {
    const id = document.getElementById('eliminarCategoriaId').value;
    const modal = getModalInstance('modalEliminarCategoria');

    fetch(`{{ url('admin/categorias/eliminar') }}/${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error al eliminar');
        return data;
    })
    .then(data => {
        if (data.success) {
            if(modal) modal.hide();
            mostrarAlerta(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarAlerta(data.message, 'danger');
        }
    })
    .catch(error => {
        mostrarAlerta(error.message || 'Error al eliminar la categoría', 'danger');
        console.error(error);
    });
}
</script>

@endsection

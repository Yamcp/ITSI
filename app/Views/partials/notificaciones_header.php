<!-- Componente de Notificaciones para el Header -->
<div class="dropdown">
    <button class="btn btn-link text-decoration-none position-relative" type="button" id="dropdownNotificaciones" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-lg"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="contadorNotificaciones" style="display: none;">
            0
        </span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end p-0" style="width: 350px; max-height: 400px; overflow-y: auto;" aria-labelledby="dropdownNotificaciones">
        <li class="dropdown-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-bell me-2"></i>Notificaciones</span>
            <button class="btn btn-sm btn-outline-primary" onclick="marcarTodasLeidasHeader()" id="btnMarcarTodas" style="display: none;">
                <i class="fas fa-check-double"></i>
            </button>
        </li>
        <li><hr class="dropdown-divider"></li>
        <div id="listaNotificacionesHeader">
            <li class="dropdown-item text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 mb-0">Cargando notificaciones...</p>
            </li>
        </div>
        <li><hr class="dropdown-divider"></li>
        <li class="dropdown-item text-center">
            <a href="<?= base_url(session()->get('rol') == 2 ? 'docente' : 'estudiante') ?>/notificaciones" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye me-1"></i>Ver Todas
            </a>
        </li>
    </ul>
</div>

<script>
// Variables globales para notificaciones
let notificacionesCargadas = false;
let intervaloNotificaciones = null;

// Cargar notificaciones al abrir el dropdown
document.getElementById('dropdownNotificaciones').addEventListener('show.bs.dropdown', function() {
    if (!notificacionesCargadas) {
        cargarNotificacionesHeader();
    }
});

// Cargar notificaciones en el header
function cargarNotificacionesHeader() {
    fetch('/notificaciones/no-leidas')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarNotificacionesHeader(data.data.notificaciones);
                actualizarContadorHeader(data.data.contador);
                notificacionesCargadas = true;
            } else {
                mostrarErrorNotificaciones();
            }
        })
        .catch(error => {
            console.error('Error cargando notificaciones:', error);
            mostrarErrorNotificaciones();
        });
}

// Mostrar notificaciones en el header
function mostrarNotificacionesHeader(notificaciones) {
    const lista = document.getElementById('listaNotificacionesHeader');
    const btnMarcarTodas = document.getElementById('btnMarcarTodas');
    
    if (notificaciones.length === 0) {
        lista.innerHTML = `
            <li class="dropdown-item text-center py-4">
                <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                <p class="mb-0 text-muted">No tienes notificaciones nuevas</p>
            </li>
        `;
        btnMarcarTodas.style.display = 'none';
    } else {
        let html = '';
        notificaciones.slice(0, 5).forEach(notificacion => {
            const icono = obtenerIconoNotificacion(notificacion.TIPO_NOTIFICACION);
            const tiempo = obtenerTiempoTranscurrido(notificacion.FECHA_CREACION);
            
            html += `
                <li class="dropdown-item notification-item-header" data-id="${notificacion.ID_NOTIFICACION}" onclick="marcarLeidaHeader(${notificacion.ID_NOTIFICACION})">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="${icono} text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">${notificacion.TITULO}</h6>
                            <p class="mb-1 text-muted small">${notificacion.MENSAJE.substring(0, 100)}${notificacion.MENSAJE.length > 100 ? '...' : ''}</p>
                            <small class="text-muted">${tiempo}</small>
                        </div>
                        <div class="ms-2">
                            <span class="badge bg-primary rounded-pill priority-${notificacion.PRIORIDAD}"></span>
                        </div>
                    </div>
                </li>
            `;
        });
        
        if (notificaciones.length > 5) {
            html += `
                <li class="dropdown-item text-center">
                    <small class="text-muted">Y ${notificaciones.length - 5} notificaciones más...</small>
                </li>
            `;
        }
        
        lista.innerHTML = html;
        btnMarcarTodas.style.display = 'inline-block';
    }
}

// Mostrar error al cargar notificaciones
function mostrarErrorNotificaciones() {
    const lista = document.getElementById('listaNotificacionesHeader');
    lista.innerHTML = `
        <li class="dropdown-item text-center py-4">
            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
            <p class="mb-0 text-muted">Error al cargar notificaciones</p>
        </li>
    `;
}

// Obtener icono según el tipo de notificación
function obtenerIconoNotificacion(tipo) {
    const iconos = {
        'asignacion_practica': 'fas fa-briefcase',
        'tutoria_asignada': 'fas fa-chalkboard-teacher',
        'recordatorio': 'fas fa-clock',
        'general': 'fas fa-info-circle'
    };
    return iconos[tipo] || 'fas fa-bell';
}

// Obtener tiempo transcurrido
function obtenerTiempoTranscurrido(fecha) {
    const ahora = new Date();
    const fechaNotificacion = new Date(fecha);
    const diffMs = ahora - fechaNotificacion;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Ahora mismo';
    if (diffMins < 60) return `Hace ${diffMins} min`;
    if (diffHours < 24) return `Hace ${diffHours}h`;
    if (diffDays < 7) return `Hace ${diffDays} días`;
    return fechaNotificacion.toLocaleDateString();
}

// Marcar notificación como leída desde el header
function marcarLeidaHeader(idNotificacion) {
    fetch(`/notificaciones/marcar-leida/${idNotificacion}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remover la notificación de la lista
            const elemento = document.querySelector(`[data-id="${idNotificacion}"]`);
            if (elemento) {
                elemento.remove();
            }
            
            // Actualizar contador
            actualizarContadorHeader();
            
            // Recargar notificaciones si no quedan
            const lista = document.getElementById('listaNotificacionesHeader');
            if (lista.children.length === 0) {
                cargarNotificacionesHeader();
            }
        }
    })
    .catch(error => {
        console.error('Error marcando notificación:', error);
    });
}

// Marcar todas las notificaciones como leídas desde el header
function marcarTodasLeidasHeader() {
    fetch('/notificaciones/marcar-todas-leidas', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Limpiar lista y mostrar mensaje
            const lista = document.getElementById('listaNotificacionesHeader');
            lista.innerHTML = `
                <li class="dropdown-item text-center py-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="mb-0 text-muted">Todas las notificaciones han sido marcadas como leídas</p>
                </li>
            `;
            
            // Ocultar botón
            document.getElementById('btnMarcarTodas').style.display = 'none';
            
            // Actualizar contador
            actualizarContadorHeader();
        }
    })
    .catch(error => {
        console.error('Error marcando notificaciones:', error);
    });
}

// Actualizar contador de notificaciones
function actualizarContadorHeader() {
    fetch('/notificaciones/contador')
        .then(response => response.json())
        .then(data => {
            const contador = document.getElementById('contadorNotificaciones');
            if (data.success && data.contador > 0) {
                contador.textContent = data.contador;
                contador.style.display = 'inline-block';
            } else {
                contador.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error actualizando contador:', error);
        });
}

// Inicializar contador al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarContadorHeader();
    
    // Actualizar contador cada 30 segundos
    intervaloNotificaciones = setInterval(actualizarContadorHeader, 30000);
});

// Limpiar intervalo al salir de la página
window.addEventListener('beforeunload', function() {
    if (intervaloNotificaciones) {
        clearInterval(intervaloNotificaciones);
    }
});
</script>

<style>
.notification-item-header {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.notification-item-header:hover {
    background-color: #f8f9fa;
}

.priority-alta {
    background-color: #dc3545 !important;
}

.priority-media {
    background-color: #ffc107 !important;
}

.priority-baja {
    background-color: #28a745 !important;
}

#contadorNotificaciones {
    font-size: 0.7rem;
    min-width: 18px;
    height: 18px;
    line-height: 18px;
}
</style>

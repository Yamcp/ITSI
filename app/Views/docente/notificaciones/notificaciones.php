<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para notificaciones -->
<style>
    .notification-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .notification-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .notification-item.unread {
        border-left-color: #28a745;
        background-color: #f8fff8;
    }

    .notification-item.read {
        border-left-color: #6c757d;
        background-color: #f8f9fa;
    }

    .notification-priority {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .priority-alta {
        background-color: #dc3545;
    }

    .priority-media {
        background-color: #ffc107;
    }

    .priority-baja {
        background-color: #28a745;
    }

    .notification-type {
        font-size: 0.8rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 500;
    }

    .type-asignacion_practica {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .type-tutoria_asignada {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }

    .type-recordatorio {
        background-color: #fff3e0;
        color: #f57c00;
    }

    .type-general {
        background-color: #e8f5e8;
        color: #388e3c;
    }

    .notification-actions {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .notification-item:hover .notification-actions {
        opacity: 1;
    }

    .stats-card {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .filter-buttons .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .tutoria-badge {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Mis Notificaciones de Tutoría
                </h3>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><?= $estadisticas['total'] ?></h4>
                            <p class="mb-0">Total Notificaciones</p>
                        </div>
                        <i class="fas fa-bell fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><?= $estadisticas['no_leidas'] ?></h4>
                            <p class="mb-0">Pendientes</p>
                        </div>
                        <i class="fas fa-exclamation-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><?= $estadisticas['leidas'] ?></h4>
                            <p class="mb-0">Revisadas</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros y Acciones -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="filter-buttons">
                    <button class="btn btn-outline-success active" data-filter="todas">
                        <i class="fas fa-list me-1"></i>Todas
                    </button>
                    <button class="btn btn-outline-warning" data-filter="no_leidas">
                        <i class="fas fa-exclamation-circle me-1"></i>Pendientes
                    </button>
                    <button class="btn btn-outline-primary" data-filter="tutoria_asignada">
                        <i class="fas fa-chalkboard-teacher me-1"></i>Tutorías
                    </button>
                    <button class="btn btn-outline-info" data-filter="asignacion_practica">
                        <i class="fas fa-briefcase me-1"></i>Prácticas
                    </button>
                    <button class="btn btn-outline-secondary" data-filter="recordatorio">
                        <i class="fas fa-clock me-1"></i>Recordatorios
                    </button>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success" onclick="marcarTodasLeidas()">
                    <i class="fas fa-check-double me-1"></i>Marcar Todas Revisadas
                </button>
            </div>
        </div>

        <!-- Lista de Notificaciones -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <?php if (!empty($notificaciones)): ?>
                            <div id="notificacionesLista">
                                <?php foreach ($notificaciones as $notificacion): ?>
                                    <div class="notification-item p-3 border-bottom position-relative <?= $notificacion['LEIDA'] ? 'read' : 'unread' ?>"
                                        data-id="<?= $notificacion['ID_NOTIFICACION'] ?>"
                                        data-tipo="<?= $notificacion['TIPO_NOTIFICACION'] ?>"
                                        data-leida="<?= $notificacion['LEIDA'] ?>">

                                        <!-- Indicador de prioridad -->
                                        <div class="notification-priority priority-<?= $notificacion['PRIORIDAD'] ?>"></div>

                                        <div class="row">
                                            <div class="col-md-1">
                                                <div class="text-center">
                                                    <?php if ($notificacion['TIPO_NOTIFICACION'] == 'tutoria_asignada'): ?>
                                                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                                                    <?php elseif ($notificacion['TIPO_NOTIFICACION'] == 'asignacion_practica'): ?>
                                                        <i class="fas fa-briefcase fa-2x text-primary"></i>
                                                    <?php elseif ($notificacion['TIPO_NOTIFICACION'] == 'recordatorio'): ?>
                                                        <i class="fas fa-clock fa-2x text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-info-circle fa-2x text-info"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="mb-1 fw-bold"><?= $notificacion['TITULO'] ?></h6>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <?php if ($notificacion['TIPO_NOTIFICACION'] == 'tutoria_asignada'): ?>
                                                            <span class="tutoria-badge">
                                                                <i class="fas fa-graduation-cap me-1"></i>Nueva Tutoria
                                                            </span>
                                                        <?php endif; ?>
                                                        <div class="notification-type type-<?= $notificacion['TIPO_NOTIFICACION'] ?>">
                                                            <?= ucfirst(str_replace('_', ' ', $notificacion['TIPO_NOTIFICACION'])) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="mb-2 text-muted"><?= nl2br($notificacion['MENSAJE']) ?></p>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    <?= date('d/m/Y H:i', strtotime($notificacion['FECHA_CREACION'])) ?>
                                                    <?php if ($notificacion['LEIDA'] && $notificacion['FECHA_LEIDA']): ?>
                                                        | <i class="fas fa-check me-1"></i>Revisada: <?= date('d/m/Y H:i', strtotime($notificacion['FECHA_LEIDA'])) ?>
                                                    <?php endif; ?>
                                                </small>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="notification-actions text-end">
                                                    <?php if (!$notificacion['LEIDA']): ?>
                                                        <button class="btn btn-sm btn-outline-success" onclick="marcarLeida(<?= $notificacion['ID_NOTIFICACION'] ?>)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="eliminarNotificacion(<?= $notificacion['ID_NOTIFICACION'] ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <h4>No tienes notificaciones de tutoría</h4>
                                <p>Cuando seas asignado como tutor de una práctica, recibirás notificaciones aquí.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let notificaciones = <?= json_encode($notificaciones) ?>;

    // Funciones principales
    function marcarLeida(idNotificacion) {
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
                    // Actualizar la interfaz
                    const elemento = document.querySelector(`[data-id="${idNotificacion}"]`);
                    if (elemento) {
                        elemento.classList.remove('unread');
                        elemento.classList.add('read');
                        elemento.setAttribute('data-leida', '1');

                        // Ocultar botón de marcar como leída
                        const btnMarcar = elemento.querySelector('.notification-actions .btn-outline-success');
                        if (btnMarcar) {
                            btnMarcar.remove();
                        }

                        // Actualizar contador
                        actualizarContador();
                    }

                    showNotification('Notificación marcada como revisada', 'success');
                } else {
                    showNotification('Error al marcar la notificación', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
            });
    }

    function marcarTodasLeidas() {
        if (confirm('¿Estás seguro de que quieres marcar todas las notificaciones como revisadas?')) {
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
                        // Actualizar todas las notificaciones no leídas
                        document.querySelectorAll('.notification-item.unread').forEach(elemento => {
                            elemento.classList.remove('unread');
                            elemento.classList.add('read');
                            elemento.setAttribute('data-leida', '1');

                            // Ocultar botones de marcar como leída
                            const btnMarcar = elemento.querySelector('.notification-actions .btn-outline-success');
                            if (btnMarcar) {
                                btnMarcar.remove();
                            }
                        });

                        // Actualizar contador
                        actualizarContador();

                        showNotification('Todas las notificaciones han sido marcadas como revisadas', 'success');
                    } else {
                        showNotification('Error al marcar las notificaciones', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error de conexión', 'error');
                });
        }
    }

    function eliminarNotificacion(idNotificacion) {
        if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
            fetch(`/notificaciones/eliminar/${idNotificacion}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remover elemento de la interfaz
                        const elemento = document.querySelector(`[data-id="${idNotificacion}"]`);
                        if (elemento) {
                            elemento.remove();
                        }

                        // Actualizar contador
                        actualizarContador();

                        showNotification('Notificación eliminada', 'success');
                    } else {
                        showNotification('Error al eliminar la notificación', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error de conexión', 'error');
                });
        }
    }

    function filtrarNotificaciones(filtro) {
        const elementos = document.querySelectorAll('.notification-item');

        elementos.forEach(elemento => {
            let mostrar = true;

            switch (filtro) {
                case 'no_leidas':
                    mostrar = elemento.getAttribute('data-leida') === '0';
                    break;
                case 'tutoria_asignada':
                case 'asignacion_practica':
                case 'recordatorio':
                case 'general':
                    mostrar = elemento.getAttribute('data-tipo') === filtro;
                    break;
                case 'todas':
                default:
                    mostrar = true;
                    break;
            }

            elemento.style.display = mostrar ? 'block' : 'none';
        });

        // Actualizar botones de filtro
        document.querySelectorAll('.filter-buttons .btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-filter="${filtro}"]`).classList.add('active');
    }

    function actualizarContador() {
        const noLeidas = document.querySelectorAll('.notification-item[data-leida="0"]').length;
        const total = document.querySelectorAll('.notification-item').length;

        // Actualizar estadísticas en la página
        const statsNoLeidas = document.querySelector('.stats-card:nth-child(2) h4');
        const statsLeidas = document.querySelector('.stats-card:nth-child(3) h4');
        const statsTotal = document.querySelector('.stats-card:nth-child(1) h4');

        if (statsNoLeidas) statsNoLeidas.textContent = noLeidas;
        if (statsLeidas) statsLeidas.textContent = total - noLeidas;
        if (statsTotal) statsTotal.textContent = total;
    }

    function showNotification(message, type = 'info') {
        const colors = {
            success: '#27ae60',
            error: '#e74c3c',
            warning: '#f39c12',
            info: '#3498db'
        };

        const notification = document.createElement('div');
        notification.className = 'position-fixed top-0 end-0 m-3';
        notification.style.zIndex = '9999';
        notification.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="background: ${colors[type]}; color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Filtros
        document.querySelectorAll('.filter-buttons .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filtro = this.getAttribute('data-filter');
                filtrarNotificaciones(filtro);
            });
        });

        // Auto-actualizar contador cada 30 segundos
        setInterval(actualizarContador, 30000);
    });
</script>
<?= $this->endSection() ?>
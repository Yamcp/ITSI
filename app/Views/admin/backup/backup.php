<!-- app/Views/admin/backup/backup.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<style>
    .backup-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .stats-card {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .action-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .action-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .backup-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .backup-table .table {
        margin-bottom: 0;
    }
    
    .backup-table .table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        font-weight: 600;
        padding: 1rem;
    }
    
    .backup-table .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .backup-table .table tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: scale(1.01);
    }
    
    .btn-modern {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .badge-modern {
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #dee2e6;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header del Backup -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-database me-2"></i>
                    Gestión de Backups
                </h3>
            </div>
        </div>

        
        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="showModal('modalNuevoBackup')">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-plus-circle fa-3x mb-3 text-primary"></i>
                        <div class="fw-bold text-primary">Generar Backup</div>
                        <small class="text-muted">Crear nuevo respaldo del sistema</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="showModal('modalConfiguracion')">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-cog fa-3x mb-3 text-warning"></i>
                        <div class="fw-bold text-warning">Configuración</div>
                        <small class="text-muted">Ajustar parámetros de backup</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="exportarHistorial()">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-download fa-3x mb-3 text-success"></i>
                        <div class="fw-bold text-success">Exportar Historial</div>
                        <small class="text-muted">Descargar registro de backups</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Backups -->
        <div class="row">
            <div class="col-12">
                <div class="card backup-table">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-history me-2"></i>
                            Historial de Backups
                        </span>
                        <button class="btn btn-light btn-sm" onclick="showModal('modalFiltros')">
                            <i class="fas fa-filter me-1"></i>Filtros
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($exportaciones)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Usuario</th>
                                            <th>Fecha y Hora</th>
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                            <th>Tamaño</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($exportaciones as $index => $backup): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge bg-secondary"><?= $backup['ID_EXPORTACION'] ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($backup['NOMBRE'] ?? 'Usuario') ?>+<?= urlencode($backup['APELLIDO'] ?? 'Sistema') ?>&background=0d6efd&color=fff&size=32" 
                                                             class="rounded-circle me-2" alt="Usuario">
                                                        <div>
                                                            <div class="fw-semibold">
                                                                <?php 
                                                                if (isset($backup['NOMBRE']) && isset($backup['APELLIDO'])) {
                                                                    echo $backup['NOMBRE'] . ' ' . $backup['APELLIDO'];
                                                                } else {
                                                                    echo 'Usuario ID: ' . $backup['ID_USUARIO'];
                                                                }
                                                                ?>
                                                            </div>
                                                            <small class="text-muted"><?= $backup['USUARIO'] ?? 'Sistema' ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($backup['FECHA_EXPORTACION'])) ?></div>
                                                    <small class="text-muted"><?= date('H:i:s', strtotime($backup['FECHA_EXPORTACION'])) ?></small>
                                                </td>
                                                <td>
                                                    <div class="fw-semibold"><?= $backup['DESCRIPCION_EXPORTACION'] ?? 'Backup del sistema' ?></div>
                                                    <small class="text-muted">Respaldo automático</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-modern bg-success text-white">Completado</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">2.5 MB</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-success btn-modern" 
                                                                onclick="descargarBackup(<?= $backup['ID_EXPORTACION'] ?>)" 
                                                                title="Descargar">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info btn-modern" 
                                                                onclick="verDetalleBackup(<?= $backup['ID_EXPORTACION'] ?>)" 
                                                                title="Ver Detalle">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-modern" 
                                                                onclick="eliminarBackup(<?= $backup['ID_EXPORTACION'] ?>)" 
                                                                title="Eliminar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-database"></i>
                                <h5>No hay backups registrados</h5>
                                <p class="text-muted">Genera tu primer backup para comenzar a proteger la información del sistema.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Backup -->
<div class="modal fade" id="modalNuevoBackup" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Generar Nuevo Backup
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoBackup">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Backup</label>
                                <select class="form-select" name="tipo_backup" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="completo">Backup Completo</option>
                                    <option value="incremental">Backup Incremental</option>
                                    <option value="diferencial">Backup Diferencial</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prioridad</label>
                                <select class="form-select" name="prioridad" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" 
                                  placeholder="Describe el propósito de este backup..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Programada</label>
                                <input type="datetime-local" class="form-control" name="fecha_programada">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Retención (días)</label>
                                <input type="number" class="form-control" name="retencion" min="1" max="365" value="30">
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> El backup se ejecutará inmediatamente o en la fecha programada según tu selección.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="generarBackup()">
                    <i class="fas fa-play me-1"></i>Generar Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Configuración -->
<div class="modal fade" id="modalConfiguracion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>
                    Configuración de Backups
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Configuración General</h6>
                        <div class="mb-3">
                            <label class="form-label">Backup Automático</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="backupAutomatico" checked>
                                <label class="form-check-label" for="backupAutomatico">Habilitar</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Frecuencia</label>
                            <select class="form-select">
                                <option>Diario</option>
                                <option>Semanal</option>
                                <option>Mensual</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Almacenamiento</h6>
                        <div class="mb-3">
                            <label class="form-label">Ubicación Local</label>
                            <input type="text" class="form-control" value="/backups/" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Espacio Disponible</label>
                            <div class="progress">
                                <div class="progress-bar" style="width: 65%">65%</div>
                            </div>
                            <small class="text-muted">6.5 GB de 10 GB</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="guardarConfiguracion()">
                    <i class="fas fa-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filtros de Búsqueda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFiltros">
                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <select class="form-select" name="filtro_usuario">
                            <option value="">Todos los usuarios</option>
                            <option value="admin">Administrador</option>
                            <option value="sistema">Sistema</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Desde</label>
                                <input type="date" class="form-control" name="fecha_desde">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="mb-3">
                                    <label class="form-label">Fecha Hasta</label>
                                    <input type="date" class="form-control" name="fecha_hasta">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="completado">Completado</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
                <button type="button" class="btn btn-info" onclick="aplicarFiltros()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function generarBackup() {
        showNotification('Generando backup del sistema...', 'info');
        // Aquí iría la lógica para generar el backup
        setTimeout(() => {
            showNotification('Backup generado exitosamente', 'success');
            bootstrap.Modal.getInstance(document.getElementById('modalNuevoBackup')).hide();
            // Recargar la página para mostrar el nuevo backup
            location.reload();
        }, 2000);
    }

    function descargarBackup(id) {
        showNotification('Descargando backup...', 'info');
        // Aquí iría la lógica para descargar el backup
        setTimeout(() => {
            showNotification('Descarga completada', 'success');
        }, 1500);
    }

    function verDetalleBackup(id) {
        showNotification('Mostrando detalles del backup...', 'info');
    }

    function eliminarBackup(id) {
        if (confirm('¿Estás seguro de eliminar este backup? Esta acción no se puede deshacer.')) {
            showNotification('Eliminando backup...', 'warning');
            // Aquí iría la lógica para eliminar el backup
            setTimeout(() => {
                showNotification('Backup eliminado exitosamente', 'success');
                // Recargar la página
                location.reload();
            }, 1000);
        }
    }

    function guardarConfiguracion() {
        showNotification('Guardando configuración...', 'info');
        setTimeout(() => {
            showNotification('Configuración guardada exitosamente', 'success');
            bootstrap.Modal.getInstance(document.getElementById('modalConfiguracion')).hide();
        }, 1000);
    }

    function aplicarFiltros() {
        showNotification('Aplicando filtros...', 'info');
        bootstrap.Modal.getInstance(document.getElementById('modalFiltros')).hide();
    }

    function limpiarFiltros() {
        document.getElementById('formFiltros').reset();
        showNotification('Filtros limpiados', 'info');
    }

    function exportarHistorial() {
        showNotification('Exportando historial...', 'info');
        setTimeout(() => {
            showNotification('Historial exportado exitosamente', 'success');
        }, 1500);
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set default date for new backup
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        document.querySelector('input[name="fecha_programada"]').value = now.toISOString().slice(0, 16);
    });
</script>
<?= $this->endSection() ?>
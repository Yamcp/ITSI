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
    
    /* Estilos adicionales para modales */
    .modal {
        z-index: 1055;
    }
    
    .modal-backdrop {
        z-index: 1050;
    }
    
    .modal.show {
        display: block !important;
    }
    
    .modal.fade .modal-dialog {
        transition: transform .3s ease-out;
        transform: translate(0, -50px);
    }
    
    .modal.show .modal-dialog {
        transform: none;
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
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="showModal('modalNuevoBackup')">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-plus-circle fa-2x mb-3 text-primary"></i>
                        <div class="fw-bold text-primary">Generar Backup</div>
                        <small class="text-muted">Crear nuevo respaldo del sistema</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="showModal('modalConfiguracion')">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-cog fa-2x mb-3 text-warning"></i>
                        <div class="fw-bold text-warning">Configuración</div>
                        <small class="text-muted">Ajustar parámetros de backup</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="exportarHistorial()">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-download fa-2x mb-3 text-success"></i>
                        <div class="fw-bold text-success">Exportar Historial</div>
                        <small class="text-muted">Descargar registro de backups</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm action-card h-100" onclick="showModal('modalFiltros')">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <i class="fas fa-filter fa-2x mb-3 text-info"></i>
                        <div class="fw-bold text-info">Filtros</div>
                        <small class="text-muted">Buscar backups específicos</small>
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
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="limpiarFiltros()">
                                <i class="fas fa-eraser me-1"></i>Limpiar
                            </button>
                            <button class="btn btn-light btn-sm" onclick="showModal('modalFiltros')">
                                <i class="fas fa-filter me-1"></i>Filtros
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (isset($exportaciones) && !empty($exportaciones)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Usuario</th>
                                            <th>Fecha y Hora</th>
                                            <th>Descripción</th>
                                            <th>Estado</th>
                                            <th>Tipo</th>
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
                                                    <span class="badge bg-info">Sistema</span>
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
                                <button class="btn btn-primary btn-modern" onclick="showModal('modalNuevoBackup')">
                                    <i class="fas fa-plus me-2"></i>Generar Primer Backup
                                </button>
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

<!-- Modal Detalle de Backup -->
<div class="modal fade" id="modalDetalleBackup" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle del Backup
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Información General</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>ID:</strong> <span id="detalleId">-</span></p>
                                        <p><strong>Usuario:</strong> <span id="detalleUsuario">-</span></p>
                                        <p><strong>Fecha:</strong> <span id="detalleFecha">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Descripción:</strong> <span id="detalleDescripcion">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                        <p><strong>Tipo:</strong> <span id="detalleTipo">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Información del Sistema</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Versión del Sistema:</strong> <span>1.0.0</span></p>
                                        <p><strong>Base de Datos:</strong> <span>MySQL 8.0</span></p>
                                        <p><strong>Servidor:</strong> <span>Apache 2.4</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>PHP:</strong> <span>8.1.0</span></p>
                                        <p><strong>Framework:</strong> <span>CodeIgniter 4</span></p>
                                        <p><strong>Fecha de Creación:</strong> <span id="detalleFechaCreacion">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Estado del Backup</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="progress-circle mb-3">
                                    <canvas id="estadoChart" width="150" height="150"></canvas>
                                </div>
                                <h4 id="estadoPercent">100%</h4>
                                <p class="text-muted" id="estadoDias">Completado</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Acciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-success btn-sm" onclick="descargarBackup(currentBackupId)">
                                        <i class="fas fa-download me-1"></i>Descargar Backup
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" onclick="restaurarBackup(currentBackupId)">
                                        <i class="fas fa-undo me-1"></i>Restaurar Sistema
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="verLogs(currentBackupId)">
                                        <i class="fas fa-file-alt me-1"></i>Ver Logs
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Editar Backup
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
                                <label class="form-label">Fecha Hasta</label>
                                <input type="date" class="form-control" name="fecha_hasta">
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
    // Variable global para el ID del backup actual
    let currentBackupId = null;
    
    // Prevenir errores de dashboard.js en esta página
    console.log('Página de backup cargada - evitando conflictos con dashboard.js');
    
    // Sobrescribir la función problemática de dashboard.js si existe
    if (typeof ApexCharts !== 'undefined') {
        console.log('ApexCharts detectado, configurando para página de backup');
        // Crear una función de renderizado segura
        const originalRender = ApexCharts.prototype.render;
        ApexCharts.prototype.render = function() {
            try {
                if (this.el && document.contains(this.el)) {
                    return originalRender.call(this);
                } else {
                    console.warn('Elemento de gráfico no encontrado, saltando renderizado');
                    return Promise.resolve();
                }
            } catch (error) {
                console.warn('Error al renderizar gráfico:', error);
                return Promise.resolve();
            }
        };
    }

    // Funciones principales - Definidas globalmente INMEDIATAMENTE
    window.showModal = function(modalId) {
        console.log('showModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                alert('Modal no encontrado: ' + modalId);
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                console.log('Usando Bootstrap 5');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('Modal abierto exitosamente con Bootstrap 5:', modalId);
                return;
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                console.log('Usando jQuery/Bootstrap 4');
                $('#' + modalId).modal('show');
                console.log('Modal abierto exitosamente con jQuery:', modalId);
                return;
            }

            // Fallback manual - mostrar el modal directamente
            console.log('Usando fallback manual');
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            
            // Crear backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop-' + modalId;
            document.body.appendChild(backdrop);
            
            console.log('Modal abierto manualmente:', modalId);

        } catch (error) {
            console.error('Error al abrir modal:', error);
            alert('Error al abrir la ventana: ' + error.message);
        }
    };
    
    // Definir funciones básicas inmediatamente
    window.exportarHistorial = function() {
        console.log('exportarHistorial called');
        alert('Función exportarHistorial ejecutada');
    };
    
    window.generarBackup = function() {
        console.log('generarBackup called');
        alert('Función generarBackup ejecutada');
    };
    
    window.descargarBackup = function(id) {
        console.log('descargarBackup called with id:', id);
        alert('Función descargarBackup ejecutada para ID: ' + id);
    };
    
    window.verDetalleBackup = function(id) {
        console.log('verDetalleBackup called with id:', id);
        alert('Función verDetalleBackup ejecutada para ID: ' + id);
    };
    
    window.eliminarBackup = function(id) {
        console.log('eliminarBackup called with id:', id);
        alert('Función eliminarBackup ejecutada para ID: ' + id);
    };
    
    window.guardarConfiguracion = function() {
        console.log('guardarConfiguracion called');
        alert('Función guardarConfiguracion ejecutada');
    };
    
    window.aplicarFiltros = function() {
        console.log('aplicarFiltros called');
        alert('Función aplicarFiltros ejecutada');
    };
    
    window.limpiarFiltros = function() {
        console.log('limpiarFiltros called');
        alert('Función limpiarFiltros ejecutada');
    };
    
    window.restaurarBackup = function(id) {
        console.log('restaurarBackup called with id:', id);
        alert('Función restaurarBackup ejecutada para ID: ' + id);
    };
    
    window.verLogs = function(id) {
        console.log('verLogs called with id:', id);
        alert('Función verLogs ejecutada para ID: ' + id);
    };
    
    window.drawEstadoChart = function(percentage) {
        console.log('drawEstadoChart called with percentage:', percentage);
    };
    
    window.actualizarTablaBackups = function(backups) {
        console.log('actualizarTablaBackups called with backups:', backups);
    };
    
    window.hideModal = function(modalId) {
        console.log('hideModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                    return;
                }
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#' + modalId).modal('hide');
                return;
            }

            // Fallback manual
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            
            // Remover backdrop
            const backdrop = document.getElementById('modal-backdrop-' + modalId);
            if (backdrop) {
                backdrop.remove();
            }

        } catch (error) {
            console.error('Error al cerrar modal:', error);
        }
    };



    window.generarBackup = function() {
        console.log('generarBackup called');
        try {
            const form = document.getElementById('formNuevoBackup');
            const formData = new FormData(form);
            
            // Convertir FormData a JSON
            const data = {
                descripcion: formData.get('descripcion'),
                tipo_backup: formData.get('tipo_backup'),
                prioridad: formData.get('prioridad'),
                fecha_programada: formData.get('fecha_programada'),
                retencion: formData.get('retencion')
            };

            // Mostrar loading
            const btnGenerar = document.querySelector('button[onclick="generarBackup()"]');
            const originalText = btnGenerar.innerHTML;
            btnGenerar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generando...';
            btnGenerar.disabled = true;

            fetch('<?= base_url('admin/backup/crear') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('Backup generado exitosamente');
                    // Cerrar modal
                    hideModal('modalNuevoBackup');
                    // Recargar la página para mostrar el nuevo backup
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al generar backup: ' + error.message);
            })
            .finally(() => {
                // Restaurar botón
                btnGenerar.innerHTML = originalText;
                btnGenerar.disabled = false;
            });

        } catch (error) {
            console.error('Error al generar backup:', error);
            alert('Error al generar backup: ' + error.message);
        }
    }





    // Verificar que las funciones estén disponibles inmediatamente
    console.log('=== VERIFICACIÓN INMEDIATA ===');
    console.log('showModal disponible:', typeof window.showModal);
    console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
    console.log('generarBackup disponible:', typeof window.generarBackup);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOM LOADED ===');
        
        // Prevenir errores de ApexCharts en esta página
        if (typeof ApexCharts !== 'undefined') {
            console.log('ApexCharts detectado, evitando errores en página de backup');
        }
        
        // Esperar un poco más para asegurar que Bootstrap esté completamente cargado
        setTimeout(function() {
            try {
                // Verificar Bootstrap
                if (typeof bootstrap === 'undefined') {
                    console.warn('⚠️ Bootstrap no está disponible');
                    console.log('Intentando cargar Bootstrap manualmente...');
                } else {
                    console.log('✅ Bootstrap está disponible');
                    console.log('Bootstrap version:', bootstrap.Modal ? 'Modal disponible' : 'Modal no disponible');
                }

                // Verificar jQuery
                if (typeof $ !== 'undefined') {
                    console.log('✅ jQuery está disponible');
                } else {
                    console.warn('⚠️ jQuery no está disponible');
                }

                // Set default date for new backup
                const fechaInput = document.querySelector('input[name="fecha_programada"]');
                if (fechaInput) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    fechaInput.value = now.toISOString().slice(0, 16);
                    console.log('✅ Fecha por defecto establecida');
                }

                // Verificar que los modales existan
                const modales = ['modalNuevoBackup', 'modalConfiguracion', 'modalDetalleBackup', 'modalFiltros'];
                modales.forEach(function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        console.log('✅ Modal encontrado:', modalId);
                    } else {
                        console.error('❌ Modal no encontrado:', modalId);
                    }
                });

                console.log('✅ Backup page initialized successfully');

            } catch (error) {
                console.error('❌ Error en la inicialización:', error);
            }
        }, 500); // Esperar 500ms para que Bootstrap se cargue completamente
    });

    // Verificar funciones después de un breve delay
    setTimeout(() => {
        console.log('=== VERIFICACIÓN FINAL ===');
        console.log('showModal disponible:', typeof window.showModal);
        console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
        console.log('generarBackup disponible:', typeof window.generarBackup);
        console.log('==========================');
        
        // Función de prueba para verificar que los modales funcionen
        window.testModal = function() {
            console.log('Probando modal...');
            window.showModal('modalNuevoBackup');
        };
        
        console.log('Función de prueba disponible: testModal()');
    }, 100);

</script>
<?= $this->endSection() ?>
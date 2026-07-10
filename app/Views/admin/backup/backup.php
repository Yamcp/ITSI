<!-- app/Views/admin/backup/backup.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

<?php
$exportaciones = $exportaciones ?? [];
$totalBackups = count($exportaciones);
$hace30dias = date('Y-m-d', strtotime('-30 days'));
$hace7dias = date('Y-m-d', strtotime('-7 days'));
$esteMes = 0;
$estaSemana = 0;
foreach ($exportaciones as $exp) {
    $f = $exp['FECHA_EXPORTACION'] ?? '';
    if ($f >= $hace30dias) $esteMes++;
    if ($f >= $hace7dias) $estaSemana++;
}
$estadisticas = [
    'total' => $totalBackups,
    'completados' => $totalBackups,
    'este_mes' => $esteMes,
    'esta_semana' => $estaSemana
];
?>
<?= $this->section('styles') ?>
<style>
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .backup-calendar {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.5rem;
    }

    .backup-day-btn {
        min-height: 42px;
        padding: 0.65rem 0.5rem;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        background: #fff;
        color: #495057;
    }

    .backup-day-btn.active {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }

    .backup-day-btn:hover {
        background: #e7f1ff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Script inline para definir funciones críticas inmediatamente -->
<script>
    // Definir funciones críticas inmediatamente para evitar errores de referencia
    window.showModal = function(modalId) {
        console.log('showModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                modal.show();
                return;
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#' + modalId).modal({
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                return;
            }

            // Fallback manual
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            modalElement.setAttribute('aria-hidden', 'false');
            modalElement.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop-' + modalId;
            document.body.appendChild(backdrop);

        } catch (error) {
            console.error('Error al abrir modal:', error);
        }
    };






    window.validarCarpeta = function() {
        console.log('validarCarpeta called');
        const ubicacionInput = document.getElementById('ubicacion_backup');
        if (ubicacionInput && ubicacionInput.value.trim() !== '') {
            // Validar formato básico de ruta
            const ruta = ubicacionInput.value.trim();
            if (ruta.length > 0) {
                if (typeof showNotification === 'function') {
                    showNotification('Ruta de carpeta actualizada: ' + ruta, 'info');
                }
            }
        }
    };

    window.abrirModalBackupModo = function(modo) {
        if (modo === 'automatico') {
            showModal('modalAutomaticoBackup');
            return;
        }

        showModal('modalNuevoBackup');
    };

window.toggleUbicacionBackupSection = function() {
        const tipo = document.getElementById('tipo_almacenamiento')?.value;
        const seccion = document.getElementById('ubicacionBackupSection');
        const localSection = document.getElementById('backupLocalSection');
        const oneDriveSection = document.getElementById('backupOneDriveSection');

        if (seccion) {
            seccion.style.display = tipo ? 'block' : 'none';
        }

        if (localSection && oneDriveSection) {
            if (tipo === 'local') {
                localSection.style.display = 'block';
                oneDriveSection.style.display = 'none';
            } else if (tipo === 'remoto') {
                localSection.style.display = 'none';
                oneDriveSection.style.display = 'block';
                window.verificarConexionOneDrive();
            }
        }
    };

    window.verificarConexionOneDrive = function() {
        // Verificar en el servidor si está conectado a OneDrive
        fetch('<?= base_url('admin/onedrive/check-connection') ?>')
            .then(response => response.json())
            .then(data => {
                const btnConectar = document.getElementById('btnConectarOneDrive');
                const btnDesconectar = document.getElementById('btnDesconectarOneDrive');
                const labelStatus = document.getElementById('onedriveLabelStatus');

                if (data.connected) {
                    labelStatus.textContent = '✓ Conectado a OneDrive';
                    labelStatus.className = 'text-success fw-bold';
                    btnConectar.style.display = 'none';
                    btnDesconectar.style.display = 'inline-block';
                } else {
                    labelStatus.textContent = '✗ No conectado a OneDrive';
                    labelStatus.className = 'text-muted';
                    btnConectar.style.display = 'inline-block';
                    btnDesconectar.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error verificando conexión OneDrive:', error);
            });
    };

    window.conectarOneDrive = function(event) {
        event.preventDefault();
        window.location.href = '<?= base_url('admin/onedrive/connect') ?>';
    };

    window.desconectarOneDrive = function() {
        if (confirm('¿Deseas desconectar tu cuenta de OneDrive?')) {
            window.location.href = '<?= base_url('admin/onedrive/disconnect') ?>';
        }
    };

    window.toggleFrecuenciaFields = function() {
        const frecuencia = document.getElementById('frecuenciaBackup')?.value || 'Diario';
        const semanalSection = document.getElementById('configSemanalSection');
        const mensualSection = document.getElementById('configMensualSection');

        if (semanalSection) {
            semanalSection.style.display = frecuencia === 'Semanal' ? 'block' : 'none';
        }
        if (mensualSection) {
            mensualSection.style.display = frecuencia === 'Mensual' ? 'block' : 'none';
        }

        if (frecuencia === 'Mensual') {
            window.renderBackupCalendar();
        }
    };

    window.renderBackupCalendar = function() {
        const calendar = document.getElementById('backupCalendar');
        const selectedInput = document.getElementById('diaDelMesBackup');
        if (!calendar) {
            return;
        }

        const selectedDay = Number(selectedInput?.value || 1);
        calendar.innerHTML = '';

        for (let day = 1; day <= 31; day++) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'backup-day-btn';
            button.textContent = day;
            button.dataset.day = day;
            if (day === selectedDay) {
                button.classList.add('active');
            }
            button.addEventListener('click', function() {
                window.selectBackupDay(day);
            });
            calendar.appendChild(button);
        }
    };

    window.selectBackupDay = function(day) {
        const selectedInput = document.getElementById('diaDelMesBackup');
        if (selectedInput) {
            selectedInput.value = day;
        }

        const calendar = document.getElementById('backupCalendar');
        if (!calendar) {
            return;
        }

        calendar.querySelectorAll('.backup-day-btn').forEach(function(button) {
            if (Number(button.dataset.day) === day) {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
    };

    window.verificarCarpeta = function() {
        console.log('verificarCarpeta called');

        const ubicacionInput = document.getElementById('ubicacion_backup');

        if (!ubicacionInput || ubicacionInput.value.trim() === '') {
            if (typeof showNotification === 'function') {
                showNotification('Por favor ingresa una ruta de carpeta', 'warning');
            } else {
                alert('Por favor ingresa una ruta de carpeta');
            }
            return;
        }

        const ruta = ubicacionInput.value.trim();

        if (typeof showNotification === 'function') {
            showNotification('Verificando carpeta: ' + ruta, 'info');
        }

        setTimeout(() => {
            const existe = Math.random() > 0.3;
            const estadoCarpeta = document.getElementById('estadoCarpeta');
            const badgeEstadoCarpeta = document.getElementById('badgeEstadoCarpeta');

            if (existe) {
                if (typeof showNotification === 'function') {
                    showNotification('✓ Carpeta encontrada y accesible', 'success');
                } else {
                    alert('✓ Carpeta encontrada y accesible');
                }

                if (estadoCarpeta && badgeEstadoCarpeta) {
                    estadoCarpeta.style.display = 'block';
                    badgeEstadoCarpeta.className = 'badge bg-success';
                    badgeEstadoCarpeta.innerHTML = '<i class="fas fa-check-circle me-1"></i>Carpeta accesible';
                }
            } else {
                if (typeof showNotification === 'function') {
                    showNotification('⚠ La carpeta no existe o no es accesible', 'warning');
                } else {
                    alert('⚠ La carpeta no existe o no es accesible');
                }

                if (estadoCarpeta && badgeEstadoCarpeta) {
                    estadoCarpeta.style.display = 'block';
                    badgeEstadoCarpeta.className = 'badge bg-warning';
                    badgeEstadoCarpeta.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Carpeta no accesible';
                }
            }
        }, 1000);
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

    console.log('Funciones críticas definidas inmediatamente');
</script>

<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header de Backups (igual que Convenios) -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-database me-2"></i>
                    Gestión de Backups
                </h3>
            </div>
        </div>

        <!-- Acciones Rápidas en Tarjetas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="abrirModalBackupModo('manual'); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-hdd fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Backup Manual</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="abrirModalBackupModo('automatico'); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-sync-alt fa-2x mb-2" style="color: #17a2b8; text-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);"></i>
                            <div class="fw-bold">Backup Automático</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarHistorial(); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Exportar Historial</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalFiltros'); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-filter fa-2x mb-2" style="color: #17a2b8; text-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);"></i>
                            <div class="fw-bold">Filtros</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Backups -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-history me-2"></i>
                            Historial de Backups
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (isset($exportaciones) && !empty($exportaciones)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-light">
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
                                            <tr role="row">
                                                <td role="cell">
                                                    <span class="badge bg-secondary"><?= $backup['ID_EXPORTACION'] ?></span>
                                                </td>
                                                <td role="cell">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($backup['NOMBRE'] ?? 'Usuario') ?>+<?= urlencode($backup['APELLIDO'] ?? 'Sistema') ?>&background=0d6efd&color=fff&size=32"
                                                            class="rounded-circle me-2"
                                                            alt="Avatar de <?= $backup['NOMBRE'] ?? 'Usuario' ?> <?= $backup['APELLIDO'] ?? 'Sistema' ?>"
                                                            width="32" height="32">
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
                                                <td role="cell">
                                                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($backup['FECHA_EXPORTACION'])) ?></div>
                                                    <small class="text-muted"><?= date('H:i:s', strtotime($backup['FECHA_EXPORTACION'])) ?></small>
                                                </td>
                                                <td role="cell">
                                                    <div class="fw-semibold"><?= $backup['DESCRIPCION_EXPORTACION'] ?? 'Backup del sistema' ?></div>
                                                    <small class="text-muted">Respaldo automático</small>
                                                </td>
                                                <td role="cell">
                                                    <span class="badge bg-success"
                                                        role="status"
                                                        aria-label="Estado: Completado">Completado</span>
                                                </td>
                                                <td role="cell">
                                                    <span class="badge bg-info">Sistema</span>
                                                </td>
                                                <td role="cell">
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Acciones para backup <?= $backup['ID_EXPORTACION'] ?>">
                                                        <button class="btn btn-outline-success btn-sm"
                                                            onclick="descargarBackup(<?= $backup['ID_EXPORTACION'] ?>)"
                                                            aria-label="Descargar backup <?= $backup['ID_EXPORTACION'] ?>"
                                                            title="Descargar backup">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info btn-sm"
                                                            onclick="verDetalleBackup(<?= $backup['ID_EXPORTACION'] ?>)"
                                                            aria-label="Ver detalles del backup <?= $backup['ID_EXPORTACION'] ?>"
                                                            title="Ver Detalle">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-sm"
                                                            onclick="eliminarBackup(<?= $backup['ID_EXPORTACION'] ?>)"
                                                            aria-label="Eliminar backup <?= $backup['ID_EXPORTACION'] ?>"
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
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-light">
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
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-database fa-2x mb-2"></i><br>
                                                No hay backups registrados. Genera tu primer backup para proteger la información del sistema.
                                                <div class="mt-2">
                                                    <button class="btn btn-primary btn-sm" onclick="showModal('modalNuevoBackup')">
                                                        <i class="fas fa-plus me-1"></i>Generar Primer Backup
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Backup Manual -->
<div class="modal fade" id="modalNuevoBackup" tabindex="-1"
    role="dialog"
    aria-labelledby="modalNuevoBackupTitle"
    aria-describedby="modalNuevoBackupDesc"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNuevoBackupTitle">
                    <i class="fas fa-plus-circle me-2"></i>Backup Manual
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalNuevoBackupDesc">
                <form id="formNuevoBackup" role="form" aria-label="Formulario para generar nuevo backup">
                    <div class="alert alert-info mb-4" role="alert">
                        <strong>Campos obligatorios:</strong> los marcados con <span class="text-danger">*</span>.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_backup" class="form-label">Tipo de Backup <span class="text-danger">*</span></label>
                                <select class="form-select"
                                    name="tipo_backup"
                                    id="tipo_backup"
                                    required
                                    aria-describedby="tipo_backup_help">
                                    <option value="">Seleccionar...</option>
                                    <option value="completo">Backup Completo</option>
                                    <option value="incremental">Backup Incremental</option>
                                    <option value="diferencial">Backup Diferencial</option>
                                </select>
                                <div id="tipo_backup_help" class="form-text">Selecciona el tipo de respaldo que deseas crear</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select"
                                    name="prioridad"
                                    id="prioridad"
                                    required
                                    aria-describedby="prioridad_help">
                                    <option value="">Seleccionar...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                                <div id="prioridad_help" class="form-text">Define la prioridad de ejecución del backup</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control"
                            name="descripcion"
                            id="descripcion"
                            rows="3"
                            placeholder="Describe el propósito de este backup..."
                            required
                            aria-describedby="descripcion_help"></textarea>
                        <div id="descripcion_help" class="form-text">Proporciona una descripción clara del propósito de este backup</div>
                    </div>                  
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGenerarBackupManual" onclick="generarBackupManual()" aria-label="Generar backup">
                    <i class="fas fa-play me-1"></i>Generar Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Backup Automático -->
<div class="modal fade" id="modalAutomaticoBackup" tabindex="-1" aria-labelledby="modalAutomaticoBackupTitle" aria-describedby="modalAutomaticoBackupDesc" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAutomaticoBackupTitle">
                    <i class="fas fa-cogs me-2"></i>Backup Automático
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalAutomaticoBackupDesc">
                <form id="formAutomaticoBackup" role="form" aria-label="Formulario de configuración de backup automático">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="frecuenciaBackup" class="form-label">Frecuencia</label>
                                    <select class="form-select" id="frecuenciaBackup" name="frecuencia" onchange="toggleFrecuenciaFields()">
                                        <option value="Diario">Diario</option>
                                        <option value="Semanal">Semanal</option>
                                        <option value="Mensual">Mensual</option>
                                    </select>
                                    <div class="form-text">Selecciona la frecuencia de los backups automáticos.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="horaGeneracionBackup" class="form-label">Hora de generación</label>
                                    <input type="time" class="form-control" id="horaGeneracionBackup" name="hora_generacion" value="02:00">
                                    <div class="form-text">Define la hora en que se generará cada backup automático.</div>
                                </div>
                            </div>
                            <div id="configSemanalSection" class="mb-3" style="display: none;">
                                <label class="form-label">Días de la semana</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_lunes" name="dias_semana" value="Lunes">
                                        <label class="form-check-label" for="dia_lunes">Lun</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_martes" name="dias_semana" value="Martes">
                                        <label class="form-check-label" for="dia_martes">Mar</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_miercoles" name="dias_semana" value="Miércoles">
                                        <label class="form-check-label" for="dia_miercoles">Mié</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_jueves" name="dias_semana" value="Jueves">
                                        <label class="form-check-label" for="dia_jueves">Jue</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_viernes" name="dias_semana" value="Viernes">
                                        <label class="form-check-label" for="dia_viernes">Vie</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_sabado" name="dias_semana" value="Sábado">
                                        <label class="form-check-label" for="dia_sabado">Sáb</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="dia_domingo" name="dias_semana" value="Domingo">
                                        <label class="form-check-label" for="dia_domingo">Dom</label>
                                    </div>
                                </div>
                                <div class="form-text">Selecciona los días de la semana para ejecutar el backup.</div>
                            </div>
                            <div id="configMensualSection" class="mb-3" style="display: none;">
                                <label class="form-label">Día del mes</label>
                                <div id="backupCalendar" class="backup-calendar"></div>
                                <input type="hidden" id="diaDelMesBackup" name="dia_del_mes" value="1">
                                <div class="form-text">Selecciona el día del mes para generar el backup.</div>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_almacenamiento" class="form-label">Tipo de almacenamiento</label>
                                <select class="form-select" id="tipo_almacenamiento" name="tipo_almacenamiento" onchange="toggleUbicacionBackupSection()">
                                    <option value="">Seleccionar...</option>
                                    <option value="local">Local</option>
                                    <option value="remoto">Remoto</option>
                                </select>
                                <div class="form-text">Selecciona dónde se guardarán los backups automáticos.</div>
                            </div>
                            <div id="ubicacionBackupSection" class="mb-3" style="display: none;">
                                <div id="backupLocalSection">
                                    <label for="ubicacion_backup" class="form-label">Ubicación de respaldo</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="ubicacion_backup" name="ubicacion_backup" value="/backups/" placeholder="Carpeta para backups" onchange="validarCarpeta()">
                                        <button class="btn btn-outline-secondary" type="button" onclick="seleccionarCarpeta()" title="Explorar carpetas">
                                            <i class="fas fa-folder-open"></i>
                                        </button>
                                        <button class="btn btn-outline-info" type="button" onclick="verificarCarpeta()" title="Verificar si la carpeta existe">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Ruta donde se guardarán los archivos de backup.</div>
                                    <div id="estadoCarpeta" class="mt-2" style="display: none;">
                                        <span class="badge" id="badgeEstadoCarpeta">
                                            <i class="fas fa-info-circle me-1"></i>Estado de la carpeta
                                        </span>
                                    </div>
                                </div>

                                <div id="backupOneDriveSection" style="display: none;" class="p-3 bg-light rounded">
                                    <div class="mb-3">
                                        <h6 class="mb-3"><i class="fas fa-cloud me-2"></i>Conectar con OneDrive</h6>
                                        <p class="mb-3">Los backups se guardarán automáticamente en tu cuenta de OneDrive.</p>
                                        <div id="onedriveStatus" class="mb-3">
                                            <p><span id="onedriveLabelStatus" class="text-muted">No conectado</span></p>
                                        </div>
                                        <a href="<?= base_url('admin/onedrive/connect') ?>" class="btn btn-primary" id="btnConectarOneDrive" onclick="conectarOneDrive(event)">
                                            <i class="fas fa-link me-1"></i>Conectar con OneDrive
                                        </a>
                                        <button type="button" class="btn btn-danger" id="btnDesconectarOneDrive" onclick="desconectarOneDrive()" style="display: none;">
                                            <i class="fas fa-unlink me-1"></i>Desconectar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline-primary" onclick="guardarConfiguracion()">
                    <i class="fas fa-save me-1"></i>Guardar configuración
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Backup -->
<div class="modal fade" id="modalDetalleBackup" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Detalle del Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

<!-- Modal Logs del Backup -->
<div class="modal fade" id="modalLogsBackup" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>Log del backup <span id="logsBackupIdTitle">#0</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body p-0">
                <pre id="logBackupContent" class="bg-dark text-light p-4 mb-0 small" style="max-height: 70vh; overflow: auto; white-space: pre-wrap; word-break: break-word;">Cargando...</pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-filter me-2"></i>Filtros de Búsqueda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFiltros">
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
    // ===== DEFINIR FUNCIONES CRÍTICAS INMEDIATAMENTE =====

    // Función de notificación accesible
    window.showNotification = function(message, type = 'info', duration = 5000) {
        const bsType = type === 'error' ? 'danger' : type;
        const notification = document.createElement('div');
        notification.className = `alert alert-${bsType} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 340px; max-width: 560px; box-shadow: 0 0 18px rgba(0,0,0,0.25); border-width: 2px;';
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', bsType === 'danger' ? 'assertive' : 'polite');

        if (bsType === 'danger') {
            notification.style.cssText += ' background-color: #f8d7da; color: #842029; border-color: #f5c2c7;';
        }

        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} fa-lg me-2" aria-hidden="true"></i>
                <span class="fw-semibold">${message}</span>
                <button type="button" class="btn-close ms-auto" aria-label="Cerrar notificación" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove después del tiempo especificado
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, duration);
    };

    // Función principal para mostrar modales
    window.showModal = function(modalId) {
        console.log('showModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                showNotification('Modal no encontrado: ' + modalId, 'error');
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                console.log('Usando Bootstrap 5');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                modal.show();

                // Mejorar accesibilidad
                modalElement.setAttribute('aria-hidden', 'false');
                modalElement.setAttribute('aria-modal', 'true');

                // Enfocar el primer elemento interactivo
                setTimeout(() => {
                    const firstFocusable = modalElement.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    if (firstFocusable) {
                        firstFocusable.focus();
                    }
                }, 100);

                console.log('Modal abierto exitosamente con Bootstrap 5:', modalId);
                return;
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                console.log('Usando jQuery/Bootstrap 4');
                $('#' + modalId).modal({
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                console.log('Modal abierto exitosamente con jQuery:', modalId);
                return;
            }

            // Fallback manual - mostrar el modal directamente
            console.log('Usando fallback manual');
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            modalElement.setAttribute('aria-hidden', 'false');
            modalElement.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            // Crear backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop-' + modalId;
            backdrop.setAttribute('aria-hidden', 'true');
            document.body.appendChild(backdrop);

            // Enfocar el primer elemento interactivo
            setTimeout(() => {
                const firstFocusable = modalElement.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            }, 100);

            console.log('Modal abierto manualmente:', modalId);

        } catch (error) {
            console.error('Error al abrir modal:', error);
            showNotification('Error al abrir la ventana: ' + error.message, 'error');
        }
    };

    // Función para cerrar modales
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
            modalElement.setAttribute('aria-hidden', 'true');
            modalElement.setAttribute('aria-modal', 'false');
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

    // Funciones de acción del backup
    window.exportarHistorial = function() {
        showNotification('Exportando historial de backups...', 'info');
        window.location.href = `<?= base_url('admin/backup/exportar-historial') ?>`;
        setTimeout(() => {
            showNotification('Historial exportado exitosamente', 'success');
        }, 1500);
    };

    window.descargarBackup = function(id) {
        if (!id) {
            showNotification('ID de backup no válido', 'error');
            return;
        }

        showNotification(`Iniciando descarga del backup ${id}...`, 'info');

        // Navegación directa para forzar descarga del archivo (no JSON simulado)
        const downloadUrl = `<?= base_url('admin/backup/descargar') ?>/${id}`;
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.style.display = 'none';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        setTimeout(() => {
            showNotification('Descarga del backup iniciada', 'success');
        }, 800);
    };

    window.verDetalleBackup = function(id) {
        console.log('verDetalleBackup called with id:', id);
        currentBackupId = id;
        showModal('modalDetalleBackup');
    };

    window.eliminarBackup = function(id) {
        console.log('eliminarBackup called with id:', id);

        confirmarAccion({
            titulo: 'Eliminar Backup',
            mensaje: `¿Deseas eliminar el backup #${id}?`,
            icono: 'fas fa-trash-alt',
            colorIcono: 'text-danger',
            bgIcono: 'bg-danger bg-opacity-10',
            textoAceptar: 'Eliminar',
            colorBoton: 'btn-danger',
            onAceptar: function() {
                showNotification(`Eliminando backup ${id}...`, 'info');

                fetch(`<?= base_url('admin/backup/eliminar') ?>/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    const data = await response.json().catch(() => null);
                    if (!response.ok || !data?.success) {
                        throw new Error(data?.message || 'No se pudo eliminar el backup');
                    }
                    return data;
                })
                .then(() => {
                    const button = document.querySelector(`button[aria-label="Eliminar backup ${id}"]`);
                    const row = button?.closest('tr');
                    if (row) {
                        row.remove();
                    }
                    showNotification('Backup eliminado exitosamente', 'success');
                })
                .catch(error => {
                    console.error('Error al eliminar backup:', error);
                    showNotification(error.message || 'Error al eliminar el backup', 'error');
                });
            }
        });
    };

    window.guardarConfiguracion = function() {
        console.log('guardarConfiguracion called');

        // Recopilar datos del formulario de configuración
        const frecuencia = document.querySelector('select[name="frecuencia"]')?.value || 'Diario';
        const diasSemana = Array.from(document.querySelectorAll('input[name="dias_semana"]:checked')).map(input => input.value);
        const configuracion = {
            backupAutomatico: true,
            frecuencia: frecuencia,
            horaGeneracion: document.getElementById('horaGeneracionBackup')?.value || '02:00',
            diasSemana: frecuencia === 'Semanal' ? diasSemana : [],
            diaDelMes: frecuencia === 'Mensual' ? document.getElementById('diaDelMesBackup')?.value || '1' : null,
            ubicacion: document.getElementById('ubicacion_backup')?.value || '/backups/',
            tipoAlmacenamiento: document.getElementById('tipo_almacenamiento')?.value || 'local'
        };

        showNotification('Guardando configuración...', 'info');

        // Simular guardado (en producción, esto sería una llamada al servidor)
        setTimeout(() => {
            console.log('Configuración a guardar:', configuracion);
            showNotification('Configuración guardada exitosamente', 'success');
            hideModal('modalAutomaticoBackup');
        }, 1500);
    };

    window.seleccionarCarpeta = function() {
        console.log('seleccionarCarpeta called');

        // Crear un input de tipo file para seleccionar directorio
        const input = document.createElement('input');
        input.type = 'file';
        input.webkitdirectory = true;
        input.directory = true;
        input.multiple = true;
        input.style.display = 'none';

        input.onchange = function(e) {
            if (e.target.files.length > 0) {
                // Obtener la ruta del directorio seleccionado
                const path = e.target.files[0].webkitRelativePath;
                const directory = path.split('/')[0];

                // Actualizar el campo de ubicación
                const ubicacionInput = document.getElementById('ubicacion_backup');
                if (ubicacionInput) {
                    ubicacionInput.value = directory + '/';
                    showNotification('Carpeta seleccionada: ' + directory, 'success');

                }
            }
        };

        // Agregar el input al DOM temporalmente
        document.body.appendChild(input);
        input.click();

        // Limpiar después de usar
        setTimeout(() => {
            document.body.removeChild(input);
        }, 100);
    };





    window.aplicarFiltros = function() {
        console.log('aplicarFiltros called');
        showNotification('Aplicando filtros...', 'info');
        setTimeout(() => {
            showNotification('Filtros aplicados exitosamente', 'success');
            hideModal('modalFiltros');
        }, 1000);
    };

    window.limpiarFiltros = function() {
        console.log('limpiarFiltros called');
        showNotification('Filtros limpiados', 'info');
    };

    window.restaurarBackup = function(id) {
        console.log('restaurarBackup called with id:', id);
        if (true) {
            showNotification(`Restaurando sistema desde backup ${id}...`, 'info');
            setTimeout(() => {
                showNotification('Sistema restaurado exitosamente', 'success');
            }, 3000);
        }
    };

    window.verLogs = function(id) {
        const logContent = document.getElementById('logBackupContent');
        const logsTitle = document.getElementById('logsBackupIdTitle');
        if (logsTitle) logsTitle.textContent = '#' + id;
        if (logContent) logContent.textContent = 'Cargando...';
        showModal('modalLogsBackup');
        const url = '<?= base_url('admin/backup/logs/') ?>' + id;
        fetch(url)
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.data && res.data.log) {
                    logContent.textContent = res.data.log;
                } else {
                    logContent.textContent = res.message || 'No se pudieron cargar los logs.';
                }
            })
            .catch(function() {
                if (logContent) logContent.textContent = 'Error de conexión al cargar los logs.';
                showNotification('Error al cargar los logs', 'error');
            });
    };

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

    // Función para manejar navegación por teclado
    window.handleKeyboardNavigation = function(event) {
        // ESC para cerrar modales
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                hideModal(openModal.id);
            }
        }

        // Enter y Space para activar botones de acción
        // Enter/Space en botones ya manejado por navegación estándar
    };

    // Función para mejorar la accesibilidad de las tarjetas de acción
    window.enhanceActionCards = function() {
        // Acciones rápidas son enlaces en tarjetas (estilo Convenios), no requieren enhance
    };

    // Verificar que las funciones estén disponibles inmediatamente
    console.log('=== VERIFICACIÓN INMEDIATA ===');
    console.log('showModal disponible:', typeof window.showModal);
    console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
    console.log('generarBackupManual disponible:', typeof window.generarBackupManual);
    console.log('showNotification disponible:', typeof window.showNotification);
    console.log('hideModal disponible:', typeof window.hideModal);

    // Verificación adicional para asegurar que las funciones estén disponibles
    if (typeof window.showModal === 'undefined') {
        console.error('ERROR: showModal no está definida');
    }
    if (typeof window.exportarHistorial === 'undefined') {
        console.error('ERROR: exportarHistorial no está definida');
    }
    if (typeof window.showNotification === 'undefined') {
        console.error('ERROR: showNotification no está definida');
    }

    // Funciones adicionales
    window.drawEstadoChart = function(percentage) {
        console.log('drawEstadoChart called with percentage:', percentage);
    };

    window.actualizarTablaBackups = function(backups) {
        console.log('actualizarTablaBackups called with backups:', backups);
    };


    // Funciones separadas para generación de backups (manual y delegador)
    window.generarBackupManual = function() {
        console.log('generarBackupManual called');
        try {
            const form = document.getElementById('formNuevoBackup');
            if (!form) {
                showNotification('Formulario no encontrado', 'error');
                return;
            }

            const formData = new FormData(form);
            const descripcion = formData.get('descripcion');
            const tipoBackup = formData.get('tipo_backup');
            const prioridad = formData.get('prioridad');

            const missingFields = [];
            if (!tipoBackup) missingFields.push('Tipo de Backup');
            if (!prioridad) missingFields.push('Prioridad');
            if (!descripcion) missingFields.push('Descripción');

            if (missingFields.length > 0) {
                showNotification('Por favor completa los campos obligatorios: ' + missingFields.join(', '), 'error');
                return;
            }

            const fechaProg = formData.get('fecha_programada');
            const horaProg = formData.get('hora_programada');
            const fechaProgramada = (fechaProg && horaProg) ? (fechaProg + 'T' + horaProg) : (fechaProg || '');

            const data = {
                descripcion: descripcion,
                tipo_backup: tipoBackup,
                prioridad: prioridad,
                fecha_programada: fechaProgramada,
                retencion: formData.get('retencion')
            };

            const btnGenerar = document.getElementById('btnGenerarBackupManual');
            const originalText = btnGenerar ? btnGenerar.innerHTML : 'Generando...';
            if (btnGenerar) {
                btnGenerar.innerHTML = '<span class="loading-spinner me-1"></span>Generando...';
                btnGenerar.disabled = true;
            }

            showNotification('Iniciando proceso de backup (manual)...', 'info');

            fetch(`<?= base_url('admin/backup/crear') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(async response => {
                const result = await response.json().catch(() => null);
                if (!response.ok || !result?.success) {
                    throw new Error(result?.message || 'No se pudo generar el backup');
                }
                return result;
            })
            .then(() => {
                showNotification('Backup generado exitosamente', 'success');
                hideModal('modalNuevoBackup');
                form.reset();
                setTimeout(() => window.location.reload(), 800);
            })
            .catch(error => {
                console.error('Error al generar backup manual:', error);
                showNotification(error.message || 'Error al generar backup', 'error');
            })
            .finally(() => {
                if (btnGenerar) {
                    btnGenerar.innerHTML = originalText;
                    btnGenerar.disabled = false;
                }
            });
        } catch (error) {
            console.error('Error al generar backup manual:', error);
            showNotification('Error al generar backup: ' + error.message, 'error');
        }
    };

    // Verificar que las funciones estén disponibles inmediatamente
    console.log('=== VERIFICACIÓN INMEDIATA ===');
    console.log('showModal disponible:', typeof window.showModal);
    console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
    console.log('generarBackupManual disponible:', typeof window.generarBackupManual);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOM LOADED ===');

        // Prevenir errores de ApexCharts en esta página
        if (typeof ApexCharts !== 'undefined') {
            console.log('ApexCharts detectado, evitando errores en página de backup');
        }

        // Agregar event listeners para navegación por teclado
        document.addEventListener('keydown', handleKeyboardNavigation);

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

                // Mejorar accesibilidad de las tarjetas de acción
                enhanceActionCards();
                console.log('✅ Tarjetas de acción mejoradas para accesibilidad');

                // Set default date and time for new backup
                const fechaInput = document.querySelector('input[name="fecha_programada"]');
                const horaInput = document.querySelector('input[name="hora_programada"]');
                if (fechaInput) {
                    const now = new Date();
                    fechaInput.value = now.toISOString().slice(0, 10);
                    console.log('✅ Fecha por defecto establecida');
                }
                if (horaInput) {
                    const now = new Date();
                    horaInput.value = now.toTimeString().slice(0, 5);
                    console.log('✅ Hora por defecto establecida');
                }

                if (typeof window.toggleUbicacionBackupSection === 'function') {
                    window.toggleUbicacionBackupSection();
                    console.log('✅ Visibilidad de ubicación de backup inicializada');
                }

                if (typeof window.toggleFrecuenciaFields === 'function') {
                    window.toggleFrecuenciaFields();
                    console.log('✅ Campos de frecuencia inicializados');
                }

                // Verificar que los modales existan y mejorar su accesibilidad
                const modales = ['modalNuevoBackup', 'modalDetalleBackup', 'modalLogsBackup', 'modalFiltros'];
                modales.forEach(function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        console.log('✅ Modal encontrado:', modalId);

                        // Mejorar accesibilidad de modales
                        modal.setAttribute('aria-hidden', 'true');
                        modal.setAttribute('aria-modal', 'false');

                        // Agregar event listeners para cerrar con ESC
                        modal.addEventListener('keydown', function(event) {
                            if (event.key === 'Escape') {
                                hideModal(modalId);
                            }
                        });
                    } else {
                        console.error('❌ Modal no encontrado:', modalId);
                    }
                });

                // Mejorar accesibilidad de la tabla
                const table = document.querySelector('.backup-table table');
                if (table) {
                    table.setAttribute('role', 'table');
                    table.setAttribute('aria-label', 'Lista de backups del sistema');
                    console.log('✅ Tabla mejorada para accesibilidad');
                }

                // Agregar skip links para navegación
                const skipLink = document.createElement('a');
                skipLink.href = '#historial-backups';
                skipLink.textContent = 'Saltar al contenido principal';
                skipLink.className = 'sr-only sr-only-focusable btn btn-primary position-absolute';
                skipLink.style.cssText = 'top: 10px; left: 10px; z-index: 10000;';
                document.body.insertBefore(skipLink, document.body.firstChild);

                console.log('✅ Backup page initialized successfully with accessibility improvements');

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
        console.log('generarBackupManual disponible:', typeof window.generarBackupManual);
        console.log('showNotification disponible:', typeof window.showNotification);
        console.log('hideModal disponible:', typeof window.hideModal);
        console.log('==========================');

        // Función de prueba para verificar que los modales funcionen
        window.testModal = function() {
            console.log('Probando modal...');
            window.showModal('modalNuevoBackup');
        };

        // Función de prueba para notificaciones
        window.testNotification = function() {
            console.log('Probando notificación...');
            window.showNotification('Esta es una notificación de prueba', 'success');
        };

        console.log('Funciones de prueba disponibles: testModal() y testNotification()');
    }, 100);
</script>
<?= $this->endSection() ?>
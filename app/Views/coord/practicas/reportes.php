<?= $this->extend('coord/layouts/mainCoord') ?>

<?= $this->section('styles') ?>
<style>
    .filtros-container {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .estadisticas-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .export-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .badge-tipo {
        font-size: 0.8rem;
        padding: 0.5rem 0.8rem;
    }

    /* Pestañas tipo pill (igual que en Prácticas asignadas) */
    #reportesTabs.nav-tabs {
        border: none;
        gap: 0.5rem;
    }
    #reportesTabs .nav-link {
        border: none;
        color: #495057 !important;
        background-color: #e9ecef;
        padding: 0.6rem 1.1rem;
        border-radius: 2rem;
        font-weight: 600;
        transition: background-color 0.2s, color 0.2s;
    }
    #reportesTabs .nav-link:hover {
        background-color: #dee2e6;
        color: #212529 !important;
    }
    #reportesTabs .nav-link.active {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
    #reportesTabs .nav-link.active .badge {
        background-color: rgba(255,255,255,0.9) !important;
        color: #0d6efd !important;
    }
    #reportesTabs .nav-link i {
        opacity: 0.95;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Reportes de Prácticas
                    </h3>
                    <a href="<?= base_url('coord/practicas') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="estadisticas-card">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['totalPracticas'] ?></h2>
                            <p class="mb-0">Total Prácticas</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['practicasActivas'] ?></h2>
                            <p class="mb-0">Prácticas Activas</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['practicasFinalizadas'] ?></h2>
                            <p class="mb-0">Finalizadas</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['practicasPendientes'] ?></h2>
                            <p class="mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="filtros-container">
                    <h5 class="mb-3">
                        <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                    </h5>
                    <form method="GET" action="<?= base_url('coord/practicas/reportes') ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo de Práctica</label>
                                <select class="form-select" name="tipo_practica">
                                    <option value="">Todos los tipos</option>
                                    <option value="preprofesional" <?= (isset($filtros['tipo_practica']) && $filtros['tipo_practica'] == 'preprofesional') ? 'selected' : '' ?>>
                                        Prácticas Preprofesionales
                                    </option>
                                    <option value="servicio" <?= (isset($filtros['tipo_practica']) && $filtros['tipo_practica'] == 'servicio') ? 'selected' : '' ?>>
                                        Servicio Comunitario
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select" name="estado">
                                    <option value="">Todos los estados</option>
                                    <option value="Pendiente" <?= (isset($filtros['estado']) && $filtros['estado'] == 'Pendiente') ? 'selected' : '' ?>>
                                        Pendiente
                                    </option>
                                    <option value="En Progreso" <?= (isset($filtros['estado']) && $filtros['estado'] == 'En Progreso') ? 'selected' : '' ?>>
                                        En Progreso
                                    </option>
                                    <option value="Completada" <?= (isset($filtros['estado']) && $filtros['estado'] == 'Completada') ? 'selected' : '' ?>>
                                        Completada
                                    </option>
                                    <option value="Cancelada" <?= (isset($filtros['estado']) && $filtros['estado'] == 'Cancelada') ? 'selected' : '' ?>>
                                        Cancelada
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Institución</label>
                                <select class="form-select" name="institucion">
                                    <option value="">Todas las instituciones</option>
                                    <?php if (isset($instituciones)): ?>
                                        <?php foreach ($instituciones as $institucion): ?>
                                            <option value="<?= $institucion['ID_INSTITUCION_CONVENIO'] ?>"
                                                <?= (isset($filtros['institucion']) && $filtros['institucion'] == $institucion['ID_INSTITUCION_CONVENIO']) ? 'selected' : '' ?>>
                                                <?= $institucion['NOMBRE'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio"
                                    value="<?= $filtros['fecha_inicio'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin"
                                    value="<?= $filtros['fecha_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Carrera</label>
                                <select class="form-select" name="carrera">
                                    <option value="">Todas las carreras</option>
                                    <?php if (!empty($carreras)): ?>
                                        <?php foreach ($carreras as $carrera): ?>
                                            <option value="<?= (int) $carrera['ID_CARRERA'] ?>"
                                                <?= (isset($filtros['carrera']) && (string)$filtros['carrera'] === (string)$carrera['ID_CARRERA']) ? 'selected' : '' ?>>
                                                <?= esc($carrera['NOMBRE']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Filtrar
                                </button>
                                <a href="<?= base_url('coord/practicas/reportes') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Opciones de Exportación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-download me-2"></i>Opciones de Exportación
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="export-buttons">
                            <button class="btn btn-outline-danger" onclick="exportarFormato('pdf')">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </button>
                            <button class="btn btn-outline-success" onclick="exportarFormato('excel')">
                                <i class="fas fa-file-excel me-1"></i>Exportar Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs para diferentes tipos de prácticas (mismo estilo que Prácticas asignadas) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified px-2 py-2 bg-light rounded-3" id="reportesTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="preprofesionales-tab" data-bs-toggle="tab"
                                    data-bs-target="#preprofesionales" type="button" role="tab">
                                    <i class="fas fa-building me-2"></i>Prácticas Preprofesionales
                                    <span class="badge bg-light text-dark ms-2"><?= count($practicasPreprofesionales) ?></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="servicio-tab" data-bs-toggle="tab"
                                    data-bs-target="#servicio" type="button" role="tab">
                                    <i class="fas fa-heart me-2"></i>Servicio Comunitario
                                    <span class="badge bg-light text-dark ms-2"><?= count($serviciosComunitarios) ?></span>
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">
                    </div>
                    <div class="card-body p-0">
                        <div class="tab-content" id="reportesTabContent">
                            <!-- Prácticas Preprofesionales -->
                            <div class="tab-pane fade show active" id="preprofesionales" role="tabpanel">
                                <?php if (!empty($practicasPreprofesionales)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Estudiante</th>
                                                    <th>Institución</th>
                                                    <th>Período</th>
                                                    <th>Horas</th>
                                                    <th>Estado</th>
                                                    <th>Progreso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($practicasPreprofesionales as $practica): ?>
                                                    <tr>
                                                        <td><?= str_pad($practica['ID_PRACTICA_PREPROFESIONAL'], 3, '0', STR_PAD_LEFT) ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($practica['ESTUDIANTE_NOMBRE']) ?>&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="<?= substr($practica['ESTUDIANTE_NOMBRE'], 0, 2) ?>">
                                                                <div>
                                                                    <div class="fw-semibold"><?= $practica['ESTUDIANTE_NOMBRE'] ?></div>
                                                                    <small class="text-muted"><?= $practica['CARRERA_NOMBRE'] ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div><?= $practica['INSTITUCION_NOMBRE'] ?></div>
                                                            <small class="text-muted"><?= $practica['TIPO_INSTITUCION'] ?></small>
                                                        </td>
                                                        <td>
                                                            <div><?= date('M Y', strtotime($practica['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($practica['FECHA_FIN'])) ?></div>
                                                            <small class="text-muted"><?= $practica['HORAS_PRACTICAS'] ?>h</small>
                                                        </td>
                                                        <td><span class="badge bg-info"><?= $practica['HORAS_PRACTICAS'] ?>h</span></td>
                                                        <td>
                                                            <?php
                                                            $estadoClass = '';
                                                            switch ($practica['ESTADO_PRACTICA']) {
                                                                case 'Completada':
                                                                    $estadoClass = 'bg-success text-white';
                                                                    break;
                                                                case 'En Progreso':
                                                                    $estadoClass = 'bg-warning text-dark';
                                                                    break;
                                                                case 'Pendiente':
                                                                    $estadoClass = 'bg-info text-dark';
                                                                    break;
                                                                default:
                                                                    $estadoClass = 'bg-secondary text-white';
                                                            }
                                                            ?>
                                                            <span class="badge <?= $estadoClass ?>"><?= $practica['ESTADO_PRACTICA'] ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-success" style="width: 100%"></div>
                                                            </div>
                                                            <small class="text-muted">100%</small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No se encontraron prácticas preprofesionales</h5>
                                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Servicio Comunitario -->
                            <div class="tab-pane fade" id="servicio" role="tabpanel">
                                <?php if (!empty($serviciosComunitarios)): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Estudiante</th>
                                                    <th>Institución</th>
                                                    <th>Período</th>
                                                    <th>Horas</th>
                                                    <th>Estado</th>
                                                    <th>Progreso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($serviciosComunitarios as $servicio): ?>
                                                    <tr>
                                                        <td>SC<?= str_pad($servicio['ID_SERVICIO_COMUNITARIO'], 3, '0', STR_PAD_LEFT) ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($servicio['ESTUDIANTE_NOMBRE']) ?>&background=198754&color=fff&size=32" class="rounded-circle me-2" alt="<?= substr($servicio['ESTUDIANTE_NOMBRE'], 0, 2) ?>">
                                                                <div>
                                                                    <div class="fw-semibold"><?= $servicio['ESTUDIANTE_NOMBRE'] ?></div>
                                                                    <small class="text-muted"><?= $servicio['CARRERA_NOMBRE'] ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div><?= $servicio['INSTITUCION_NOMBRE'] ?></div>
                                                            <small class="text-muted"><?= $servicio['TIPO_INSTITUCION'] ?></small>
                                                        </td>
                                                        <td>
                                                            <div><?= date('M Y', strtotime($servicio['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($servicio['FECHA_FIN'])) ?></div>
                                                            <small class="text-muted"><?= $servicio['HORAS_SERVICIO'] ?>h</small>
                                                        </td>
                                                        <td><span class="badge bg-info text-dark"><?= $servicio['HORAS_SERVICIO'] ?>h</span></td>
                                                        <td>
                                                            <?php
                                                            $estadoClass = '';
                                                            switch ($servicio['ESTADO_SERVICIO']) {
                                                                case 'Completado':
                                                                    $estadoClass = 'bg-success text-white';
                                                                    break;
                                                                case 'En Progreso':
                                                                    $estadoClass = 'bg-warning text-dark';
                                                                    break;
                                                                case 'Pendiente':
                                                                    $estadoClass = 'bg-info text-dark';
                                                                    break;
                                                                default:
                                                                    $estadoClass = 'bg-secondary text-white';
                                                            }
                                                            ?>
                                                            <span class="badge <?= $estadoClass ?>"><?= $servicio['ESTADO_SERVICIO'] ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-info" style="width: 47%"></div>
                                                            </div>
                                                            <small class="text-muted">47%</small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No se encontraron servicios comunitarios</h5>
                                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function exportarFormato(formato) {
        // Mostrar notificación de procesamiento
        showNotification(`Generando archivo ${formato.toUpperCase()}...`, 'info');

        // Pasar los mismos filtros de la URL para que la exportación use los datos visibles
        const queryString = window.location.search || '';
        const url = '<?= base_url('coord/practicas/exportar-datos/') ?>' + formato + (queryString ? '?' + queryString.slice(1) : '');

        // Realizar la exportación
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }

                // Obtener el nombre del archivo del header Content-Disposition
                const contentDisposition = response.headers.get('Content-Disposition');
                let filename = `reporte_practicas_${new Date().toISOString().split('T')[0]}.${formato}`;

                if (contentDisposition) {
                    const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                    if (filenameMatch) {
                        filename = filenameMatch[1];
                    }
                }

                return response.blob().then(blob => ({
                    blob,
                    filename
                }));
            })
            .then(({
                blob,
                filename
            }) => {
                // Crear URL temporal para descarga
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = filename;

                // Agregar al DOM, hacer clic y remover
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                // Limpiar la URL temporal
                window.URL.revokeObjectURL(url);

                showNotification(`Archivo ${formato.toUpperCase()} descargado exitosamente`, 'success');
            })
            .catch(error => {
                console.error('Error al exportar:', error);
                showNotification(`Error al exportar archivo ${formato.toUpperCase()}: ${error.message}`, 'error');
            });
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
</script>
<?= $this->endSection() ?>
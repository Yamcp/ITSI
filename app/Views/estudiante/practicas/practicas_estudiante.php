<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    .practica-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .practica-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .practica-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }
    
    .practica-body {
        padding: 1.5rem;
    }
    
    .estado-badge {
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .progreso-circular {
        width: 80px;
        height: 80px;
        position: relative;
        margin: 0 auto;
    }
    
    .progreso-texto {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 0.9rem;
    }
    
    .accion-btn {
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .accion-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .timeline-item {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }
    
    .timeline-marker {
        position: absolute;
        left: 0;
        top: 0.25rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #667eea;
    }
    
    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 1rem;
        width: 2px;
        height: calc(100% - 0.5rem);
        background: #dee2e6;
    }
    
    .documento-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .documento-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-briefcase me-2"></i>
                    Mis Prácticas
                </h3>
            </div>
        </div>

        <!-- Estadísticas del Estudiante -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['totalPracticas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Prácticas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['practicasActivas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">En Progreso</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #0c5460 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['practicasFinalizadas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Finalizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['horasCompletadas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Horas Completadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="registrarActividad()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Registrar Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="subirDocumento()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-upload fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Subir Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verProgreso()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-line fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Ver Progreso</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="contactarSupervisor()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-comments fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Contactar Supervisor</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="practicasTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="preprofesionales-tab" data-bs-toggle="tab" data-bs-target="#preprofesionales" type="button" role="tab" aria-selected="true" style="transition: background 0.2s;">
                                    <i class="fas fa-building me-2"></i>
                                    Prácticas Preprofesionales
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="servicio-tab" data-bs-toggle="tab" data-bs-target="#servicio" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                    <i class="fas fa-heart me-2"></i>
                                    Servicio Comunitario
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Mis Documentos
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="practicasTabContent">
                            <!-- Prácticas Preprofesionales -->
                            <div class="tab-pane fade show active" id="preprofesionales" role="tabpanel">
                                <?php if (!empty($practicasPreprofesionales)): ?>
                                    <?php foreach ($practicasPreprofesionales as $practica): ?>
                                        <div class="practica-card">
                                            <div class="practica-header">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h5 class="mb-1">
                                                            <i class="fas fa-building me-2"></i>
                                                            <?= $practica['INSTITUCION_NOMBRE'] ?>
                                                        </h5>
                                                        <p class="mb-0 opacity-75"><?= $practica['PROYECTO_ESPECIFICO'] ?? 'Sin descripción específica' ?></p>
                                                    </div>
                                                    <div class="col-md-4 text-md-end">
                                                        <?php
                                                        $estadoClass = '';
                                                        switch($practica['ESTADO_PRACTICA']) {
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
                                                        <span class="estado-badge <?= $estadoClass ?>"><?= $practica['ESTADO_PRACTICA'] ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="practica-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Período:</strong><br>
                                                                <small class="text-muted">
                                                                    <?= date('d/m/Y', strtotime($practica['FECHA_INICIO'])) ?> - 
                                                                    <?= date('d/m/Y', strtotime($practica['FECHA_FIN'])) ?>
                                                                </small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Horas Totales:</strong><br>
                                                                <span class="badge bg-info"><?= $practica['HORAS_PRACTICAS'] ?>h</span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Supervisor:</strong><br>
                                                                <small class="text-muted"><?= $practica['SUPERVISOR_NOMBRE'] ?? 'No asignado' ?></small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Progreso:</strong><br>
                                                                <div class="progress" style="height: 8px;">
                                                                    <div class="progress-bar bg-success" style="width: 75%"></div>
                                                                </div>
                                                                <small class="text-muted">75% completado</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="progreso-circular">
                                                            <canvas id="progresoPre<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>" width="80" height="80"></canvas>
                                                            <div class="progreso-texto">75%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="btn-group w-100" role="group">
                                                            <button class="btn btn-outline-primary accion-btn" onclick="verDetallePractica(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')">
                                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                                            </button>
                                                            <button class="btn btn-outline-success accion-btn" onclick="registrarActividadPractica(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')">
                                                                <i class="fas fa-plus me-1"></i>Registrar
                                                            </button>
                                                            <button class="btn btn-outline-info accion-btn" onclick="verDocumentos(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')">
                                                                <i class="fas fa-file-alt me-1"></i>Documentos
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No tienes prácticas preprofesionales asignadas</h5>
                                        <p class="text-muted">Contacta con tu coordinador para más información</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Servicio Comunitario -->
                            <div class="tab-pane fade" id="servicio" role="tabpanel">
                                <?php if (!empty($serviciosComunitarios)): ?>
                                    <?php foreach ($serviciosComunitarios as $servicio): ?>
                                        <div class="practica-card">
                                            <div class="practica-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <h5 class="mb-1">
                                                            <i class="fas fa-heart me-2"></i>
                                                            <?= $servicio['INSTITUCION_NOMBRE'] ?>
                                                        </h5>
                                                        <p class="mb-0 opacity-75"><?= $servicio['PROYECTO_SOCIAL'] ?? 'Sin descripción específica' ?></p>
                                                    </div>
                                                    <div class="col-md-4 text-md-end">
                                                        <?php
                                                        $estadoClass = '';
                                                        switch($servicio['ESTADO_SERVICIO']) {
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
                                                        <span class="estado-badge <?= $estadoClass ?>"><?= $servicio['ESTADO_SERVICIO'] ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="practica-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Período:</strong><br>
                                                                <small class="text-muted">
                                                                    <?= date('d/m/Y', strtotime($servicio['FECHA_INICIO'])) ?> - 
                                                                    <?= date('d/m/Y', strtotime($servicio['FECHA_FIN'])) ?>
                                                                </small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Horas Totales:</strong><br>
                                                                <span class="badge bg-info"><?= $servicio['HORAS_SERVICIO'] ?>h</span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Supervisor:</strong><br>
                                                                <small class="text-muted"><?= $servicio['SUPERVISOR_NOMBRE'] ?? 'No asignado' ?></small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Progreso:</strong><br>
                                                                <div class="progress" style="height: 8px;">
                                                                    <div class="progress-bar bg-info" style="width: 47%"></div>
                                                                </div>
                                                                <small class="text-muted">47% completado</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="progreso-circular">
                                                            <canvas id="progresoServ<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>" width="80" height="80"></canvas>
                                                            <div class="progreso-texto">47%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="btn-group w-100" role="group">
                                                            <button class="btn btn-outline-primary accion-btn" onclick="verDetallePractica(<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                                            </button>
                                                            <button class="btn btn-outline-success accion-btn" onclick="registrarActividadPractica(<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                                <i class="fas fa-plus me-1"></i>Registrar
                                                            </button>
                                                            <button class="btn btn-outline-info accion-btn" onclick="verDocumentos(<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')">
                                                                <i class="fas fa-file-alt me-1"></i>Documentos
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No tienes servicios comunitarios asignados</h5>
                                        <p class="text-muted">Contacta con tu coordinador para más información</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Documentos -->
                            <div class="tab-pane fade" id="documentos" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="mb-3">
                                            <i class="fas fa-file-pdf me-2"></i>
                                            Documentos Requeridos
                                        </h6>
                                        <div class="documento-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                                    <strong>Carta de Presentación</strong>
                                                    <br><small class="text-muted">Documento oficial de presentación</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-success">Completado</span>
                                                    <button class="btn btn-sm btn-outline-primary ms-2">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="documento-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-word text-primary me-2"></i>
                                                    <strong>Plan de Trabajo</strong>
                                                    <br><small class="text-muted">Cronograma de actividades</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-warning">Pendiente</span>
                                                    <button class="btn btn-sm btn-outline-success ms-2">
                                                        <i class="fas fa-upload"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="documento-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-file-excel text-success me-2"></i>
                                                    <strong>Registro de Actividades</strong>
                                                    <br><small class="text-muted">Seguimiento diario</small>
                                                </div>
                                                <div>
                                                    <span class="badge bg-info">En Progreso</span>
                                                    <button class="btn btn-sm btn-outline-primary ms-2">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-3">
                                            <i class="fas fa-upload me-2"></i>
                                            Subir Nuevo Documento
                                        </h6>
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="formSubirDocumento" enctype="multipart/form-data">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipo de Documento</label>
                                                        <select class="form-select" name="tipo_documento" required>
                                                            <option value="">Seleccionar...</option>
                                                            <option value="carta_presentacion">Carta de Presentación</option>
                                                            <option value="plan_trabajo">Plan de Trabajo</option>
                                                            <option value="registro_actividades">Registro de Actividades</option>
                                                            <option value="informe_semanal">Informe Semanal</option>
                                                            <option value="informe_final">Informe Final</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Archivo</label>
                                                        <input type="file" class="form-control" name="archivo" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                                                        <div class="form-text">Formatos permitidos: PDF, DOC, DOCX, XLS, XLSX</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Descripción</label>
                                                        <textarea class="form-control" name="descripcion" rows="3" placeholder="Descripción del documento..."></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fas fa-upload me-1"></i>Subir Documento
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Práctica -->
<div class="modal fade" id="modalDetallePractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle de Práctica
                </h5>
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
                                        <p><strong>Institución:</strong> <span id="detalleInstitucion">-</span></p>
                                        <p><strong>Tipo de Práctica:</strong> <span id="detalleTipo">-</span></p>
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Supervisor:</strong> <span id="detalleSupervisor">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                        <p><strong>Horas:</strong> <span id="detalleHoras">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Descripción:</strong></p>
                                        <p class="text-muted" id="detalleDescripcion">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Actividades Recientes</h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div>
                                            <div class="fw-semibold">Desarrollo de módulo de usuarios</div>
                                            <div class="text-muted small">8 horas - 30/08/2025</div>
                                        </div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div>
                                            <div class="fw-semibold">Análisis de requerimientos</div>
                                            <div class="text-muted small">6 horas - 29/08/2025</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Progreso</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="progreso-circular">
                                    <canvas id="progressChart" width="120" height="120"></canvas>
                                    <div class="progreso-texto" style="font-size: 1.1rem;">75%</div>
                                </div>
                                <h5 class="mt-3" id="progressHours">180 de 240 horas</h5>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Contacto</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Supervisor:</strong><br>
                                <small class="text-muted" id="contactoSupervisor">Juan Pérez</small></p>
                                <p><strong>Email:</strong><br>
                                <small class="text-muted" id="contactoEmail">juan.perez@institucion.com</small></p>
                                <p><strong>Teléfono:</strong><br>
                                <small class="text-muted" id="contactoTelefono">0987654321</small></p>
                                <button class="btn btn-primary btn-sm w-100 mt-2">
                                    <i class="fas fa-envelope me-1"></i>Enviar Mensaje
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="registrarActividadPractica()">
                    <i class="fas fa-plus me-1"></i>Registrar Actividad
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Actividad -->
<div class="modal fade" id="modalRegistrarActividad" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Registrar Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarActividad" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="fecha_actividad" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Entrada <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_entrada" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Salida <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_salida" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Actividades Realizadas <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="actividades" rows="4" placeholder="Describe las actividades realizadas..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarActividad()">
                    <i class="fas fa-save me-1"></i>Guardar Actividad
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let practicaActual = null;

    // Funciones principales
    function verDetallePractica(id, tipo) {
        practicaActual = { id: id, tipo: tipo };
        
        // Simular carga de datos
        document.getElementById('detalleInstitucion').textContent = 'Hospital San Vicente de Paúl';
        document.getElementById('detalleTipo').textContent = tipo === 'preprofesional' ? 'Preprofesional' : 'Servicio Comunitario';
        document.getElementById('detallePeriodo').textContent = '01/08/2025 - 30/11/2025';
        document.getElementById('detalleSupervisor').textContent = 'Dr. Juan Pérez';
        document.getElementById('detalleEstado').textContent = 'En Progreso';
        document.getElementById('detalleHoras').textContent = '180 de 240 horas';
        document.getElementById('detalleDescripcion').textContent = 'Desarrollo de sistema de gestión hospitalaria';
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetallePractica'));
        modal.show();
        
        // Dibujar gráfico de progreso
        setTimeout(() => {
            drawProgressChart(75);
        }, 100);
    }

    function registrarActividad() {
        const modal = new bootstrap.Modal(document.getElementById('modalRegistrarActividad'));
        modal.show();
    }

    function registrarActividadPractica(id, tipo) {
        practicaActual = { id: id, tipo: tipo };
        registrarActividad();
    }

    function subirDocumento() {
        showNotification('Función de subida de documentos en desarrollo', 'info');
    }

    function verProgreso() {
        showNotification('Mostrando progreso detallado...', 'info');
    }

    function contactarSupervisor() {
        showNotification('Abriendo chat con supervisor...', 'info');
    }

    function verDocumentos(id, tipo) {
        showNotification('Mostrando documentos de la práctica...', 'info');
    }

    function guardarActividad() {
        const form = document.getElementById('formRegistrarActividad');
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Simular guardado
        showNotification('Actividad registrada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalRegistrarActividad')).hide();
        form.reset();
        form.classList.remove('was-validated');
    }

    function drawProgressChart(percentage) {
        const canvas = document.getElementById('progressChart');
        if (!canvas) return;
        
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 50;

        // Limpiar canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Círculo de fondo
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 8;
        ctx.stroke();

        // Círculo de progreso
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
        ctx.strokeStyle = '#667eea';
        ctx.lineWidth = 8;
        ctx.lineCap = 'round';
        ctx.stroke();
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

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer fecha actual por defecto
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="fecha_actividad"]').value = today;
        
        // Dibujar gráficos de progreso para las prácticas existentes
        setTimeout(() => {
            document.querySelectorAll('[id^="progresoPre"], [id^="progresoServ"]').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;
                const radius = 30;
                const percentage = canvas.id.includes('Pre') ? 75 : 47;

                // Círculo de fondo
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                ctx.strokeStyle = '#e9ecef';
                ctx.lineWidth = 6;
                ctx.stroke();

                // Círculo de progreso
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
                ctx.strokeStyle = canvas.id.includes('Pre') ? '#28a745' : '#17a2b8';
                ctx.lineWidth = 6;
                ctx.lineCap = 'round';
                ctx.stroke();
            });
        }, 100);
    });

    // Manejo de formulario de subida de documentos
    document.getElementById('formSubirDocumento').addEventListener('submit', function(e) {
        e.preventDefault();
        showNotification('Documento subido exitosamente', 'success');
        this.reset();
    });
</script>
<?= $this->endSection() ?>

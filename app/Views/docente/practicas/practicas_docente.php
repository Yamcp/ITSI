<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
<style>
    .supervision-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .supervision-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .supervision-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }
    
    .supervision-body {
        padding: 1.5rem;
    }
    
    .estudiante-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
    
    .evaluacion-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .evaluacion-item:hover {
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
    
    .alert-item {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.5rem;
    }
    
    .alert-item.warning {
        background: #fff3cd;
        border-color: #ffeaa7;
    }
    
    .alert-item.danger {
        background: #f8d7da;
        border-color: #f5c6cb;
    }
    
    .alert-item.success {
        background: #d4edda;
        border-color: #c3e6cb;
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
                    Supervisión de Prácticas
                </h3>
            </div>
        </div>

        <!-- Estadísticas del Docente -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['estudiantesAsignados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Estudiantes Asignados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['practicasActivas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Prácticas Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #0c5460 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['evaluacionesPendientes'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Evaluaciones Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card stats-card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%);">
                    <div class="card-body">
                        <h2 class="card-title mb-2" style="font-size:2.5rem;"><?= $estadisticas['alertas'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Alertas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="evaluarEstudiante()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-star fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Evaluar Estudiante</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporte()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCalendario()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="configurarAlertas()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-bell fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Configurar Alertas</div>
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
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="supervisionTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="estudiantes-tab" data-bs-toggle="tab" data-bs-target="#estudiantes" type="button" role="tab" aria-selected="true" style="transition: background 0.2s;">
                                    <i class="fas fa-users me-2"></i>
                                    Mis Estudiantes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="evaluaciones-tab" data-bs-toggle="tab" data-bs-target="#evaluaciones" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                    <i class="fas fa-star me-2"></i>
                                    Evaluaciones
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-warning" id="alertas-tab" data-bs-toggle="tab" data-bs-target="#alertas" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Alertas
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="reportes-tab" data-bs-toggle="tab" data-bs-target="#reportes" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Reportes
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="supervisionTabContent">
                            <!-- Mis Estudiantes -->
                            <div class="tab-pane fade show active" id="estudiantes" role="tabpanel">
                                <?php if (!empty($estudiantesAsignados)): ?>
                                    <?php foreach ($estudiantesAsignados as $estudiante): ?>
                                        <div class="supervision-card">
                                            <div class="supervision-header">
                                                <div class="row align-items-center">
                                                    <div class="col-md-8">
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($estudiante['NOMBRE_COMPLETO']) ?>&background=0d6efd&color=fff&size=50" class="estudiante-avatar me-3" alt="<?= substr($estudiante['NOMBRE_COMPLETO'], 0, 2) ?>">
                                                            <div>
                                                                <h5 class="mb-1"><?= $estudiante['NOMBRE_COMPLETO'] ?></h5>
                                                                <p class="mb-0 opacity-75"><?= $estudiante['CARRERA'] ?> - <?= $estudiante['INSTITUCION_NOMBRE'] ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-md-end">
                                                        <?php
                                                        $estadoClass = '';
                                                        switch($estudiante['ESTADO_PRACTICA']) {
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
                                                        <span class="estado-badge <?= $estadoClass ?>"><?= $estudiante['ESTADO_PRACTICA'] ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="supervision-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <strong>Período:</strong><br>
                                                                <small class="text-muted">
                                                                    <?= date('d/m/Y', strtotime($estudiante['FECHA_INICIO'])) ?> - 
                                                                    <?= date('d/m/Y', strtotime($estudiante['FECHA_FIN'])) ?>
                                                                </small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Horas:</strong><br>
                                                                <span class="badge bg-info"><?= $estudiante['HORAS_CUMPLIDAS'] ?>/<?= $estudiante['HORAS_TOTALES'] ?>h</span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <strong>Última Actividad:</strong><br>
                                                                <small class="text-muted"><?= $estudiante['ULTIMA_ACTIVIDAD'] ?? 'Sin actividades' ?></small>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <strong>Progreso:</strong><br>
                                                                <div class="progress" style="height: 8px;">
                                                                    <div class="progress-bar bg-success" style="width: <?= $estudiante['PORCENTAJE_PROGRESO'] ?>%"></div>
                                                                </div>
                                                                <small class="text-muted"><?= $estudiante['PORCENTAJE_PROGRESO'] ?>% completado</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 text-center">
                                                        <div class="progreso-circular">
                                                            <canvas id="progresoEst<?= $estudiante['ID_ESTUDIANTE'] ?>" width="80" height="80"></canvas>
                                                            <div class="progreso-texto"><?= $estudiante['PORCENTAJE_PROGRESO'] ?>%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="btn-group w-100" role="group">
                                                            <button class="btn btn-outline-primary accion-btn" onclick="verDetalleEstudiante(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-eye me-1"></i>Ver Detalle
                                                            </button>
                                                            <button class="btn btn-outline-success accion-btn" onclick="evaluarEstudiante(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-star me-1"></i>Evaluar
                                                            </button>
                                                            <button class="btn btn-outline-info accion-btn" onclick="enviarMensaje(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-comment me-1"></i>Mensaje
                                                            </button>
                                                            <button class="btn btn-outline-warning accion-btn" onclick="verActividades(<?= $estudiante['ID_ESTUDIANTE'] ?>)">
                                                                <i class="fas fa-list me-1"></i>Actividades
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No tienes estudiantes asignados</h5>
                                        <p class="text-muted">Contacta con el administrador para asignaciones</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Evaluaciones -->
                            <div class="tab-pane fade" id="evaluaciones" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-3">
                                            <i class="fas fa-star me-2"></i>
                                            Evaluaciones Pendientes
                                        </h6>
                                        <?php if (!empty($evaluacionesPendientes)): ?>
                                            <?php foreach ($evaluacionesPendientes as $evaluacion): ?>
                                                <div class="evaluacion-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong><?= $evaluacion['ESTUDIANTE_NOMBRE'] ?></strong>
                                                            <br><small class="text-muted"><?= $evaluacion['TIPO_EVALUACION'] ?> - <?= $evaluacion['INSTITUCION_NOMBRE'] ?></small>
                                                        </div>
                                                        <div>
                                                            <span class="badge bg-warning">Pendiente</span>
                                                            <button class="btn btn-sm btn-primary ms-2" onclick="realizarEvaluacion(<?= $evaluacion['ID_EVALUACION'] ?>)">
                                                                <i class="fas fa-edit"></i> Evaluar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="text-center py-4">
                                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                                <p class="text-muted">No hay evaluaciones pendientes</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-3">
                                            <i class="fas fa-chart-pie me-2"></i>
                                            Resumen de Evaluaciones
                                        </h6>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Completadas</span>
                                                        <span class="fw-bold text-success"><?= $estadisticas['evaluacionesCompletadas'] ?? 0 ?></span>
                                                    </div>
                                                    <div class="progress mt-1" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: 70%"></div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Pendientes</span>
                                                        <span class="fw-bold text-warning"><?= $estadisticas['evaluacionesPendientes'] ?? 0 ?></span>
                                                    </div>
                                                    <div class="progress mt-1" style="height: 6px;">
                                                        <div class="progress-bar bg-warning" style="width: 30%"></div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Promedio General</span>
                                                        <span class="fw-bold text-primary">8.5/10</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alertas -->
                            <div class="tab-pane fade" id="alertas" role="tabpanel">
                                <h6 class="mb-3">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Alertas y Notificaciones
                                </h6>
                                
                                <div class="alert-item warning">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-triangle text-warning me-3 mt-1"></i>
                                        <div>
                                            <strong>Estudiante con retraso</strong>
                                            <p class="mb-1">Ana Yandun no ha registrado actividades en 3 días</p>
                                            <small class="text-muted">Hace 2 horas</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-item danger">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-exclamation-circle text-danger me-3 mt-1"></i>
                                        <div>
                                            <strong>Documento faltante</strong>
                                            <p class="mb-1">Pedro Aguirre debe entregar informe semanal</p>
                                            <small class="text-muted">Hace 1 día</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-item success">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                        <div>
                                            <strong>Práctica completada</strong>
                                            <p class="mb-1">Yamilex Campues finalizó su práctica preprofesional</p>
                                            <small class="text-muted">Hace 3 horas</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="alert-item warning">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-clock text-warning me-3 mt-1"></i>
                                        <div>
                                            <strong>Evaluación próxima a vencer</strong>
                                            <p class="mb-1">Evaluación de María González vence en 2 días</p>
                                            <small class="text-muted">Hace 4 horas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reportes -->
                            <div class="tab-pane fade" id="reportes" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="mb-3">
                                            <i class="fas fa-chart-bar me-2"></i>
                                            Generar Reportes
                                        </h6>
                                        <div class="card">
                                            <div class="card-body">
                                                <form id="formGenerarReporte">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tipo de Reporte</label>
                                                        <select class="form-select" name="tipo_reporte" required>
                                                            <option value="">Seleccionar...</option>
                                                            <option value="progreso_estudiantes">Progreso de Estudiantes</option>
                                                            <option value="evaluaciones_periodo">Evaluaciones por Período</option>
                                                            <option value="actividades_realizadas">Actividades Realizadas</option>
                                                            <option value="documentos_entregados">Documentos Entregados</option>
                                                        </select>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Fecha Desde</label>
                                                                <input type="date" class="form-control" name="fecha_desde" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Fecha Hasta</label>
                                                                <input type="date" class="form-control" name="fecha_hasta" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Formato</label>
                                                        <select class="form-select" name="formato" required>
                                                            <option value="pdf">PDF</option>
                                                            <option value="excel">Excel</option>
                                                            <option value="word">Word</option>
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary w-100">
                                                        <i class="fas fa-download me-1"></i>Generar Reporte
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-3">
                                            <i class="fas fa-chart-line me-2"></i>
                                            Estadísticas Rápidas
                                        </h6>
                                        <div class="card">
                                            <div class="card-body">
                                                <canvas id="estadisticasChart" width="400" height="200"></canvas>
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

<!-- Modal Detalle de Estudiante -->
<div class="modal fade" id="modalDetalleEstudiante" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-graduate me-2"></i>
                    Detalle del Estudiante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Información del Estudiante</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> <span id="detalleNombre">-</span></p>
                                        <p><strong>Carrera:</strong> <span id="detalleCarrera">-</span></p>
                                        <p><strong>Institución:</strong> <span id="detalleInstitucion">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                        <p><strong>Progreso:</strong> <span id="detalleProgreso">-</span></p>
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
                                <h6 class="mb-0">Acciones Rápidas</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" onclick="evaluarEstudiante()">
                                        <i class="fas fa-star me-1"></i>Evaluar
                                    </button>
                                    <button class="btn btn-success" onclick="enviarMensaje()">
                                        <i class="fas fa-comment me-1"></i>Enviar Mensaje
                                    </button>
                                    <button class="btn btn-info" onclick="verActividades()">
                                        <i class="fas fa-list me-1"></i>Ver Actividades
                                    </button>
                                    <button class="btn btn-warning" onclick="generarReporte()">
                                        <i class="fas fa-file-alt me-1"></i>Reporte Individual
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Evaluar Estudiante -->
<div class="modal fade" id="modalEvaluarEstudiante" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-star me-2"></i>
                    Evaluar Estudiante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEvaluarEstudiante" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Criterio de Evaluación</label>
                                <select class="form-select" name="criterio" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="puntualidad">Puntualidad</option>
                                    <option value="responsabilidad">Responsabilidad</option>
                                    <option value="conocimientos">Conocimientos Técnicos</option>
                                    <option value="iniciativa">Iniciativa</option>
                                    <option value="trabajo_equipo">Trabajo en Equipo</option>
                                    <option value="comunicacion">Comunicación</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Calificación (1-10)</label>
                                <input type="number" class="form-control" name="calificacion" min="1" max="10" step="0.1" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios</label>
                        <textarea class="form-control" name="comentarios" rows="4" placeholder="Comentarios sobre el desempeño del estudiante..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Recomendaciones</label>
                        <textarea class="form-control" name="recomendaciones" rows="3" placeholder="Recomendaciones para mejorar..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarEvaluacion()">
                    <i class="fas fa-save me-1"></i>Guardar Evaluación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let estudianteActual = null;

    // Funciones principales
    function verDetalleEstudiante(id) {
        estudianteActual = id;
        
        // Simular carga de datos
        document.getElementById('detalleNombre').textContent = 'Ana Yandun';
        document.getElementById('detalleCarrera').textContent = 'Desarrollo de Software';
        document.getElementById('detalleInstitucion').textContent = 'Hospital San Vicente de Paúl';
        document.getElementById('detallePeriodo').textContent = '01/08/2025 - 30/11/2025';
        document.getElementById('detalleEstado').textContent = 'En Progreso';
        document.getElementById('detalleProgreso').textContent = '75% (180 de 240 horas)';
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleEstudiante'));
        modal.show();
        
        // Dibujar gráfico de progreso
        setTimeout(() => {
            drawProgressChart(75);
        }, 100);
    }

    function evaluarEstudiante(id = null) {
        if (id) estudianteActual = id;
        
        const modal = new bootstrap.Modal(document.getElementById('modalEvaluarEstudiante'));
        modal.show();
    }

    function generarReporte() {
        showNotification('Generando reporte...', 'info');
    }

    function verCalendario() {
        showNotification('Abriendo calendario de actividades...', 'info');
    }

    function configurarAlertas() {
        showNotification('Abriendo configuración de alertas...', 'info');
    }

    function enviarMensaje(id) {
        showNotification('Abriendo chat con el estudiante...', 'info');
    }

    function verActividades(id) {
        showNotification('Mostrando actividades del estudiante...', 'info');
    }

    function realizarEvaluacion(id) {
        estudianteActual = id;
        evaluarEstudiante();
    }

    function guardarEvaluacion() {
        const form = document.getElementById('formEvaluarEstudiante');
        
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Simular guardado
        showNotification('Evaluación guardada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalEvaluarEstudiante')).hide();
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
        // Dibujar gráficos de progreso para los estudiantes
        setTimeout(() => {
            document.querySelectorAll('[id^="progresoEst"]').forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const centerX = canvas.width / 2;
                const centerY = canvas.height / 2;
                const radius = 30;
                const percentage = 75; // Simular porcentaje

                // Círculo de fondo
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
                ctx.strokeStyle = '#e9ecef';
                ctx.lineWidth = 6;
                ctx.stroke();

                // Círculo de progreso
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
                ctx.strokeStyle = '#28a745';
                ctx.lineWidth = 6;
                ctx.lineCap = 'round';
                ctx.stroke();
            });
        }, 100);
    });

    // Manejo de formulario de reportes
    document.getElementById('formGenerarReporte').addEventListener('submit', function(e) {
        e.preventDefault();
        showNotification('Generando reporte...', 'info');
    });
</script>
<?= $this->endSection() ?>

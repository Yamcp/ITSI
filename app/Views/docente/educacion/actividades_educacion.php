<?= $this->extend('docente/layouts/mainDocente') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
<style>
    /* Estilos para el calendario */
    .fc-toolbar-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        text-transform: capitalize !important;
    }

    .fc-button {
        background-color: #007bff !important;
        border-color: #007bff !important;
        color: white !important;
        font-weight: 500 !important;
    }

    .fc-button:hover {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }

    .fc-button:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
    }

    .fc-button-active {
        background-color: #0056b3 !important;
        border-color: #0056b3 !important;
    }

    .fc-daygrid-day-number {
        color: #2c3e50 !important;
        font-weight: 500 !important;
    }

    .fc-day-today {
        background-color: #e3f2fd !important;
    }

    .fc-event {
        border-radius: 4px !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
    }

    /* Estilos para evaluaciones integradas */
    .evaluation-card {
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }

    .evaluation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .evaluation-card.satisfaccion {
        border-left-color: #28a745;
    }

    .evaluation-card.instructores {
        border-left-color: #ffc107;
    }

    .evaluation-card.practicas {
        border-left-color: #17a2b8;
    }

    .evaluation-card.cursos {
        border-left-color: #6f42c1;
    }

    .evaluation-card.comunidad {
        border-left-color: #fd7e14;
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-activo {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactivo {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-vencido {
        background-color: #fff3cd;
        color: #856404;
    }

    .btn-evaluacion {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-evaluacion:hover {
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
    }

    .urgent-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffc107;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Educación Continua: Mis Cursos y Evaluaciones
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Mis Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="cursosActivos" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Cursos Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #0f6674 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="talleresActivos" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Talleres Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #6f42c1 80%, #4a2c7a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="seminariosActivos" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Seminarios Activos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="<?= base_url('docente/actividades-educacion/crear') ?>" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nueva Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCalendario()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporteEvaluaciones()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Mis Reportes</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarMisActividades()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Mis Datos</div>
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
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="actividadesTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="cursos-tab" data-bs-toggle="tab" data-bs-target="#cursos" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-book me-2"></i>Mis Cursos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="talleres-tab" data-bs-toggle="tab" data-bs-target="#talleres" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-tools me-2"></i>Mis Talleres
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="seminarios-tab" data-bs-toggle="tab" data-bs-target="#seminarios" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-users me-2"></i>Mis Seminarios
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-primary" id="evaluaciones-tab" data-bs-toggle="tab" data-bs-target="#evaluaciones" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-clipboard-check me-2"></i>Evaluaciones
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <!-- Cursos -->
                            <div class="tab-pane fade show active" id="cursos" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-primary text-white">
                                        <span><i class="fas fa-book me-2"></i>Mis Cursos</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Estado</th>
                                                        <th>Participantes</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaCursos">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Curso'): ?>
                                                                <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                                    <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fas fa-laptop-code fa-2x me-2 text-primary"></i>
                                                                            <div>
                                                                                <div class="fw-semibold"><?= $actividad['NOMBRE_ACTIVIDAD'] ?></div>
                                                                                <small class="text-muted"><?= $actividad['DESCRIPCION'] ?></small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span></td>
                                                                    <td>
                                                                        <div><?= date('M Y', strtotime($actividad['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($actividad['FECHA_FIN'])) ?></div>
                                                                        <small class="text-muted"><?= $actividad['DURACION_HORAS'] ?> horas</small>
                                                                    </td>
                                                                    <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                                    <td>
                                                                        <?php
                                                                        $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                                        $hoy = new DateTime();
                                                                        if ($fechaFin >= $hoy) {
                                                                            echo '<span class="badge bg-success">Activo</span>';
                                                                        } else {
                                                                            echo '<span class="badge bg-secondary">Finalizado</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-primary">15</span>
                                                                        <small class="text-muted">inscritos</small>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('docente/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('docente/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <button class="btn btn-outline-info" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Participantes">
                                                                                <i class="fas fa-users"></i>
                                                                            </button>
                                                                            <a href="<?= base_url('docente/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar esta actividad? Esta acción no se puede deshacer.');">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No tienes cursos registrados</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Talleres -->
                            <div class="tab-pane fade" id="talleres" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white">
                                        <span><i class="fas fa-tools me-2"></i>Mis Talleres</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Estado</th>
                                                        <th>Participantes</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaTalleres">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Taller'): ?>
                                                                <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                                    <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fas fa-wrench fa-2x me-2 text-success"></i>
                                                                            <div>
                                                                                <div class="fw-semibold"><?= $actividad['NOMBRE_ACTIVIDAD'] ?></div>
                                                                                <small class="text-muted"><?= $actividad['DESCRIPCION'] ?></small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><span class="badge bg-warning text-dark"><?= $actividad['MODALIDAD'] ?></span></td>
                                                                    <td>
                                                                        <div><?= date('M Y', strtotime($actividad['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($actividad['FECHA_FIN'])) ?></div>
                                                                        <small class="text-muted"><?= $actividad['DURACION_HORAS'] ?> horas</small>
                                                                    </td>
                                                                    <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                                    <td>
                                                                        <?php
                                                                        $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                                        $hoy = new DateTime();
                                                                        if ($fechaFin >= $hoy) {
                                                                            echo '<span class="badge bg-warning text-dark">Activo</span>';
                                                                        } else {
                                                                            echo '<span class="badge bg-secondary">Finalizado</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-success"><?= (int)($conteoParticipantes[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? 0) ?></span>
                                                                        <small class="text-muted">inscritos</small>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('docente/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('docente/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <button class="btn btn-outline-info" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Participantes">
                                                                                <i class="fas fa-users"></i>
                                                                            </button>
                                                                            <a href="<?= base_url('docente/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar esta actividad? Esta acción no se puede deshacer.');">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No tienes talleres registrados</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Seminarios -->
                            <div class="tab-pane fade" id="seminarios" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-info text-white">
                                        <span><i class="fas fa-users me-2"></i>Mis Seminarios</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Estado</th>
                                                        <th>Participantes</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaSeminarios">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Seminario'): ?>
                                                                <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                                    <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fas fa-comments fa-2x me-2 text-info"></i>
                                                                            <div>
                                                                                <div class="fw-semibold"><?= $actividad['NOMBRE_ACTIVIDAD'] ?></div>
                                                                                <small class="text-muted"><?= $actividad['DESCRIPCION'] ?></small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td><span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span></td>
                                                                    <td>
                                                                        <div><?= date('M Y', strtotime($actividad['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($actividad['FECHA_FIN'])) ?></div>
                                                                        <small class="text-muted"><?= $actividad['DURACION_HORAS'] ?> horas</small>
                                                                    </td>
                                                                    <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                                    <td>
                                                                        <?php
                                                                        $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                                        $hoy = new DateTime();
                                                                        if ($fechaFin >= $hoy) {
                                                                            echo '<span class="badge bg-secondary">Programado</span>';
                                                                        } else {
                                                                            echo '<span class="badge bg-secondary">Finalizado</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-info"><?= (int)($conteoParticipantes[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? 0) ?></span>
                                                                        <small class="text-muted">inscritos</small>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('docente/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('docente/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                            <button class="btn btn-outline-info" onclick="gestionarParticipantes(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Participantes">
                                                                                <i class="fas fa-users"></i>
                                                                            </button>
                                                                            <a href="<?= base_url('docente/actividades-educacion/eliminar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-danger" title="Eliminar" onclick="return confirm('¿Está seguro de eliminar esta actividad? Esta acción no se puede deshacer.');">
                                                                                <i class="fas fa-trash-alt"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No tienes seminarios registrados</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Evaluaciones -->
                            <div class="tab-pane fade" id="evaluaciones" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body p-4">
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <h4 class="text-center mb-1">
                                                    <i class="fas fa-clipboard-check me-2"></i> Evaluaciones
                                                </h4>
                                                <p class="text-center text-muted mb-0">
                                                    Completa los formularios de evaluación relacionados con tus actividades.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Alerta de Evaluaciones Urgentes -->
                                        <div class="row mb-4" id="alertaUrgentesEvaluacionesDocenteEdu" style="display: none;">
                                            <div class="col-12">
                                                <div class="urgent-card">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-exclamation-triangle text-warning me-3" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <h6 class="mb-1">Evaluaciones Próximas a Vencer</h6>
                                                            <p class="mb-0 text-muted">
                                                                Tienes formularios que vencen pronto. Te recomendamos completarlos a la brevedad.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Estadísticas -->
                                        <div class="row mb-4">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                                                    <div class="card-body">
                                                        <h2 class="card-title mb-2" id="totalEvaluacionesEduDocente" style="font-size:2.5rem;">0</h2>
                                                        <p class="card-text fw-bold" style="color: #e0e0e0;">Evaluaciones Disponibles</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                                                    <div class="card-body">
                                                        <h2 class="card-title mb-2" id="evaluacionesActivasEduDocente" style="font-size:2.5rem;">0</h2>
                                                        <p class="card-text fw-bold" style="color: #e0e0e0;">Activas</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                                                    <div class="card-body">
                                                        <h2 class="card-title mb-2" id="evaluacionesPendientesEduDocente" style="font-size:2.5rem;">0</h2>
                                                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lista de Evaluaciones -->
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card shadow-sm border-0">
                                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                        <span>
                                                            <i class="fas fa-clipboard-check me-2"></i>
                                                            Formularios de Evaluación
                                                        </span>
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-light btn-sm" onclick="cambiarVistaEvaluacionesEducacionDocente('grid')">
                                                                <i class="fas fa-th-large me-1"></i>Grid
                                                            </button>
                                                            <button class="btn btn-light btn-sm" onclick="cambiarVistaEvaluacionesEducacionDocente('list')">
                                                                <i class="fas fa-list me-1"></i>Lista
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- Vista Grid -->
                                                        <div id="vistaGridEvaluacionesEduDocente" class="row g-3"></div>

                                                        <!-- Vista Lista -->
                                                        <div id="vistaListaEvaluacionesEduDocente" class="d-none">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped align-middle">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Evaluación</th>
                                                                            <th>Tipo</th>
                                                                            <th>Curso</th>
                                                                            <th>Estado</th>
                                                                            <th>Vencimiento</th>
                                                                            <th>Acción</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaEvaluacionesListaEduDocente"></tbody>
                                                                </table>
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
            </div>
        </div>
    </div>
</div>

<!-- Modal Calendario de Actividades -->
<div class="modal fade" id="modalCalendario" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Mi Calendario de Actividades
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Filtros por tipo de actividad -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="btn-group" role="group">
                            <input type="checkbox" class="btn-check" id="filtroCursos" checked>
                            <label class="btn btn-outline-primary" for="filtroCursos">
                                <i class="fas fa-book me-1"></i>Cursos
                            </label>

                            <input type="checkbox" class="btn-check" id="filtroTalleres" checked>
                            <label class="btn btn-outline-success" for="filtroTalleres">
                                <i class="fas fa-tools me-1"></i>Talleres
                            </label>

                            <input type="checkbox" class="btn-check" id="filtroSeminarios" checked>
                            <label class="btn btn-outline-info" for="filtroSeminarios">
                                <i class="fas fa-users me-1"></i>Seminarios
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Calendario -->
                <div id="calendario" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="exportarCalendario()">
                    <i class="fas fa-download me-1"></i>Exportar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let actividadesData = [];
    let estadisticas = {};

    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verCalendario() {
        showModal('modalCalendario');
        // Inicializar calendario después de que se abra el modal
        setTimeout(() => {
            cargarDatosCalendario();
        }, 300);
    }

    // Cargar datos del calendario desde la API
    async function cargarDatosCalendario() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/calendario') ?>');
            const eventos = await response.json();
            inicializarCalendario(eventos);
        } catch (error) {
            console.error('Error al cargar datos del calendario:', error);
            showNotification('Error al cargar el calendario', 'error');
        }
    }

    function inicializarCalendario(eventos) {
        const calendarEl = document.getElementById('calendario');

        if (!calendarEl) {
            console.error('Elemento calendario no encontrado');
            return;
        }

        // Limpiar contenido previo
        calendarEl.innerHTML = '';

        try {
            // Crear el calendario
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: eventos || [],
                eventClick: function(info) {
                    mostrarDetalleEvento(info.event);
                },
                height: 'auto',
                dayMaxEvents: true,
                moreLinkClick: 'popover',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día',
                    list: 'Lista'
                }
            });

            calendar.render();

            // Guardar referencia global del calendario
            window.calendario = calendar;

            console.log('Calendario inicializado correctamente');

        } catch (error) {
            console.error('Error al inicializar el calendario:', error);
            calendarEl.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Error al cargar el calendario: ${error.message}
                </div>
            `;
        }
    }

    function gestionarParticipantes(id) {
        window.location.href = '<?= base_url('docente/actividades-educacion/participantes/') ?>' + id;
    }

    function generarReporteEvaluaciones() {
        // Redirigir a la página de reportes del docente
        window.location.href = '<?= base_url('docente/actividades-educacion/reportes') ?>';
    }

    function exportarMisActividades() {
        window.location.href = '<?= base_url('docente/actividades-educacion/reportes') ?>';
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
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
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

    // Cargar estadísticas desde la API
    async function cargarEstadisticas() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/api/estadisticas') ?>');
            const stats = await response.json();

            // Actualizar las estadísticas en la interfaz
            document.getElementById('totalActividades').textContent = stats.totalActividades || 0;
            document.getElementById('cursosActivos').textContent = stats.cursosActivos || 0;
            document.getElementById('talleresActivos').textContent = stats.talleresActivos || 0;
            document.getElementById('seminariosActivos').textContent = stats.seminariosActivos || 0;

            estadisticas = stats;
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    /**
     * Polling para que, cuando el coordinador agregue el enlace de satisfacción,
     * el docente lo vea automáticamente en la tabla (sin recargar la página).
     */
    async function actualizarEnlacesSatisfaccionDocente() {
        try {
            const response = await fetch('<?= base_url('docente/actividades-educacion/api/encuestas-satisfaccion') ?>', { cache: 'no-store' });
            const payload = await response.json();
            if (!payload.success) return;

            const enlacesPorActividad = payload.data || {};
            const hoy = new Date().toISOString().split('T')[0];

            document.querySelectorAll('tr[data-actividad-id]').forEach(tr => {
                const idActividad = String(tr.dataset.actividadId || '');
                const fechaFin = String(tr.dataset.fechaFin || '');
                if (!idActividad || !fechaFin) return;

                const fechaFinSolo = fechaFin.slice(0, 10);
                const finalizado = fechaFinSolo < hoy;
                const enlace = enlacesPorActividad[idActividad]?.ENLACE_FORMULARIO || null;

                // Mostrar/ocultar fila: activos siempre; finalizados solo si ya hay enlace.
                tr.style.display = (!finalizado || (finalizado && enlace)) ? '' : 'none';

                const btnGroup = tr.querySelector('.btn-group');
                if (!btnGroup) return;

                const idLink = `doc-encuesta-link-${idActividad}`;
                const enlaceExistente = document.getElementById(idLink);

                if (finalizado && enlace) {
                    if (!enlaceExistente) {
                        const a = document.createElement('a');
                        a.id = idLink;
                        a.target = '_blank';
                        a.rel = 'noopener';
                        a.className = 'btn btn-outline-success btn-sm';
                        a.innerHTML = '<i class="fas fa-external-link-alt me-1"></i>Abrir encuesta';
                        btnGroup.appendChild(a);
                    }
                    const link = document.getElementById(idLink);
                    link.href = enlace;
                } else {
                    if (enlaceExistente) {
                        enlaceExistente.remove();
                    }
                }
            });
        } catch (e) {
            console.error('Error al actualizar enlaces satisfacción (docente):', e);
        }
    }

    function iniciarPollingEncuestasSatisfaccionDocente() {
        actualizarEnlacesSatisfaccionDocente();
        setInterval(actualizarEnlacesSatisfaccionDocente, 15000); // 15s
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar estadísticas al cargar la página
        cargarEstadisticas();
        iniciarPollingEncuestasSatisfaccionDocente();
    });
</script>

<script>
    // ===== Evaluaciones integradas en Educación Continua (Docente) =====
    let evaluacionesDocenteEdu = [];
    let vistaEvaluacionesDocenteEdu = 'grid';

    function cambiarVistaEvaluacionesEducacionDocente(tipo) {
        vistaEvaluacionesDocenteEdu = tipo;

        const grid = document.getElementById('vistaGridEvaluacionesEduDocente');
        const lista = document.getElementById('vistaListaEvaluacionesEduDocente');
        if (!grid || !lista) return;

        if (tipo === 'grid') {
            grid.classList.remove('d-none');
            lista.classList.add('d-none');
            generarVistaGridEvaluacionesEducacionDocente();
        } else {
            grid.classList.add('d-none');
            lista.classList.remove('d-none');
            generarVistaListaEvaluacionesEducacionDocente();
        }
    }

    function generarVistaGridEvaluacionesEducacionDocente() {
        const container = document.getElementById('vistaGridEvaluacionesEduDocente');
        if (!container) return;

        container.innerHTML = '';

        if (evaluacionesDocenteEdu.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay evaluaciones disponibles</h5>
                    <p class="text-muted mb-0">En este momento no tienes formularios pendientes.</p>
                </div>
            `;
            return;
        }

        evaluacionesDocenteEdu.forEach(evalItem => {
            const fechaVencimiento = new Date(evalItem.fecha_vencimiento);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            const esUrgente = diasRestantes <= 7 && diasRestantes >= 0;
            const tipoLower = (evalItem.tipo || '').toLowerCase();

            const enlace = evalItem.enlace || '';
            const enlaceDisponible = enlace.trim().length > 0;

            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4';

            card.innerHTML = `
                <div class="card evaluation-card ${tipoLower} h-100 ${esUrgente ? 'border-warning' : ''}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">${evalItem.nombre || ''}</h6>
                            <span class="status-badge status-${evalItem.estado || ''}">${evalItem.estado || ''}</span>
                        </div>
                        <p class="card-text text-muted small mb-3 flex-grow-1">${evalItem.descripcion || ''}</p>

                        <div class="mb-3">
                            <small class="text-muted d-block">Curso:</small>
                            <strong>${evalItem.curso || 'Sin curso asignado'}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Vence:</small>
                            <strong class="${esUrgente ? 'text-warning' : ''}">
                                ${formatearFechaEvaluacionesEducacionDocente(evalItem.fecha_vencimiento)}
                            </strong>
                            ${esUrgente ? `<small class="text-warning d-block">(${diasRestantes} días restantes)</small>` : ''}
                        </div>

                        <div class="mt-auto">
                            ${
                                enlaceDisponible
                                    ? `<a href="${enlace}" target="_blank" rel="noopener" class="btn-evaluacion w-100 text-center">
                                        <i class="fas fa-external-link-alt"></i>
                                        Completar Evaluación
                                    </a>`
                                    : `<span class="btn-evaluacion w-100 text-center" style="opacity:0.6; pointer-events:none;">
                                        <i class="fas fa-external-link-alt"></i>
                                        Enlace no disponible
                                    </span>`
                            }
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });
    }

    function generarVistaListaEvaluacionesEducacionDocente() {
        const tbody = document.getElementById('tablaEvaluacionesListaEduDocente');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (evaluacionesDocenteEdu.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay evaluaciones disponibles</p>
                    </td>
                </tr>
            `;
            return;
        }

        evaluacionesDocenteEdu.forEach(evalItem => {
            const fechaVencimiento = new Date(evalItem.fecha_vencimiento);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            const esUrgente = diasRestantes <= 7 && diasRestantes >= 0;

            const enlace = evalItem.enlace || '';
            const enlaceDisponible = enlace.trim().length > 0;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="fw-semibold">${evalItem.nombre || ''}</div>
                    <small class="text-muted">${evalItem.descripcion || ''}</small>
                    ${esUrgente ? '<small class="text-warning d-block"><i class="fas fa-clock"></i> Próxima a vencer</small>' : ''}
                </td>
                <td><span class="badge bg-secondary">${evalItem.tipo || ''}</span></td>
                <td>${evalItem.curso || 'Sin curso asignado'}</td>
                <td><span class="status-badge status-${evalItem.estado || ''}">${evalItem.estado || ''}</span></td>
                <td class="${esUrgente ? 'text-warning' : ''}">
                    ${formatearFechaEvaluacionesEducacionDocente(evalItem.fecha_vencimiento)}
                    ${esUrgente ? `<br><small>(${diasRestantes} días)</small>` : ''}
                </td>
                <td>
                    ${
                        enlaceDisponible
                            ? `<a href="${enlace}" target="_blank" rel="noopener" class="btn-evaluacion">
                                    <i class="fas fa-external-link-alt"></i>
                                    Completar
                               </a>`
                            : `<span class="btn-evaluacion" style="opacity:0.6; pointer-events:none;">
                                    <i class="fas fa-external-link-alt"></i>
                                    Completar
                               </span>`
                    }
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function formatearFechaEvaluacionesEducacionDocente(fecha) {
        try {
            return new Date(fecha).toLocaleDateString('es-ES');
        } catch (e) {
            return fecha;
        }
    }

    function verificarEvaluacionesUrgentesDocente() {
        const alerta = document.getElementById('alertaUrgentesEvaluacionesDocenteEdu');
        if (!alerta) return;

        const hoy = new Date();
        const urgentes = evaluacionesDocenteEdu.filter(evalItem => {
            const fechaVencimiento = new Date(evalItem.fecha_vencimiento);
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            return diasRestantes <= 7 && diasRestantes >= 0;
        });

        alerta.style.display = urgentes.length > 0 ? 'block' : 'none';
    }

    function cargarEvaluacionesEducacionDocente() {
        fetch('<?= base_url('docente/evaluaciones/obtener') ?>')
            .then(response => response.json())
            .then(payload => {
                if (!payload.success) {
                    console.error('Error cargando evaluaciones:', payload.message);
                    showNotification('Error al cargar evaluaciones: ' + payload.message, 'error');
                    return;
                }

                evaluacionesDocenteEdu = payload.data || [];
                verificarEvaluacionesUrgentesDocente();

                if (vistaEvaluacionesDocenteEdu === 'grid') {
                    generarVistaGridEvaluacionesEducacionDocente();
                } else {
                    generarVistaListaEvaluacionesEducacionDocente();
                }
            })
            .catch(error => {
                console.error('Error cargando evaluaciones:', error);
                showNotification('Error al cargar evaluaciones desde el servidor', 'error');
            });
    }

    function cargarEstadisticasEvaluacionesEducacionDocente() {
        fetch('<?= base_url('docente/evaluaciones/estadisticas') ?>')
            .then(response => response.json())
            .then(payload => {
                if (!payload.success) return;

                document.getElementById('totalEvaluacionesEduDocente').textContent = payload.data.total || 0;
                document.getElementById('evaluacionesActivasEduDocente').textContent = payload.data.activas || 0;
                document.getElementById('evaluacionesPendientesEduDocente').textContent = payload.data.pendientes || 0;
            })
            .catch(error => {
                console.error('Error cargando estadísticas evaluaciones (docente):', error);
            });
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        cargarEvaluacionesEducacionDocente();
        cargarEstadisticasEvaluacionesEducacionDocente();
    });
</script>

<!-- Incluir FullCalendar CSS y JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>
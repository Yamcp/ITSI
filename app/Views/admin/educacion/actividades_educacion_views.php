<?= $this->extend('admin/layouts/mainAdmin') ?>

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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Gestión de Actividades Educativas
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg,  #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="cursosActivos" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #ffe6e6;">Cursos Activos</p>
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
                        <a href="<?= base_url('admin/actividades-educacion/crear') ?>" style="text-decoration: none; color: inherit;">
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
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarEvaluaciones()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Datos</div>
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
                                    <i class="fas fa-book me-2"></i>Cursos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="talleres-tab" data-bs-toggle="tab" data-bs-target="#talleres" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-tools me-2"></i>Talleres
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="seminarios-tab" data-bs-toggle="tab" data-bs-target="#seminarios" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-users me-2"></i>Seminarios
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <!-- Cursos -->
                            <div class="tab-pane fade show active" id="cursos" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-book me-2"></i>Cursos</span>
                                        <button class="btn btn-light btn-sm" onclick="showModal('modalFiltros')">
                                            <i class="fas fa-filter me-1"></i>Filtros
                                        </button>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Instructor</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaCursos">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Curso'): ?>
                                                                <tr>
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
                                                                    <td>
                                                                        <div><?= $actividad['NOMBRE'] ?> <?= $actividad['APELLIDO'] ?></div>
                                                                        <small class="text-muted"><?= $actividad['ESPECIALIDAD'] ?></small>
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
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('admin/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('admin/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
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
                                                                <p>No hay cursos registrados</p>
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
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-tools me-2"></i>Talleres</span>
                                        <button class="btn btn-light btn-sm" onclick="showModal('modalFiltros')">
                                            <i class="fas fa-filter me-1"></i>Filtros
                                        </button>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Instructor</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaTalleres">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Taller'): ?>
                                                                <tr>
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
                                                                    <td>
                                                                        <div><?= $actividad['NOMBRE'] ?> <?= $actividad['APELLIDO'] ?></div>
                                                                        <small class="text-muted"><?= $actividad['ESPECIALIDAD'] ?></small>
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
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('admin/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('admin/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
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
                                                                <p>No hay talleres registrados</p>
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
                                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-users me-2"></i>Seminarios</span>
                                        <button class="btn btn-light btn-sm" onclick="showModal('modalFiltros')">
                                            <i class="fas fa-filter me-1"></i>Filtros
                                        </button>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Actividad</th>
                                                        <th>Instructor</th>
                                                        <th>Modalidad</th>
                                                        <th>Período</th>
                                                        <th>Duración</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaSeminarios">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Seminario'): ?>
                                                                <tr>
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
                                                                    <td>
                                                                        <div><?= $actividad['NOMBRE'] ?> <?= $actividad['APELLIDO'] ?></div>
                                                                        <small class="text-muted"><?= $actividad['ESPECIALIDAD'] ?></small>
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
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('admin/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('admin/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
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
                                                                <p>No hay seminarios registrados</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
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

<!-- Modal Nueva Actividad -->
<div class="modal fade" id="modalNuevaActividad" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Actividad Educativa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                </div>
                <form id="formNuevaActividad" action="<?= base_url('admin/actividades-educacion/guardar') ?>" method="POST" onsubmit="return validarFormulario()">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Actividad<span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_actividad" required>
                                    <option value="">Seleccionar...</option>
                                    <?php if (!empty($tipos_actividades)): ?>
                                        <?php foreach ($tipos_actividades as $tipo): ?>
                                            <option value="<?= $tipo['ID_TIPO_ACTIVIDAD'] ?>"><?= $tipo['ACTIVIDAD'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Actividad<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre_actividad" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Instructor<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" name="instructor" id="selectInstructor" required>
                                        <option value="">Seleccionar instructor...</option>
                                        <?php if (!empty($instructores)): ?>
                                            <?php foreach ($instructores as $instructor): ?>
                                                <option value="<?= $instructor['ID_INSTRUCTOR'] ?>"><?= $instructor['NOMBRE'] ?> <?= $instructor['APELLIDO'] ?> - <?= $instructor['ESPECIALIDAD'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <option value="__agregar_instructor__">➕ No está en la lista — Ir a agregar instructor</option>
                                    </select>
                                    <a href="<?= base_url('admin/instructores') ?>?crear=1" class="btn btn-outline-primary" type="button" title="Ir a agregar nuevo instructor" target="_self">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                                <small class="text-muted">Selecciona un instructor existente o use el botón + para ir a agregar uno nuevo</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Modalidad<span class="text-danger">*</span></label>
                                <select class="form-select" name="modalidad" required>
                                    <option value="">Seleccionar modalidad...</option>
                                    <?php if (!empty($modalidades)): ?>
                                        <?php foreach ($modalidades as $modalidad): ?>
                                            <option value="<?= $modalidad['ID_TIPO_MODALIDAD'] ?>"><?= $modalidad['MODALIDAD'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Duración (horas)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duracion_horas" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lugar<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="lugar" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Horario<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="horario" placeholder="Ej: Lunes a Viernes 8:00-12:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Objetivos<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="objetivos" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Programa Detallado</label>
                        <textarea class="form-control" name="programa_detallado" rows="4"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="incluye_certificado" id="certificado">
                        <label class="form-check-label" for="certificado">
                            Incluye certificado de participación
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Guardar Actividad
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Actividad -->
<div class="modal fade" id="modalDetalleActividad" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalle de la Actividad
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
                                        <p><strong>Actividad:</strong> <span id="detalleNombre">-</span></p>
                                        <p><strong>Tipo:</strong> <span id="detalleTipoActividad">-</span></p>
                                        <p><strong>Instructor:</strong> <span id="detalleInstructor">-</span></p>
                                        <p><strong>Modalidad:</strong> <span id="detalleModalidad">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                        <p><strong>Duración:</strong> <span id="detalleDuracion">-</span></p>
                                        <p><strong>Lugar:</strong> <span id="detalleLugar">-</span></p>
                                        <p><strong>Horario:</strong> <span id="detalleHorario">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Descripción:</strong></p>
                                        <p class="text-muted" id="detalleDescripcion">-</p>
                                        <p><strong>Objetivos:</strong></p>
                                        <p class="text-muted" id="detalleObjetivos">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Estado</h6>
                            </div>
                            <div class="card-body text-center">
                                <h4 id="estadoActividad">Activo</h4>
                                <p class="text-muted" id="certificadoInfo">Con certificado</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Acciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-users me-1"></i>Gestionar Participantes
                                    </button>
                                    <button class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-file-alt me-1"></i>Reporte de Asistencia
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
                    <i class="fas fa-edit me-1"></i>Editar Actividad
                </button>
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
                    <i class="fas fa-calendar-alt me-2"></i>Calendario de Actividades Educativas
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

<!-- Modal Detalle de Evento del Calendario -->
<div class="modal fade" id="modalDetalleEvento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalle de la Actividad
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h6 id="eventoNombre">-</h6>
                        <p class="text-muted mb-2">
                            <i class="fas fa-user me-1"></i>
                            <span id="eventoInstructor">-</span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <span id="eventoLugar">-</span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-clock me-1"></i>
                            <span id="eventoHorario">-</span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-calendar me-1"></i>
                            <span id="eventoFecha">-</span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-hourglass-half me-1"></i>
                            <span id="eventoDuracion">-</span>
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-1"></i>
                            <span id="eventoDescripcion">-</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarActividadDesdeCalendario()">
                    <i class="fas fa-edit me-1"></i>Editar
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
            const response = await fetch('<?= base_url('admin/actividades-educacion/calendario') ?>');
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

    function cambiarVistaCalendario(vista) {
        if (window.calendario) {
            try {
                window.calendario.changeView(vista);
            } catch (error) {
                console.error('Error al cambiar vista:', error);
            }
        }
    }

    function irHoy() {
        if (window.calendario) {
            try {
                window.calendario.today();
            } catch (error) {
                console.error('Error al ir a hoy:', error);
            }
        }
    }

    function anterior() {
        if (window.calendario) {
            try {
                window.calendario.prev();
            } catch (error) {
                console.error('Error al ir anterior:', error);
            }
        }
    }

    function siguiente() {
        if (window.calendario) {
            try {
                window.calendario.next();
            } catch (error) {
                console.error('Error al ir siguiente:', error);
            }
        }
    }

    function mostrarDetalleEvento(evento) {
        document.getElementById('eventoNombre').textContent = evento.title;
        document.getElementById('eventoInstructor').textContent = evento.extendedProps.instructor;
        document.getElementById('eventoLugar').textContent = evento.extendedProps.lugar;
        document.getElementById('eventoHorario').textContent = evento.extendedProps.horario;
        document.getElementById('eventoFecha').textContent = `${evento.startStr} - ${evento.endStr}`;
        document.getElementById('eventoDuracion').textContent = `${evento.extendedProps.duracion} horas`;
        document.getElementById('eventoDescripcion').textContent = evento.extendedProps.descripcion;
        
        showModal('modalDetalleEvento');
    }

    function editarActividadDesdeCalendario() {
        // Cerrar modal de detalle
        bootstrap.Modal.getInstance(document.getElementById('modalDetalleEvento')).hide();
        
        // Aquí podrías implementar la lógica para editar la actividad
        showNotification('Función de edición desde calendario en desarrollo', 'info');
    }

    function exportarCalendario() {
        if (window.calendario) {
            showNotification('Exportando calendario...', 'info');
            
            // Obtener el elemento del calendario
            const calendarElement = document.getElementById('calendario');
            
            // Usar html2canvas para capturar el calendario como imagen
            if (typeof html2canvas !== 'undefined') {
                html2canvas(calendarElement, {
                    backgroundColor: '#ffffff',
                    scale: 2, // Mayor resolución
                    useCORS: true,
                    allowTaint: true
                }).then(function(canvas) {
                    // Crear enlace de descarga
                    const link = document.createElement('a');
                    link.download = 'calendario-actividades-' + new Date().toISOString().split('T')[0] + '.png';
                    link.href = canvas.toDataURL('image/png');
                    
                    // Simular clic para descargar
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    showNotification('Calendario exportado exitosamente', 'success');
                }).catch(function(error) {
                    console.error('Error al exportar calendario:', error);
                    showNotification('Error al exportar el calendario', 'error');
                });
            } else {
                // Fallback: exportar como HTML
                exportarComoHTML();
            }
        }
    }
    
    function exportarComoHTML() {
        const calendarElement = document.getElementById('calendario');
        const htmlContent = calendarElement.outerHTML;
        
        // Crear contenido HTML completo
        const fullHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Calendario de Actividades Educativas</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .fc-toolbar-title { font-size: 1.5rem; font-weight: 600; color: #2c3e50; }
                .fc-button { background-color: #007bff; border-color: #007bff; color: white; }
                .fc-daygrid-day-number { color: #2c3e50; font-weight: 500; }
                .fc-day-today { background-color: #e3f2fd; }
                .fc-event { border-radius: 4px; font-size: 0.85rem; font-weight: 500; }
            </style>
        </head>
        <body>
            <h1>Calendario de Actividades Educativas</h1>
            <p>Exportado el: ${new Date().toLocaleDateString('es-ES')}</p>
            ${htmlContent}
        </body>
        </html>`;
        
        // Crear y descargar archivo HTML
        const blob = new Blob([fullHTML], { type: 'text/html' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'calendario-actividades-' + new Date().toISOString().split('T')[0] + '.html';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
        
        showNotification('Calendario exportado como HTML', 'success');
    }

    // Aplicar filtros del calendario
    document.addEventListener('DOMContentLoaded', function() {
        // Filtros de tipo de actividad
        const filtros = ['filtroCursos', 'filtroTalleres', 'filtroSeminarios'];
        
        filtros.forEach(filtro => {
            const elemento = document.getElementById(filtro);
            if (elemento) {
                elemento.addEventListener('change', function() {
                    aplicarFiltrosCalendario();
                });
            }
        });
    });

    function aplicarFiltrosCalendario() {
        if (!window.calendario) return;
        
        const mostrarCursos = document.getElementById('filtroCursos').checked;
        const mostrarTalleres = document.getElementById('filtroTalleres').checked;
        const mostrarSeminarios = document.getElementById('filtroSeminarios').checked;
        
        // Obtener todos los eventos
        const eventos = window.calendario.getEvents();
        
        eventos.forEach(evento => {
            const tipo = evento.extendedProps.tipo;
            let visible = false;
            
            if (tipo === 'Curso' && mostrarCursos) visible = true;
            if (tipo === 'Taller' && mostrarTalleres) visible = true;
            if (tipo === 'Seminario' && mostrarSeminarios) visible = true;
            
            evento.setProp('display', visible ? 'auto' : 'none');
        });
    }

    function generarCertificados() {
        showNotification('Generando certificados masivos...', 'info');
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
            const response = await fetch('<?= base_url('admin/actividades-educacion/api/estadisticas') ?>');
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

    // Función para gestionar participantes
    function gestionarParticipantes(id) {
        showNotification('Función de gestión de participantes en desarrollo', 'info');
    }

    // Función para abrir inscripciones
    function abrirInscripciones(id) {
        showNotification('Función de inscripciones en desarrollo', 'info');
    }

    // Función para generar certificado
    function generarCertificado(id) {
        showNotification('Generando certificado...', 'info');
        // Aquí podrías implementar la generación real de certificados
        setTimeout(() => {
            showNotification('Certificado generado exitosamente', 'success');
        }, 2000);
    }

    // Funciones para reportes y exportación
    function generarReporteEvaluaciones() {
        // Redirigir a la página de reportes
        window.location.href = '<?= base_url('admin/actividades-educacion/reportes') ?>';
    }

    function exportarEvaluaciones() {
        // Mostrar modal de opciones de exportación
        showModalOpcionesExportacion();
    }

    function showModalOpcionesExportacion() {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.id = 'modalOpcionesExportacion';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-download me-2"></i>Opciones de Exportación
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Selecciona el formato de exportación:</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-danger" onclick="exportarFormato('pdf')">
                                <i class="fas fa-file-pdf me-2"></i>Exportar como PDF
                            </button>
                            <button class="btn btn-outline-success" onclick="exportarFormato('excel')">
                                <i class="fas fa-file-excel me-2"></i>Exportar como Excel
                            </button>                            
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Los archivos se descargarán automáticamente en tu navegador
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();
        
        // Limpiar modal cuando se cierre
        modal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modal);
        });
    }

    function exportarFormato(formato) {
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalOpcionesExportacion'));
        modal.hide();
        
        // Mostrar notificación
        showNotification(`Exportando actividades en formato ${formato.toUpperCase()}...`, 'info');
        
        // Construir URL con filtros actuales
        const url = `<?= base_url('admin/actividades-educacion/exportar') ?>/${formato}`;
        
        // Crear formulario temporal para enviar filtros
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = url;
        form.target = '_blank';
        
        // Agregar filtros si existen
        const filtros = obtenerFiltrosActuales();
        Object.keys(filtros).forEach(key => {
            if (filtros[key]) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = filtros[key];
                form.appendChild(input);
            }
        });
        
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
        
        // Notificación de éxito
        setTimeout(() => {
            showNotification(`Archivo ${formato.toUpperCase()} generado exitosamente`, 'success');
        }, 1000);
    }

    function obtenerFiltrosActuales() {
        // Obtener filtros del modal de filtros si está abierto
        const filtros = {};
        
        // Aquí podrías obtener los filtros del modal de filtros
        // Por ahora retornamos un objeto vacío
        return filtros;
    }

    // Función de validación del formulario
    function validarFormulario() {
        const camposObligatorios = [
            { name: 'tipo_actividad', label: 'Tipo de Actividad' },
            { name: 'nombre_actividad', label: 'Nombre de la Actividad' },
            { name: 'instructor', label: 'Instructor' },
            { name: 'modalidad', label: 'Modalidad' },
            { name: 'descripcion', label: 'Descripción' },
            { name: 'objetivos', label: 'Objetivos' },
            { name: 'duracion_horas', label: 'Duración (horas)' },
            { name: 'fecha_inicio', label: 'Fecha de Inicio' },
            { name: 'fecha_fin', label: 'Fecha de Fin' },
            { name: 'lugar', label: 'Lugar' },
            { name: 'horario', label: 'Horario' }
        ];

        let errores = [];
        let camposVacios = [];

        // Validar campos obligatorios
        camposObligatorios.forEach(campo => {
            const elemento = document.querySelector(`[name="${campo.name}"]`);
            if (elemento) {
                const valor = elemento.value.trim();
                
                if (!valor) {
                    camposVacios.push(campo.label);
                    elemento.classList.add('is-invalid');
                } else {
                    elemento.classList.remove('is-invalid');
                    
                    // Validaciones específicas
                    if (campo.name === 'descripcion' && valor.length < 10) {
                        errores.push(`${campo.label} debe tener al menos 10 caracteres`);
                        elemento.classList.add('is-invalid');
                    }
                    
                    if (campo.name === 'objetivos' && valor.length < 10) {
                        errores.push(`${campo.label} deben tener al menos 10 caracteres`);
                        elemento.classList.add('is-invalid');
                    }
                    
                    if (campo.name === 'duracion_horas') {
                        const duracion = parseInt(valor);
                        if (isNaN(duracion) || duracion <= 0) {
                            errores.push(`${campo.label} debe ser un número mayor a 0`);
                            elemento.classList.add('is-invalid');
                        }
                    }
                    
                    if (campo.name === 'fecha_inicio' || campo.name === 'fecha_fin') {
                        const fecha = new Date(valor);
                        if (isNaN(fecha.getTime())) {
                            errores.push(`${campo.label} debe ser una fecha válida`);
                            elemento.classList.add('is-invalid');
                        }
                    }
                }
            }
        });

        // Validar que fecha fin sea posterior a fecha inicio
        const fechaInicio = document.querySelector('[name="fecha_inicio"]').value;
        const fechaFin = document.querySelector('[name="fecha_fin"]').value;
        
        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);
            
            if (fin <= inicio) {
                errores.push('La fecha de fin debe ser posterior a la fecha de inicio');
                document.querySelector('[name="fecha_fin"]').classList.add('is-invalid');
            }
        }

        // Mostrar errores
        if (camposVacios.length > 0 || errores.length > 0) {
            let mensaje = '';
            
            if (camposVacios.length > 0) {
                mensaje += 'Los siguientes campos son obligatorios:\n• ' + camposVacios.join('\n• ') + '\n\n';
            }
            
            if (errores.length > 0) {
                mensaje += 'Errores de validación:\n• ' + errores.join('\n• ');
            }
            
            showNotification(mensaje, 'error');
            return false;
        }

        return true;
    }

    // Función para limpiar validaciones al cambiar campos
    function limpiarValidacion(campo) {
        const elemento = document.querySelector(`[name="${campo}"]`);
        if (elemento) {
            elemento.classList.remove('is-invalid');
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
        if (fechaInicioInput) {
            fechaInicioInput.value = today;
        }

        // Redirigir a instructores cuando el usuario elige "agregar instructor" en el select
        const selectInstructor = document.getElementById('selectInstructor');
        if (selectInstructor) {
            selectInstructor.addEventListener('change', function() {
                if (this.value === '__agregar_instructor__') {
                    window.location.href = '<?= base_url('admin/instructores') ?>?crear=1';
                }
            });
        }
        
        // Cargar estadísticas al cargar la página
        cargarEstadisticas();
        
        // Agregar eventos para limpiar validaciones
        const camposFormulario = document.querySelectorAll('#formNuevaActividad input, #formNuevaActividad select, #formNuevaActividad textarea');
        camposFormulario.forEach(campo => {
            campo.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
    });
</script>

<!-- Incluir FullCalendar CSS y JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Incluir html2canvas para exportación de imágenes -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<?= $this->endSection() ?>
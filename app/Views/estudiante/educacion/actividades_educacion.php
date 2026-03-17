<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

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
                    Actividades Educativas Disponibles
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Actividades Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="misInscripciones" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Mis Inscripciones</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #0f6674 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="certificadosObtenidos" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Certificados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #6f42c1 80%, #4a2c7a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="horasCompletadas" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Horas Completadas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
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
                        <a href="#" onclick="verMisInscripciones()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-list-alt fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Mis Inscripciones</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCertificados()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-certificate fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Mis Certificados</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarMiProgreso()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Progreso</div>
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
                                    <i class="fas fa-book me-2"></i>Cursos Disponibles
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="talleres-tab" data-bs-toggle="tab" data-bs-target="#talleres" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-tools me-2"></i>Talleres Disponibles
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="seminarios-tab" data-bs-toggle="tab" data-bs-target="#seminarios" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-users me-2"></i>Seminarios Disponibles
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
                                        <span><i class="fas fa-book me-2"></i>Cursos Disponibles</span>
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
                                                                            echo '<span class="badge bg-success">Disponible</span>';
                                                                        } else {
                                                                            echo '<span class="badge bg-secondary">Finalizado</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </button>
                                                                            <button class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
                                                                                <i class="fas fa-user-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay cursos disponibles</p>
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
                                        <span><i class="fas fa-tools me-2"></i>Talleres Disponibles</span>
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
                                                                            echo '<span class="badge bg-success">Disponible</span>';
                                                                        } else {
                                                                            echo '<span class="badge bg-secondary">Finalizado</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </button>
                                                                            <button class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
                                                                                <i class="fas fa-user-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay talleres disponibles</p>
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
                                        <span><i class="fas fa-users me-2"></i>Seminarios Disponibles</span>
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
                                                                            echo '<span class="badge bg-success">Disponible</span>';
                                                                        } else {
                                                                            echo '<span class="badge bg-secondary">Finalizado</span>';
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </button>
                                                                            <button class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
                                                                                <i class="fas fa-user-plus"></i>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay seminarios disponibles</p>
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

<!-- Modal Detalle de Actividad -->
<div class="modal fade" id="modalDetalleActividad" tabindex="-1">
    <div class="modal-dialog modal-lg">
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
                                <h4 id="estadoActividad">Disponible</h4>
                                <p class="text-muted" id="certificadoInfo">Con certificado</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Acciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-success btn-sm" onclick="inscribirseActividad()">
                                        <i class="fas fa-user-plus me-1"></i>Inscribirse
                                    </button>
                                    <button class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-share me-1"></i>Compartir
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

<!-- Modal Calendario de Actividades -->
<div class="modal fade" id="modalCalendario" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Calendario de Actividades
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

    function verMisInscripciones() {
        showNotification('Redirigiendo a mis inscripciones...', 'info');
        // Implementar redirección a página de inscripciones
    }

    function verCertificados() {
        showNotification('Redirigiendo a mis certificados...', 'info');
        // Implementar redirección a página de certificados
    }

    function exportarMiProgreso() {
        showNotification('Exportando mi progreso...', 'info');
        // Implementar exportación del progreso del estudiante
    }

    function verDetalleActividad(id) {
        // Cargar datos de la actividad
        fetch(`<?= base_url('estudiante/actividades-educacion/detalle/') ?>${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const actividad = data.data;
                    document.getElementById('detalleNombre').textContent = actividad.NOMBRE_ACTIVIDAD;
                    document.getElementById('detalleTipoActividad').textContent = actividad.ACTIVIDAD;
                    document.getElementById('detalleInstructor').textContent = `${actividad.NOMBRE} ${actividad.APELLIDO}`;
                    document.getElementById('detalleModalidad').textContent = actividad.MODALIDAD;
                    document.getElementById('detallePeriodo').textContent = `${actividad.FECHA_INICIO} - ${actividad.FECHA_FIN}`;
                    document.getElementById('detalleDuracion').textContent = `${actividad.DURACION_HORAS} horas`;
                    document.getElementById('detalleLugar').textContent = actividad.LUGAR;
                    document.getElementById('detalleHorario').textContent = actividad.HORARIO;
                    document.getElementById('detalleDescripcion').textContent = actividad.DESCRIPCION;
                    document.getElementById('detalleObjetivos').textContent = actividad.OBJETIVOS;

                    showModal('modalDetalleActividad');
                } else {
                    showNotification('Error al cargar detalles de la actividad', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
            });
    }

    function inscribirseActividad(id) {
        if (confirm('¿Estás seguro de que quieres inscribirte en esta actividad?')) {
            showNotification('Inscribiéndote en la actividad...', 'info');
            // Implementar lógica de inscripción
        }
    }

    // Cargar datos del calendario desde la API
    async function cargarDatosCalendario() {
        try {
            const response = await fetch('<?= base_url('estudiante/actividades-educacion/calendario') ?>');
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
                    verDetalleActividad(info.event.id);
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
            const response = await fetch('<?= base_url('estudiante/actividades-educacion/api/estadisticas') ?>');
            const stats = await response.json();

            // Actualizar las estadísticas en la interfaz
            document.getElementById('totalActividades').textContent = stats.totalActividades || 0;
            document.getElementById('misInscripciones').textContent = stats.misInscripciones || 0;
            document.getElementById('certificadosObtenidos').textContent = stats.certificadosObtenidos || 0;
            document.getElementById('horasCompletadas').textContent = stats.horasCompletadas || 0;

            estadisticas = stats;
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar estadísticas al cargar la página
        cargarEstadisticas();
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
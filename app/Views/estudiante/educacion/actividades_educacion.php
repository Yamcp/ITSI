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

    /* Vista mes: que el nombre se vea también en continuationes (varias filas/semanas) */
    .fc .fc-daygrid-event .fc-event-title {
        display: block !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.2;
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
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
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
        background: linear-gradient(135deg, #1e7e34 0%, #155724 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .urgent-card {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffc107;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    /* Asegura que la pestaña "Evaluaciones" no quede fuera de la vista */
    #actividadesTabs {
        /* Centra y evita que los tabs se estiren/hagan huecos */
        justify-content: center;
        flex-wrap: nowrap;
        gap: 0.5rem;
    }

    /* Anula el comportamiento de `nav-justified` para que cada tab tenga su tamaño */
    #actividadesTabs .nav-item {
        flex: 0 0 auto;
    }

    /* En pantallas pequeñas sí permitimos salto de línea */
    @media (max-width: 768px) {
        #actividadesTabs {
            flex-wrap: wrap;
        }
    }

    /* El estudiante no debe ver una sección/listado separado de "Evaluaciones"
       dentro de Educación Continua; los enlaces se integran en las filas de cursos */
    #evaluaciones-tab,
    #evaluaciones {
        display: none !important;
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
                    Educación Continua: Cursos y Evaluaciones
                </h3>
            </div>
        </div>

        <!-- Estadísticas y acceso al calendario (misma fila) -->
        <div class="row mb-4 align-items-stretch g-3">
            <div class="col-md-6 col-sm-6">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column justify-content-center py-4">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Actividades Disponibles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-4">
                        <a href="#" onclick="verCalendario(); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Ver Calendario</div>
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
                        <?php $insc = $actividadesInscritas ?? []; ?>
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="actividadesTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="disponibles-tab" data-bs-toggle="tab" data-bs-target="#disponibles" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-book me-2"></i>Actividades Disponibles
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="tomadas-tab" data-bs-toggle="tab" data-bs-target="#tomadas" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-user-check me-2"></i>Actividades Tomadas
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="culminadas-tab" data-bs-toggle="tab" data-bs-target="#culminadas" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-clipboard-check me-2"></i>Actividades Culminadas
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <!-- Actividades Disponibles (Cursos, Talleres y Seminarios) -->
                            <div class="tab-pane fade show active" id="disponibles" role="tabpanel">
                                <div class="card shadow-sm border-0">
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
                                                <tbody id="tablaDisponibles">
                                                    <?php
                                                    $hoy = new DateTime();
                                                    $hayDisponibles = false;
                                                    if (!empty($actividades)) :
                                                        foreach ($actividades as $actividad) :
                                                            $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                            $finalizado = $fechaFin < $hoy;
                                                            if ($finalizado) continue;

                                                            $idAct = (int) $actividad['ID_ACTIVIDAD_EDUCACION'];
                                                            if (!empty($insc[$idAct])) {
                                                                continue;
                                                            }

                                                            $hayDisponibles = true;
                                                            $tipo = $actividad['ACTIVIDAD'];
                                                    ?>
                                                        <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                            <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <?php if ($tipo === 'Curso') : ?>
                                                                        <i class="fas fa-laptop-code fa-2x me-2 text-primary"></i>
                                                                    <?php elseif ($tipo === 'Taller') : ?>
                                                                        <i class="fas fa-wrench fa-2x me-2 text-success"></i>
                                                                    <?php else : ?>
                                                                        <i class="fas fa-comments fa-2x me-2 text-info"></i>
                                                                    <?php endif; ?>
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
                                                            <td>
                                                                <?php if ($tipo === 'Curso') : ?>
                                                                    <span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php elseif ($tipo === 'Taller') : ?>
                                                                    <span class="badge bg-warning text-dark"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php else : ?>
                                                                    <span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div><?= date('M Y', strtotime($actividad['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($actividad['FECHA_FIN'])) ?></div>
                                                                <small class="text-muted"><?= $actividad['DURACION_HORAS'] ?> horas</small>
                                                            </td>
                                                            <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                            <td><span class="badge bg-success">Disponible</span></td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Ver Detalle">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button data-accion-inscribir="true" class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
                                                                        <i class="fas fa-user-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;

                                                    if (!$hayDisponibles) :
                                                    ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay actividades disponibles en este momento</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actividades Tomadas (inscrito y aún en curso por calendario) -->
                            <div class="tab-pane fade" id="tomadas" role="tabpanel">
                                <div class="card shadow-sm border-0">
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
                                                <tbody id="tablaTomadas">
                                                    <?php
                                                    $hoy = new DateTime();
                                                    $hayTomadas = false;
                                                    if (!empty($actividades)) :
                                                        foreach ($actividades as $actividad) :
                                                            $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                            $finalizado = $fechaFin < $hoy;
                                                            if ($finalizado) {
                                                                continue;
                                                            }

                                                            $idAct = (int) $actividad['ID_ACTIVIDAD_EDUCACION'];
                                                            if (empty($insc[$idAct])) {
                                                                continue;
                                                            }

                                                            $hayTomadas = true;
                                                            $tipo = $actividad['ACTIVIDAD'];
                                                    ?>
                                                        <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                            <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <?php if ($tipo === 'Curso') : ?>
                                                                        <i class="fas fa-laptop-code fa-2x me-2 text-primary"></i>
                                                                    <?php elseif ($tipo === 'Taller') : ?>
                                                                        <i class="fas fa-wrench fa-2x me-2 text-success"></i>
                                                                    <?php else : ?>
                                                                        <i class="fas fa-comments fa-2x me-2 text-info"></i>
                                                                    <?php endif; ?>
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
                                                            <td>
                                                                <?php if ($tipo === 'Curso') : ?>
                                                                    <span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php elseif ($tipo === 'Taller') : ?>
                                                                    <span class="badge bg-warning text-dark"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php else : ?>
                                                                    <span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div><?= date('M Y', strtotime($actividad['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($actividad['FECHA_FIN'])) ?></div>
                                                                <small class="text-muted"><?= $actividad['DURACION_HORAS'] ?> horas</small>
                                                            </td>
                                                            <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                            <td><span class="badge bg-success">En curso</span></td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Ver Detalle">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;

                                                    if (!$hayTomadas) :
                                                    ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No tienes actividades tomadas en curso. Inscríbete desde «Actividades disponibles».</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actividades Culminadas (con enlace de encuesta/evaluación cuando exista) -->
                            <div class="tab-pane fade" id="culminadas" role="tabpanel">
                                <div class="card shadow-sm border-0">
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
                                                <tbody id="tablaCulminadas">
                                                    <?php
                                                    $hoy = new DateTime();
                                                    $hayCulminadas = false;
                                                    if (!empty($actividades)) :
                                                        foreach ($actividades as $actividad) :
                                                            $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                            $finalizado = $fechaFin < $hoy;
                                                            if (!$finalizado) continue;

                                                            $idAct = (int) $actividad['ID_ACTIVIDAD_EDUCACION'];
                                                            if (empty($insc[$idAct])) {
                                                                continue;
                                                            }

                                                            $hayCulminadas = true;
                                                            $tipo = $actividad['ACTIVIDAD'];
                                                    ?>
                                                        <tr data-actividad-id="<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>" data-fecha-fin="<?= $actividad['FECHA_FIN'] ?>">
                                                            <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <?php if ($tipo === 'Curso') : ?>
                                                                        <i class="fas fa-laptop-code fa-2x me-2 text-primary"></i>
                                                                    <?php elseif ($tipo === 'Taller') : ?>
                                                                        <i class="fas fa-wrench fa-2x me-2 text-success"></i>
                                                                    <?php else : ?>
                                                                        <i class="fas fa-comments fa-2x me-2 text-info"></i>
                                                                    <?php endif; ?>
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
                                                            <td>
                                                                <?php if ($tipo === 'Curso') : ?>
                                                                    <span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php elseif ($tipo === 'Taller') : ?>
                                                                    <span class="badge bg-warning text-dark"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php else : ?>
                                                                    <span class="badge bg-info"><?= $actividad['MODALIDAD'] ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <div><?= date('M Y', strtotime($actividad['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($actividad['FECHA_FIN'])) ?></div>
                                                                <small class="text-muted"><?= $actividad['DURACION_HORAS'] ?> horas</small>
                                                            </td>
                                                            <td><span class="badge bg-secondary"><?= $actividad['DURACION_HORAS'] ?>h</span></td>
                                                            <td><span class="badge bg-secondary">Finalizado</span></td>
                                                            <td>
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary" onclick="verDetalleActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Ver Detalle">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;

                                                    if (!$hayCulminadas) :
                                                    ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay actividades culminadas en este momento</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cursos -->
                            <div class="tab-pane fade d-none" id="cursos" role="tabpanel">
                                <div class="card shadow-sm border-0">
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
                                                                            <button data-accion-inscribir="true" class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
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
                            <div class="tab-pane fade d-none" id="talleres" role="tabpanel">
                                <div class="card shadow-sm border-0">
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
                                                                            <button data-accion-inscribir="true" class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
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
                            <div class="tab-pane fade d-none" id="seminarios" role="tabpanel">
                                <div class="card shadow-sm border-0">
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
                                                                            <button data-accion-inscribir="true" class="btn btn-outline-success" onclick="inscribirseActividad(<?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?>)" title="Inscribirse">
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
                                                    Al finalizar cada curso/taller/seminario, aquí se habilitan los enlaces de
                                                    evaluación y/o encuesta para que completes lo requerido.
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Alerta de Evaluaciones Urgentes -->
                                        <div class="row mb-4" id="alertaUrgentesEvaluacionesEdu" style="display: none;">
                                            <div class="col-12">
                                                <div class="urgent-card">
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-exclamation-triangle text-warning me-3" style="font-size: 1.5rem;"></i>
                                                        <div>
                                                            <h6 class="mb-1">Evaluaciones Próximas a Vencer</h6>
                                                            <p class="mb-0 text-muted">
                                                                Tienes evaluaciones que vencen pronto. Te recomendamos completarlas lo antes posible.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Estadísticas Básicas -->
                                        <div class="row mb-4">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                                                    <div class="card-body">
                                                        <h2 class="card-title mb-2" id="totalEvaluacionesEdu" style="font-size:2.5rem;">0</h2>
                                                        <p class="card-text fw-bold" style="color: #e0e0e0;">Evaluaciones Disponibles</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                                                    <div class="card-body">
                                                        <h2 class="card-title mb-2" id="evaluacionesActivasEdu" style="font-size:2.5rem;">0</h2>
                                                        <p class="card-text fw-bold" style="color: #e0e0e0;">Activas</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                                                    <div class="card-body">
                                                        <h2 class="card-title mb-2" id="evaluacionesPendientesEdu" style="font-size:2.5rem;">0</h2>
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
                                                            <button class="btn btn-light btn-sm" onclick="cambiarVistaEvaluacionesEducacion('grid')">
                                                                <i class="fas fa-th-large me-1"></i>Grid
                                                            </button>
                                                            <button class="btn btn-light btn-sm" onclick="cambiarVistaEvaluacionesEducacion('list')">
                                                                <i class="fas fa-list me-1"></i>Lista
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <!-- Vista Grid -->
                                                        <div id="vistaGridEvaluacionesEdu" class="row g-3"></div>

                                                        <!-- Vista Lista -->
                                                        <div id="vistaListaEvaluacionesEdu" class="d-none">
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
                                                                    <tbody id="tablaEvaluacionesListaEdu"></tbody>
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

<?= $this->endSection() ?>

<?= $this->section('modal') ?>
<!-- Modal Detalle de Actividad -->
<div class="modal fade" id="modalDetalleActividad" tabindex="-1" aria-hidden="true">
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
                                        <p id="wrapDetalleLugar"><strong>Lugar:</strong> <span id="detalleLugar">-</span></p>
                                        <p id="wrapDetalleEnlace" class="d-none"><strong>Enlace:</strong> <a id="detalleEnlace" href="#" target="_blank" rel="noopener"></a></p>
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
<div class="modal fade" id="modalCalendario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-alt me-2"></i>Calendario de Actividades
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Calendario -->
                <div id="calendario" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmación de inscripción (sustituye al diálogo nativo del navegador) -->
<div class="modal fade" id="modalConfirmarInscripcion" tabindex="-1" aria-labelledby="modalConfirmarInscripcionTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="modalConfirmarInscripcionTitulo">
                    <i class="fas fa-user-plus me-2 text-success"></i>Confirmar inscripción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="mb-0 text-body" id="textoConfirmarInscripcion">¿Confirmas que deseas inscribirte en esta actividad?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnConfirmarInscripcion" onclick="ejecutarInscripcionConfirmada()">
                    <i class="fas fa-check me-1"></i>Sí, inscribirme
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Variables globales
    let actividadesData = [];
    let estadisticas = {};
    let actividadIdInscripcionPendiente = null;
    /** Reservado por si se usa inscripción sin id explícito */
    window._idActividadParaInscripcion = null;

    function showModal(modalId) {
        const el = document.getElementById(modalId);
        if (!el || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(el).show();
    }

    function verCalendario() {
        showModal('modalCalendario');
        // Inicializar calendario después de que se abra el modal
        setTimeout(() => {
            cargarDatosCalendario();
        }, 300);
    }

    function exportarMiProgreso() {
        showNotification('Exportando mi progreso...', 'info');
        // Implementar exportación del progreso del estudiante
    }

    function verDetalleActividad(id) {
        window._idActividadParaInscripcion = parseInt(id, 10) || null;
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
                    const lugarD = (actividad.LUGAR || '').trim();
                    const enlaceD = (actividad.ENLACE || '').trim();
                    document.getElementById('detalleLugar').textContent = lugarD || '—';
                    document.getElementById('wrapDetalleLugar').classList.toggle('d-none', !lugarD);
                    const wEn = document.getElementById('wrapDetalleEnlace');
                    const aEn = document.getElementById('detalleEnlace');
                    if (enlaceD) {
                        aEn.href = /^https?:\/\//i.test(enlaceD) ? enlaceD : 'https://' + enlaceD;
                        aEn.textContent = enlaceD;
                        wEn.classList.remove('d-none');
                    } else {
                        wEn.classList.add('d-none');
                    }
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
        let actId = id;
        if (actId === undefined || actId === null || actId === '') {
            actId = window._idActividadParaInscripcion;
        }
        actId = parseInt(actId, 10);
        if (!actId || Number.isNaN(actId)) {
            showNotification('No se pudo identificar la actividad.', 'warning');
            return;
        }
        actividadIdInscripcionPendiente = actId;

        const modalEl = document.getElementById('modalConfirmarInscripcion');
        if (!modalEl || typeof bootstrap === 'undefined') return;
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    async function ejecutarInscripcionConfirmada() {
        const id = actividadIdInscripcionPendiente;
        const modalEl = document.getElementById('modalConfirmarInscripcion');
        const inst = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
        if (inst) inst.hide();

        if (!id) {
            return;
        }

        const btn = document.getElementById('btnConfirmarInscripcion');
        if (btn) {
            btn.disabled = true;
        }

        try {
            const formData = new FormData();
            formData.append('id_actividad', id);

            const response = await fetch('<?= base_url('estudiante/actividades-educacion/inscribirse') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data = {};
            try {
                data = await response.json();
            } catch (parseErr) {
                showNotification('Respuesta inválida del servidor.', 'error');
                return;
            }

            if (data.success) {
                showNotification(data.message || 'Te has inscrito correctamente.', 'success');
                window.setTimeout(() => window.location.reload(), 900);
            } else {
                showNotification(data.message || 'No se pudo completar la inscripción.', 'error');
            }
        } catch (e) {
            console.error(e);
            showNotification('Error de conexión. Revisa tu red e intenta de nuevo.', 'error');
        } finally {
            if (btn) {
                btn.disabled = false;
            }
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

        if (window.calendario) {
            try {
                window.calendario.destroy();
            } catch (e) {
                /* instancia ya destruida o DOM reemplazado */
            }
            window.calendario = null;
        }

        // Una fila por id (por si la API devolviera duplicados)
        const vistos = new Set();
        const eventosUnicos = (eventos || []).filter(e => {
            const k = String(e.id);
            if (vistos.has(k)) return false;
            vistos.add(k);
            return true;
        });

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
                events: eventosUnicos,
                eventContent: function(arg) {
                    if (arg.view.type !== 'dayGridMonth') {
                        return;
                    }
                    const text = arg.event.title;
                    if (!text) {
                        return;
                    }
                    const main = document.createElement('div');
                    main.className = 'fc-event-main';
                    const tit = document.createElement('div');
                    tit.className = 'fc-event-title';
                    tit.appendChild(document.createTextNode(text));
                    main.appendChild(tit);
                    return { domNodes: [main] };
                },
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

            estadisticas = stats;
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    /**
     * Polling para que, cuando el coordinador agregue el enlace de satisfacción,
     * el estudiante lo vea automáticamente en la tabla (sin recargar la página).
     */
    async function actualizarEnlacesSatisfaccion() {
        try {
            const response = await fetch('<?= base_url('estudiante/actividades-educacion/api/encuestas-satisfaccion') ?>', { cache: 'no-store' });
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

                // Toggle del botón de inscripción (solo aplica a estudiantes)
                const botonInscribir = tr.querySelector('button[data-accion-inscribir="true"]');
                if (botonInscribir) {
                    botonInscribir.style.display = finalizado ? 'none' : '';
                }

                // Inject del botón "Abrir encuesta"
                const btnGroup = tr.querySelector('.btn-group');
                if (!btnGroup) return;

                const idLink = `encuesta-link-${idActividad}`;
                const enlaceExistente = document.getElementById(idLink);

                if (finalizado && enlace) {
                    if (!enlaceExistente) {
                        const a = document.createElement('a');
                        a.id = idLink;
                        a.target = '_blank';
                        a.rel = 'noopener';
                        a.className = 'btn btn-outline-warning btn-sm';
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
            // No romper la página si el endpoint falla
            console.error('Error al actualizar enlaces satisfacción:', e);
        }
    }

    function iniciarPollingEncuestasSatisfaccion() {
        actualizarEnlacesSatisfaccion();
        setInterval(actualizarEnlacesSatisfaccion, 15000); // 15s
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar estadísticas al cargar la página
        cargarEstadisticas();
        iniciarPollingEncuestasSatisfaccion();
    });
</script>

<script>
    // ===== Evaluaciones integradas en Educación Continua (Estudiante) =====
    let evaluacionesEdu = [];
    let vistaEvaluacionesEdu = 'grid';

    function cambiarVistaEvaluacionesEducacion(tipo) {
        vistaEvaluacionesEdu = tipo;
        if (tipo === 'grid') {
            document.getElementById('vistaGridEvaluacionesEdu').classList.remove('d-none');
            document.getElementById('vistaListaEvaluacionesEdu').classList.add('d-none');
            generarVistaGridEvaluacionesEducacion();
        } else {
            document.getElementById('vistaGridEvaluacionesEdu').classList.add('d-none');
            document.getElementById('vistaListaEvaluacionesEdu').classList.remove('d-none');
            generarVistaListaEvaluacionesEducacion();
        }
    }

    function generarVistaGridEvaluacionesEducacion() {
        const container = document.getElementById('vistaGridEvaluacionesEdu');
        if (!container) return;

        container.innerHTML = '';

        if (evaluacionesEdu.length === 0) {
            container.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay evaluaciones disponibles</h5>
                    <p class="text-muted">No tienes evaluaciones pendientes en este momento.</p>
                </div>
            `;
            return;
        }

        evaluacionesEdu.forEach(evalItem => {
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4';

            const fechaVencimiento = new Date(evalItem.fecha_vencimiento);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            const esUrgente = diasRestantes <= 7 && diasRestantes >= 0;
            const tipoLower = (evalItem.tipo || '').toLowerCase();
            const esEncuesta = tipoLower.includes('satisfaccion') || tipoLower.includes('encuesta');
            const etiquetaAccion = esEncuesta ? 'Abrir encuesta' : 'Completar evaluación';

            card.innerHTML = `
                <div class="card evaluation-card ${tipoLower} h-100 ${esUrgente ? 'border-warning' : ''}">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">${evalItem.nombre}</h6>
                            <div class="d-flex flex-column align-items-end">
                                <span class="status-badge status-${evalItem.estado}">${evalItem.estado}</span>
                                ${esUrgente ? '<small class="text-warning mt-1"><i class="fas fa-clock"></i> Próxima a vencer</small>' : ''}
                            </div>
                        </div>
                        <p class="card-text text-muted small mb-3 flex-grow-1">${evalItem.descripcion || ''}</p>
                        ${esEncuesta ? `<small class="text-muted d-block mb-3">Cuando termines tu curso, abre la encuesta de satisfacción en este enlace.</small>` : ''}
                        <div class="mb-3">
                            <small class="text-muted d-block">Curso:</small>
                            <strong>${evalItem.curso || 'Sin curso asignado'}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Vence:</small>
                            <strong class="${esUrgente ? 'text-warning' : ''}">
                                ${formatearFechaEvaluacionesEducacion(evalItem.fecha_vencimiento)}
                            </strong>
                            ${esUrgente ? `<small class="text-warning d-block">(${diasRestantes} días restantes)</small>` : ''}
                        </div>
                        <div class="mt-auto">
                            <a href="${evalItem.enlace}" target="_blank" rel="noopener" class="btn-evaluacion w-100 text-center">
                                <i class="fas fa-external-link-alt"></i>
                                ${etiquetaAccion}
                            </a>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function generarVistaListaEvaluacionesEducacion() {
        const tbody = document.getElementById('tablaEvaluacionesListaEdu');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (evaluacionesEdu.length === 0) {
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

        evaluacionesEdu.forEach(evalItem => {
            const fechaVencimiento = new Date(evalItem.fecha_vencimiento);
            const hoy = new Date();
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            const esUrgente = diasRestantes <= 7 && diasRestantes >= 0;
            const tipoLower = (evalItem.tipo || '').toLowerCase();
            const esEncuesta = tipoLower.includes('satisfaccion') || tipoLower.includes('encuesta');
            const etiquetaAccionLista = esEncuesta ? 'Abrir encuesta' : 'Completar evaluación';

            const row = document.createElement('tr');
            const tipoBadge = evalItem.tipo || '';

            row.innerHTML = `
                <td>
                    <div class="fw-semibold">${evalItem.nombre}</div>
                    <small class="text-muted">${evalItem.descripcion || ''}</small>
                    ${esUrgente ? '<small class="text-warning d-block"><i class="fas fa-clock"></i> Próxima a vencer</small>' : ''}
                </td>
                <td><span class="badge bg-secondary">${tipoBadge}</span></td>
                <td>${evalItem.curso || 'Sin curso asignado'}</td>
                <td><span class="status-badge status-${evalItem.estado}">${evalItem.estado}</span></td>
                <td class="${esUrgente ? 'text-warning' : ''}">
                    ${formatearFechaEvaluacionesEducacion(evalItem.fecha_vencimiento)}
                    ${esUrgente ? `<br><small>(${diasRestantes} días)</small>` : ''}
                </td>
                <td>
                    <a href="${evalItem.enlace}" target="_blank" rel="noopener" class="btn-evaluacion">
                        <i class="fas fa-external-link-alt"></i>
                        ${etiquetaAccionLista}
                    </a>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function formatearFechaEvaluacionesEducacion(fecha) {
        try {
            return new Date(fecha).toLocaleDateString('es-ES');
        } catch (e) {
            return fecha;
        }
    }

    function verificarEvaluacionesUrgentesEducacion() {
        const alerta = document.getElementById('alertaUrgentesEvaluacionesEdu');
        if (!alerta) return;

        const hoy = new Date();
        const evaluacionesUrgentes = evaluacionesEdu.filter(evalItem => {
            const fechaVencimiento = new Date(evalItem.fecha_vencimiento);
            const diasRestantes = Math.ceil((fechaVencimiento - hoy) / (1000 * 60 * 60 * 24));
            return diasRestantes <= 7 && diasRestantes >= 0;
        });

        alerta.style.display = evaluacionesUrgentes.length > 0 ? 'block' : 'none';
    }

    function cargarEvaluacionesEducacion() {
        fetch('<?= base_url('estudiante/evaluaciones/obtener') ?>')
            .then(response => response.json())
            .then(payload => {
                if (payload.success) {
                    evaluacionesEdu = payload.data || [];
                    verificarEvaluacionesUrgentesEducacion();
                    if (vistaEvaluacionesEdu === 'grid') {
                        generarVistaGridEvaluacionesEducacion();
                    } else {
                        generarVistaListaEvaluacionesEducacion();
                    }
                } else {
                    console.error('Error cargando evaluaciones:', payload.message);
                    showNotification('Error al cargar evaluaciones: ' + payload.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error cargando evaluaciones:', error);
                showNotification('Error al cargar evaluaciones desde el servidor', 'error');
            });
    }

    function cargarEstadisticasEvaluacionesEducacion() {
        fetch('<?= base_url('estudiante/evaluaciones/estadisticas') ?>')
            .then(response => response.json())
            .then(payload => {
                if (!payload.success) return;

                document.getElementById('totalEvaluacionesEdu').textContent = payload.data.total || 0;
                document.getElementById('evaluacionesActivasEdu').textContent = payload.data.activas || 0;
                document.getElementById('evaluacionesPendientesEdu').textContent = payload.data.pendientes || 0;
            })
            .catch(error => {
                console.error('Error cargando estadísticas evaluaciones:', error);
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        cargarEvaluacionesEducacion();
        cargarEstadisticasEvaluacionesEducacion();
    });
</script>

<!-- Incluir FullCalendar CSS y JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
<?= $this->endSection() ?>
<?= $this->extend('coord/layouts/mainCoord') ?>

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

    .fc .fc-daygrid-event .fc-event-title {
        display: block !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        line-height: 1.2;
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

        <!-- Estadísticas y acciones rápidas (una fila en pantallas grandes) -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3 mb-4 align-items-stretch">
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Total Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaActividad')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nueva Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCalendario()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporteEvaluaciones()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
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
                                <button class="nav-link rounded-pill fw-semibold text-info" id="conferencias-tab" data-bs-toggle="tab" data-bs-target="#conferencias" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-users me-2"></i>Conferencias
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-primary" id="capacitacion-tab" data-bs-toggle="tab" data-bs-target="#capacitaciones" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>Capacitación
                                </button>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="showModal('modalFiltros')">
                                <i class="fas fa-filter me-1"></i>Filtros
                            </button>
                        </div>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <!-- Cursos -->
                            <div class="tab-pane fade show active" id="cursos" role="tabpanel">
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
                                                        <th>Encuesta</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaCursos">
                                                    <?php
                                                    $encuestasPorActividad = $encuestasPorActividad ?? [];
                                                    if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Curso'): ?>
                                                                <?php
                                                                $fechaFinC = new DateTime($actividad['FECHA_FIN']);
                                                                $fechaFinC->setTime(0, 0, 0); // Comparar solo por fecha (hoy cuenta como finalizado)
                                                                $finalizadoC = $fechaFinC <= new DateTime('today');
                                                                $encuestaC = $encuestasPorActividad[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? null;
                                                                ?>
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
                                                                        <?php if ($finalizadoC): ?>
                                                                            <span class="badge bg-secondary">Finalizado</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-success">Activo</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($finalizadoC): ?>
                                                                            <?php if ($encuestaC): ?>
                                                                                <a href="<?= esc($encuestaC['ENLACE_FORMULARIO']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm" title="Abrir encuesta"><i class="fas fa-external-link-alt"></i></a>
                                                                            <?php else: ?>
                                                                                <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-success btn-sm" title="Agregar encuesta de satisfacción"><i class="fas fa-plus"></i></a>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">—</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('coord/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted py-4">
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
                                                        <th>Encuesta</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaTalleres">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Taller'): ?>
                                                                <?php
                                                                $fechaFinT = new DateTime($actividad['FECHA_FIN']);
                                                                $fechaFinT->setTime(0, 0, 0);
                                                                $finalizadoT = $fechaFinT <= new DateTime('today');
                                                                $encuestaT = $encuestasPorActividad[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? null;
                                                                ?>
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
                                                                        <?php if ($finalizadoT): ?>
                                                                            <span class="badge bg-secondary">Finalizado</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-warning text-dark">Activo</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($finalizadoT): ?>
                                                                            <?php if ($encuestaT): ?>
                                                                                <a href="<?= esc($encuestaT['ENLACE_FORMULARIO']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm" title="Abrir encuesta"><i class="fas fa-external-link-alt"></i></a>
                                                                            <?php else: ?>
                                                                                <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-success btn-sm" title="Agregar encuesta de satisfacción"><i class="fas fa-plus"></i></a>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">—</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('coord/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted py-4">
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

                            <!-- Conferencias -->
                            <div class="tab-pane fade" id="conferencias" role="tabpanel">
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
                                                        <th>Encuesta</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaConferencias">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Conferencia'): ?>
                                                                <?php
                                                                $fechaFinCo = new DateTime($actividad['FECHA_FIN']);
                                                                $fechaFinCo->setTime(0, 0, 0);
                                                                $finalizadoCo = $fechaFinCo <= new DateTime('today');
                                                                $encuestaCo = $encuestasPorActividad[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? null;
                                                                ?>
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
                                                                        <?php if ($finalizadoCo): ?>
                                                                            <span class="badge bg-secondary">Finalizado</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-success">Activo</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($finalizadoCo): ?>
                                                                            <?php if ($encuestaCo): ?>
                                                                                <a href="<?= esc($encuestaCo['ENLACE_FORMULARIO']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm" title="Abrir encuesta"><i class="fas fa-external-link-alt"></i></a>
                                                                            <?php else: ?>
                                                                                <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-success btn-sm" title="Agregar encuesta de satisfacción"><i class="fas fa-plus"></i></a>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">—</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('coord/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay conferencias registradas</p>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Capacitación -->
                            <div class="tab-pane fade" id="capacitaciones" role="tabpanel">
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
                                                        <th>Encuesta</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaCapacitaciones">
                                                    <?php if (!empty($actividades)): ?>
                                                        <?php foreach ($actividades as $actividad): ?>
                                                            <?php if ($actividad['ACTIVIDAD'] === 'Capacitación'): ?>
                                                                <?php
                                                                $fechaFinCa = new DateTime($actividad['FECHA_FIN']);
                                                                $fechaFinCa->setTime(0, 0, 0);
                                                                $finalizadoCa = $fechaFinCa <= new DateTime('today');
                                                                $encuestaCa = $encuestasPorActividad[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? null;
                                                                ?>
                                                                <tr>
                                                                    <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <i class="fas fa-chalkboard-teacher fa-2x me-2 text-warning"></i>
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
                                                                        <?php if ($finalizadoCa): ?>
                                                                            <span class="badge bg-secondary">Finalizado</span>
                                                                        <?php else: ?>
                                                                            <span class="badge bg-warning text-dark">Activo</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if ($finalizadoCa): ?>
                                                                            <?php if ($encuestaCa): ?>
                                                                                <a href="<?= esc($encuestaCa['ENLACE_FORMULARIO']) ?>" target="_blank" rel="noopener" class="btn btn-success btn-sm" title="Abrir encuesta"><i class="fas fa-external-link-alt"></i></a>
                                                                            <?php else: ?>
                                                                                <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-success btn-sm" title="Agregar encuesta de satisfacción"><i class="fas fa-plus"></i></a>
                                                                            <?php endif; ?>
                                                                        <?php else: ?>
                                                                            <span class="text-muted small">—</span>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-primary" title="Ver Detalle">
                                                                                <i class="fas fa-eye"></i>
                                                                            </a>
                                                                            <a href="<?= base_url('coord/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>" class="btn btn-outline-warning" title="Editar">
                                                                                <i class="fas fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>No hay capacitaciones registradas</p>
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

<!-- Modal Filtros (listados por pestaña) -->
<div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFiltrosLabel">
                    <i class="fas fa-filter me-2"></i>Filtros
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Los filtros se aplican a las tablas de Cursos, Talleres, Conferencias y Capacitaciones en esta página.</p>
                <div class="mb-3">
                    <label class="form-label" for="filtroBusqueda">Buscar</label>
                    <input type="search" class="form-control" id="filtroBusqueda" placeholder="Nombre o descripción de la actividad" autocomplete="off">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="filtroInstructor">Instructor</label>
                    <select class="form-select" id="filtroInstructor">
                        <option value="">Todos</option>
                        <?php if (!empty($instructores)): ?>
                            <?php foreach ($instructores as $instructor): ?>
                                <option value="<?= esc(trim($instructor['NOMBRE'] . ' ' . $instructor['APELLIDO'])) ?>">
                                    <?= esc($instructor['NOMBRE'] . ' ' . $instructor['APELLIDO']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="filtroModalidad">Modalidad</label>
                    <select class="form-select" id="filtroModalidad">
                        <option value="">Todas</option>
                        <?php if (!empty($modalidades)): ?>
                            <?php foreach ($modalidades as $modalidad): ?>
                                <option value="<?= esc($modalidad['MODALIDAD']) ?>"><?= esc($modalidad['MODALIDAD']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mb-0">
                    <label class="form-label" for="filtroEstado">Estado</label>
                    <select class="form-select" id="filtroEstado">
                        <option value="">Todos</option>
                        <option value="activo">Activo</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltrosActividades">Limpiar</button>
                <button type="button" class="btn btn-primary" id="btnAplicarFiltrosActividades">Aplicar</button>
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
                <form id="formNuevaActividad" action="<?= base_url('coord/actividades-educacion/guardar') ?>" method="POST" onsubmit="return validarFormulario()">
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
                                    <a href="<?= base_url('coord/instructores') ?>?crear=1" class="btn btn-outline-primary" type="button" title="Ir a agregar nuevo instructor" target="_self">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                                <small class="text-muted">Selecciona un instructor existente o use el botón + para ir a agregar uno nuevo</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Modalidad<span class="text-danger">*</span></label>
                                <select class="form-select" name="modalidad" id="selectModalidadNuevaActividad" required>
                                    <option value="">Seleccionar modalidad...</option>
                                    <?php if (!empty($modalidades)): ?>
                                        <?php foreach ($modalidades as $modalidad): ?>
                                            <option value="<?= $modalidad['ID_TIPO_MODALIDAD'] ?>" data-modalidad-nombre="<?= esc($modalidad['MODALIDAD'], 'attr') ?>"><?= esc($modalidad['MODALIDAD']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Según la modalidad se pedirá lugar físico, enlace virtual o ambos (híbrida).</small>
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
                        <div class="col-md-6 mb-3 d-none" id="wrapLugarNuevaActividad">
                            <label class="form-label">Lugar <span class="text-danger req-lugar">*</span></label>
                            <input type="text" class="form-control" name="lugar" id="inputLugarNuevaActividad" autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="wrapEnlaceNuevaActividad">
                            <label class="form-label">Enlace <span class="text-danger req-enlace">*</span></label>
                            <input type="url" class="form-control" name="enlace" id="inputEnlaceNuevaActividad" placeholder="https://meet.google.com/..." autocomplete="off">
                            <small class="text-muted">URL de la reunión o plataforma (modalidad virtual o híbrida).</small>
                        </div>
                    </div>
                    <div class="row">
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
                <button type="submit" class="btn btn-primary" form="formNuevaActividad">
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

                <!-- Calendario -->
                <div id="calendario" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                        <p class="text-muted mb-2" id="wrapEventoLugar">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            <span id="eventoLugar">-</span>
                        </p>
                        <p class="text-muted mb-2 d-none" id="wrapEventoEnlace">
                            <i class="fas fa-link me-1"></i>
                            <a id="eventoEnlace" href="#" target="_blank" rel="noopener">Abrir enlace</a>
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
        const el = document.getElementById(modalId);
        if (!el) {
            console.warn('Modal no encontrado:', modalId);
            return;
        }
        const modal = new bootstrap.Modal(el);
        modal.show();
    }

    function aplicarFiltrosActividadesTablas() {
        const q = (document.getElementById('filtroBusqueda')?.value || '').trim().toLowerCase();
        const ins = (document.getElementById('filtroInstructor')?.value || '').trim();
        const mod = (document.getElementById('filtroModalidad')?.value || '').trim();
        const est = (document.getElementById('filtroEstado')?.value || '').trim();
        const ids = ['tablaCursos', 'tablaTalleres', 'tablaConferencias', 'tablaCapacitaciones'];

        ids.forEach((tbodyId) => {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) {
                return;
            }
            const hasFilters = Boolean(q || ins || mod || est);
            tbody.querySelectorAll('tr').forEach((tr) => {
                const emptyCell = tr.querySelector('td[colspan]');
                if (emptyCell) {
                    tr.style.display = hasFilters ? 'none' : '';
                    return;
                }
                const cells = tr.querySelectorAll('td');
                if (cells.length < 7) {
                    return;
                }
                const actividadText = (cells[1]?.innerText || '').toLowerCase();
                const instructorText = (cells[2]?.innerText || '').trim();
                const modalidadText = (cells[3]?.innerText || '').trim();
                const estadoText = (cells[6]?.innerText || '').toLowerCase();

                let show = true;
                if (q && !actividadText.includes(q) && !(tr.innerText || '').toLowerCase().includes(q)) {
                    show = false;
                }
                if (ins && !instructorText.includes(ins)) {
                    show = false;
                }
                if (mod && modalidadText.replace(/\s+/g, ' ') !== mod.replace(/\s+/g, ' ')) {
                    show = false;
                }
                if (est === 'activo' && !estadoText.includes('activo')) {
                    show = false;
                }
                if (est === 'finalizado' && !estadoText.includes('finalizado')) {
                    show = false;
                }
                tr.style.display = show ? '' : 'none';
            });
        });

        const modalEl = document.getElementById('modalFiltros');
        if (modalEl) {
            const instance = bootstrap.Modal.getInstance(modalEl);
            if (instance) {
                instance.hide();
            }
        }
    }

    function limpiarFiltrosActividadesTablas() {
        const b = document.getElementById('filtroBusqueda');
        const i = document.getElementById('filtroInstructor');
        const m = document.getElementById('filtroModalidad');
        const e = document.getElementById('filtroEstado');
        if (b) {
            b.value = '';
        }
        if (i) {
            i.value = '';
        }
        if (m) {
            m.value = '';
        }
        if (e) {
            e.value = '';
        }
        ['tablaCursos', 'tablaTalleres', 'tablaConferencias', 'tablaCapacitaciones'].forEach((tbodyId) => {
            const tbody = document.getElementById(tbodyId);
            if (!tbody) {
                return;
            }
            tbody.querySelectorAll('tr').forEach((tr) => {
                tr.style.display = '';
            });
        });
    }

    document.getElementById('btnAplicarFiltrosActividades')?.addEventListener('click', aplicarFiltrosActividadesTablas);
    document.getElementById('btnLimpiarFiltrosActividades')?.addEventListener('click', () => {
        limpiarFiltrosActividadesTablas();
    });

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
            const response = await fetch('<?= base_url('coord/actividades-educacion/calendario') ?>');
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

        const vistos = new Set();
        const eventosUnicos = (eventos || []).filter(e => {
            const k = String(e.id);
            if (vistos.has(k)) return false;
            vistos.add(k);
            return true;
        });

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
        const lugar = (evento.extendedProps.lugar || '').trim();
        const enlace = (evento.extendedProps.enlace || '').trim();
        document.getElementById('eventoLugar').textContent = lugar || '—';
        document.getElementById('wrapEventoLugar').classList.toggle('d-none', !lugar);
        const aEn = document.getElementById('eventoEnlace');
        const wEn = document.getElementById('wrapEventoEnlace');
        if (enlace) {
            aEn.href = enlace.match(/^https?:\/\//i) ? enlace : 'https://' + enlace;
            aEn.textContent = enlace;
            wEn.classList.remove('d-none');
        } else {
            wEn.classList.add('d-none');
        }
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
            const response = await fetch('<?= base_url('coord/actividades-educacion/api/estadisticas') ?>');
            const stats = await response.json();

            // Actualizar las estadísticas en la interfaz
            document.getElementById('totalActividades').textContent = stats.totalActividades || 0;
            document.getElementById('cursosActivos').textContent = stats.cursosActivos || 0;
            document.getElementById('talleresActivos').textContent = stats.talleresActivos || 0;
            document.getElementById('conferenciasActivos').textContent = stats.conferenciasActivos || 0;
            document.getElementById('capacitacionesActivos').textContent = stats.capacitacionesActivos || 0;

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
        window.location.href = '<?= base_url('coord/actividades-educacion/reportes') ?>';
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
        const url = `<?= base_url('coord/actividades-educacion/exportar') ?>/${formato}`;

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

    function actividadEduSlugModalidad(texto) {
        const t = (texto || '').toLowerCase();
        if (/híbr|hibr|semi[\s\-]?presencial/.test(t)) {
            return 'hibrida';
        }
        if (/virtual|en\s+l[ií]nea|l[ií]nea|remoto|online|distancia/.test(t)) {
            return 'virtual';
        }
        if (/presencial/.test(t)) {
            return 'presencial';
        }
        return '';
    }

    function actividadEduSincronizarLugarEnlaceNuevaActividad() {
        const sel = document.getElementById('selectModalidadNuevaActividad');
        if (!sel) {
            return;
        }
        const opt = sel.options[sel.selectedIndex];
        const label = opt ? (opt.getAttribute('data-modalidad-nombre') || opt.textContent || '').trim() : '';
        const slug = actividadEduSlugModalidad(label);
        const wL = document.getElementById('wrapLugarNuevaActividad');
        const wE = document.getElementById('wrapEnlaceNuevaActividad');
        const iL = document.getElementById('inputLugarNuevaActividad');
        const iE = document.getElementById('inputEnlaceNuevaActividad');
        if (!wL || !wE || !iL || !iE) {
            return;
        }
        const showL = slug === 'presencial' || slug === 'hibrida';
        const showE = slug === 'virtual' || slug === 'hibrida';
        wL.classList.toggle('d-none', !showL);
        wE.classList.toggle('d-none', !showE);
        iL.required = showL;
        iE.required = showE;
        if (!showL) {
            iL.value = '';
            iL.classList.remove('is-invalid');
        }
        if (!showE) {
            iE.value = '';
            iE.classList.remove('is-invalid');
        }
    }

    // Función de validación del formulario
    function validarFormulario() {
        const camposObligatorios = [{
                name: 'tipo_actividad',
                label: 'Tipo de Actividad'
            },
            {
                name: 'nombre_actividad',
                label: 'Nombre de la Actividad'
            },
            {
                name: 'instructor',
                label: 'Instructor'
            },
            {
                name: 'modalidad',
                label: 'Modalidad'
            },
            {
                name: 'descripcion',
                label: 'Descripción'
            },
            {
                name: 'objetivos',
                label: 'Objetivos'
            },
            {
                name: 'duracion_horas',
                label: 'Duración (horas)'
            },
            {
                name: 'fecha_inicio',
                label: 'Fecha de Inicio'
            },
            {
                name: 'fecha_fin',
                label: 'Fecha de Fin'
            },
            {
                name: 'horario',
                label: 'Horario'
            }
        ];

        let errores = [];
        let camposVacios = [];

        // Validar campos obligatorios
        camposObligatorios.forEach(campo => {
            const elemento = document.querySelector(`#formNuevaActividad [name="${campo.name}"]`);
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

        const selMod = document.getElementById('selectModalidadNuevaActividad');
        if (selMod) {
            const opt = selMod.options[selMod.selectedIndex];
            const label = opt ? (opt.getAttribute('data-modalidad-nombre') || opt.textContent || '').trim() : '';
            const slug = actividadEduSlugModalidad(label);
            if (!slug) {
                camposVacios.push('Modalidad');
                selMod.classList.add('is-invalid');
            } else {
                if (slug === 'presencial' || slug === 'hibrida') {
                    const el = document.querySelector('#formNuevaActividad [name="lugar"]');
                    if (el && !el.value.trim()) {
                        camposVacios.push('Lugar');
                        el.classList.add('is-invalid');
                    }
                }
                if (slug === 'virtual' || slug === 'hibrida') {
                    const el = document.querySelector('#formNuevaActividad [name="enlace"]');
                    if (el && !el.value.trim()) {
                        camposVacios.push('Enlace (URL)');
                        el.classList.add('is-invalid');
                    }
                }
            }
        }

        // Validar que fecha fin sea posterior a fecha inicio
        const fechaInicio = document.querySelector('#formNuevaActividad [name="fecha_inicio"]').value;
        const fechaFin = document.querySelector('#formNuevaActividad [name="fecha_fin"]').value;

        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);

            if (fin <= inicio) {
                errores.push('La fecha de fin debe ser posterior a la fecha de inicio');
                document.querySelector('#formNuevaActividad [name="fecha_fin"]').classList.add('is-invalid');
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
        const fechaInicioInput = document.querySelector('#formNuevaActividad input[name="fecha_inicio"]');
        if (fechaInicioInput) {
            fechaInicioInput.value = today;
        }

        const selMod = document.getElementById('selectModalidadNuevaActividad');
        if (selMod) {
            selMod.addEventListener('change', actividadEduSincronizarLugarEnlaceNuevaActividad);
        }
        const modalNueva = document.getElementById('modalNuevaActividad');
        if (modalNueva) {
            modalNueva.addEventListener('shown.bs.modal', actividadEduSincronizarLugarEnlaceNuevaActividad);
        }
        actividadEduSincronizarLugarEnlaceNuevaActividad();

        // Redirigir a instructores cuando el usuario elige "agregar instructor" en el select
        const selectInstructor = document.getElementById('selectInstructor');
        if (selectInstructor) {
            selectInstructor.addEventListener('change', function() {
                if (this.value === '__agregar_instructor__') {
                    window.location.href = '<?= base_url('coord/instructores') ?>?crear=1';
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

<?= $this->endSection() ?>
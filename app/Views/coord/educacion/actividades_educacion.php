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

    /* Horario interactivo – botones de días */
    .dia-btn {
        transition: all 0.2s ease;
        font-weight: 500;
        min-width: 60px;
    }
    .dia-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.25);
    }
    .dia-btn.active {
        transform: scale(1.05);
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.35);
    }
    .dia-check {
        transition: opacity 0.15s ease;
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
                <div class="card text-center shadow-sm h-100"
                    style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Total Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaActividad')"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2"
                                style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nueva Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCalendario()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2"
                                style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporteEvaluaciones()"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2"
                                style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarEvaluaciones()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2"
                                style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // Preprocesar actividades en vigentes y expiradas
        $actividadesVigentes = [];
        $actividadesExpiradas = [];
        $encuestasPorActividad = $encuestasPorActividad ?? [];
        if (!empty($actividades)) {
            $hoy = new DateTime('today');
            foreach ($actividades as $act) {
                $fechaFin = new DateTime($act['FECHA_FIN']);
                $fechaFin->setTime(0, 0, 0);
                if ($fechaFin <= $hoy) {
                    $actividadesExpiradas[] = $act;
                } else {
                    $actividadesVigentes[] = $act;
                }
            }
        }
        ?>

        <!-- Tabs Navigation -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="actividadesTabs"
                            role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-success" id="vigentes-tab"
                                    data-bs-toggle="tab" data-bs-target="#vigentes" type="button" role="tab"
                                    aria-selected="true">
                                    <i class="fas fa-check-circle me-2"></i>Actividades Vigentes
                                    <span class="badge bg-success ms-1"><?= count($actividadesVigentes) ?></span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-secondary" id="expiradas-tab"
                                    data-bs-toggle="tab" data-bs-target="#expiradas" type="button" role="tab"
                                    aria-selected="false">
                                    <i class="fas fa-history me-2"></i>Actividades Expiradas
                                    <span class="badge bg-secondary ms-1"><?= count($actividadesExpiradas) ?></span>
                                </button>
                            </li>
                        </ul>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                onclick="showModal('modalFiltros')">
                                <i class="fas fa-filter me-1"></i>Filtros
                            </button>
                        </div>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="actividadesTabContent">
                            <?php
                            // Función helper para renderizar icono según tipo de actividad
                            function iconoActividad($tipo) {
                                $iconos = [
                                    'Curso' => 'fas fa-laptop-code text-primary',
                                    'Taller' => 'fas fa-wrench text-success',
                                    'Conferencia' => 'fas fa-comments text-info',
                                    'Capacitación' => 'fas fa-chalkboard-teacher text-warning',
                                ];
                                return $iconos[$tipo] ?? 'fas fa-book text-secondary';
                            }
                            function badgeTipo($tipo) {
                                $colores = [
                                    'Curso' => 'bg-primary',
                                    'Taller' => 'bg-success',
                                    'Conferencia' => 'bg-info',
                                    'Capacitación' => 'bg-warning text-dark',
                                ];
                                return $colores[$tipo] ?? 'bg-secondary';
                            }
                            // Renderizar tabla de actividades
                            function renderTablaActividades($listaActs, $encuestasPorAct, $tbodyId, $esVigente) {
                            ?>
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-striped align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actividad</th>
                                                    <th>Tipo</th>
                                                    <th>Instructor</th>
                                                    <th>Modalidad</th>
                                                    <th>Período</th>
                                                    <th>Duración</th>
                                                    <th>Estado</th>
                                                    <th>Encuesta</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="<?= $tbodyId ?>">
                                                <?php if (!empty($listaActs)): ?>
                                                    <?php foreach ($listaActs as $actividad):
                                                        $enc = $encuestasPorAct[$actividad['ID_ACTIVIDAD_EDUCACION']] ?? null;
                                                        $fvList = '';
                                                        if ($enc && !empty($enc['FECHA_VENCIMIENTO'])) {
                                                            $fvList = substr((string) $enc['FECHA_VENCIMIENTO'], 0, 10);
                                                        }
                                                        $tipoAct = $actividad['ACTIVIDAD'] ?? '';
                                                    ?>
                                                    <tr>
                                                        <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="<?= iconoActividad($tipoAct) ?> fa-2x me-2"></i>
                                                                <div>
                                                                    <div class="fw-semibold"><?= $actividad['NOMBRE_ACTIVIDAD'] ?></div>
                                                                    <small class="text-muted"><?= $actividad['DESCRIPCION'] ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge <?= badgeTipo($tipoAct) ?>"><?= $tipoAct ?></span></td>
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
                                                            <?php if ($esVigente): ?>
                                                                <span class="badge bg-success">Activo</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Finalizado</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($enc): ?>
                                                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                                                    <button type="button"
                                                                        class="btn btn-outline-success btn-sm js-abrir-modal-encuesta-listado"
                                                                        title="Agregar o cambiar enlace de encuesta"
                                                                        data-encuesta-modo="editar"
                                                                        data-actividad-id="<?= (int) $actividad['ID_ACTIVIDAD_EDUCACION'] ?>"
                                                                        data-actividad-nombre="<?= esc($actividad['NOMBRE_ACTIVIDAD'], 'attr') ?>"
                                                                        data-evaluacion-id="<?= (int) ($enc['ID_EVALUACION_ENLACE'] ?? 0) ?>"
                                                                        data-nombre-evaluacion="<?= esc($enc['NOMBRE_EVALUACION'] ?? '', 'attr') ?>"
                                                                        data-enlace-formulario="<?= esc($enc['ENLACE_FORMULARIO'] ?? '', 'attr') ?>"
                                                                        data-descripcion="<?= esc($enc['DESCRIPCION'] ?? '', 'attr') ?>"
                                                                        data-fecha-vencimiento="<?= esc($fvList, 'attr') ?>"
                                                                        data-estado="<?= esc($enc['ESTADO'] ?? 'activo', 'attr') ?>"><i
                                                                            class="fas fa-link me-1"></i>Agregar enlace</button>
                                                                    <a href="<?= esc($enc['ENLACE_FORMULARIO'], 'attr') ?>"
                                                                        target="_blank" rel="noopener"
                                                                        class="btn btn-outline-success btn-sm"
                                                                        title="Abrir formulario de la encuesta"><i
                                                                            class="fas fa-external-link-alt me-1"></i>Abrir encuesta</a>
                                                                </div>
                                                            <?php else: ?>
                                                                <button type="button"
                                                                    class="btn btn-outline-success btn-sm js-abrir-modal-encuesta-listado"
                                                                    title="Agregar enlace de encuesta de satisfacción"
                                                                    data-encuesta-modo="nuevo"
                                                                    data-actividad-id="<?= (int) $actividad['ID_ACTIVIDAD_EDUCACION'] ?>"
                                                                    data-actividad-nombre="<?= esc($actividad['NOMBRE_ACTIVIDAD'], 'attr') ?>"><i
                                                                        class="fas fa-link me-1"></i>Agregar enlace</button>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="<?= base_url('coord/actividades-educacion/ver/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>"
                                                                    class="btn btn-outline-primary" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="<?= base_url('coord/actividades-educacion/editar/' . $actividad['ID_ACTIVIDAD_EDUCACION']) ?>"
                                                                    class="btn btn-outline-warning" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="10" class="text-center text-muted py-4">
                                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                                            <p>No hay actividades <?= $esVigente ? 'vigentes' : 'expiradas' ?></p>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <!-- Vigentes -->
                            <div class="tab-pane fade show active" id="vigentes" role="tabpanel">
                                <?php renderTablaActividades($actividadesVigentes, $encuestasPorActividad, 'tablaVigentes', true); ?>
                            </div>

                            <!-- Expiradas -->
                            <div class="tab-pane fade" id="expiradas" role="tabpanel">
                                <?php renderTablaActividades($actividadesExpiradas, $encuestasPorActividad, 'tablaExpiradas', false); ?>
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
                <p class="text-muted small mb-3">Los filtros se aplican a las tablas de actividades vigentes y expiradas.</p>
                <div class="mb-3">
                    <label class="form-label" for="filtroBusqueda">Buscar</label>
                    <input type="search" class="form-control" id="filtroBusqueda"
                        placeholder="Nombre o descripción de la actividad" autocomplete="off">
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
                <button type="button" class="btn btn-outline-secondary"
                    id="btnLimpiarFiltrosActividades">Limpiar</button>
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
                <form id="formNuevaActividad" action="<?= base_url('coord/actividades-educacion/guardar') ?>"
                    method="POST" onsubmit="return validarFormulario()">
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
                                <label class="form-label">Nombre de la Actividad<span
                                        class="text-danger">*</span></label>
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
                                                <option value="<?= $instructor['ID_INSTRUCTOR'] ?>"><?= $instructor['NOMBRE'] ?>
                                                    <?= $instructor['APELLIDO'] ?> - <?= $instructor['ESPECIALIDAD'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                        <option value="__agregar_instructor__">➕ No está en la lista — Ir a agregar
                                            instructor</option>
                                    </select>
                                    <a href="<?= base_url('coord/instructores') ?>?crear=1"
                                        class="btn btn-outline-primary" type="button"
                                        title="Ir a agregar nuevo instructor" target="_self">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                                <small class="text-muted">Selecciona un instructor existente o use el botón + para ir a
                                    agregar uno nuevo</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Modalidad<span class="text-danger">*</span></label>
                                <select class="form-select" name="modalidad" id="selectModalidadNuevaActividad"
                                    required>
                                    <option value="">Seleccionar modalidad...</option>
                                    <?php if (!empty($modalidades)): ?>
                                        <?php foreach ($modalidades as $modalidad): ?>
                                            <option value="<?= $modalidad['ID_TIPO_MODALIDAD'] ?>"
                                                data-modalidad-nombre="<?= esc($modalidad['MODALIDAD'], 'attr') ?>">
                                                <?= esc($modalidad['MODALIDAD']) ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Según la modalidad se pedirá lugar físico, enlace virtual o
                                    ambos (híbrida).</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fechaInicioNueva" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_fin" id="fechaFinNueva" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Duración (horas)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duracion_horas" id="duracionHorasNueva" min="1" required>
                                <div id="duracionInfoNueva" class="mt-1" style="min-height:1.4em;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3 d-none" id="wrapLugarNuevaActividad">
                            <label class="form-label">Lugar <span class="text-danger req-lugar">*</span></label>
                            <input type="text" class="form-control" name="lugar" id="inputLugarNuevaActividad"
                                autocomplete="off">
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="wrapEnlaceNuevaActividad">
                            <label class="form-label">Enlace <span class="text-danger req-enlace">*</span></label>
                            <input type="url" class="form-control" name="enlace" id="inputEnlaceNuevaActividad"
                                placeholder="https://meet.google.com/..." autocomplete="off">
                            <small class="text-muted">URL de la reunión o plataforma (modalidad virtual o
                                híbrida).</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Horario<span class="text-danger">*</span></label>
                                <input type="hidden" name="horario" id="horarioHiddenNueva" required>
                                <div class="card border rounded-3 p-3" style="background: #f8f9fc;">
                                    <!-- Días de la semana -->
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-2"><i class="fas fa-calendar-day me-1"></i>Selecciona los días</small>
                                        <div class="d-flex flex-wrap gap-2" id="diasSelectorNueva">
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 dia-btn" data-dia="Lunes">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Lun
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 dia-btn" data-dia="Martes">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Mar
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 dia-btn" data-dia="Miércoles">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Mié
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 dia-btn" data-dia="Jueves">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Jue
                                            </button>
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 dia-btn" data-dia="Viernes">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Vie
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dia-btn" data-dia="Sábado">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Sáb
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 dia-btn" data-dia="Domingo">
                                                <i class="fas fa-check me-1 d-none dia-check"></i>Dom
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Horas -->
                                    <div class="row g-2 align-items-end">
                                        <div class="col-auto">
                                            <small class="text-muted d-block mb-1"><i class="fas fa-clock me-1"></i>Hora inicio</small>
                                            <input type="time" class="form-control form-control-sm" id="horaInicioNueva" value="08:00" style="width:130px;">
                                        </div>
                                        <div class="col-auto d-flex align-items-center pt-3">
                                            <span class="text-muted fw-bold">—</span>
                                        </div>
                                        <div class="col-auto">
                                            <small class="text-muted d-block mb-1"><i class="fas fa-clock me-1"></i>Hora fin</small>
                                            <input type="time" class="form-control form-control-sm" id="horaFinNueva" value="12:00" style="width:130px;">
                                        </div>
                                    </div>
                                    <!-- Preview -->
                                    <div class="mt-3 pt-2 border-top">
                                        <small class="text-muted"><i class="fas fa-eye me-1"></i>Vista previa:</small>
                                        <div id="horarioPreviewNueva" class="fw-semibold text-primary mt-1" style="min-height:1.5em;">
                                            <span class="text-muted fst-italic">Selecciona al menos un día</span>
                                        </div>
                                    </div>
                                </div>
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

<!-- Modal: agregar enlace de encuesta (desde columna Encuesta del listado) -->
<div class="modal fade" id="modalEncuestaListadoCoord" tabindex="-1" aria-labelledby="modalEncuestaListadoCoordLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalEncuestaListadoCoordLabel"><i
                        class="fas fa-clipboard-check me-2"></i><span id="modalEncuestaListadoCoordTituloTexto">Encuesta
                        de satisfacción — agregar enlace</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <form id="formEncuestaListadoCoord">
                    <?= csrf_field() ?>
                    <input type="hidden" id="encuestaListado_evaluacion_id" value="">
                    <input type="hidden" name="curso_id" id="encuestaListado_curso_id" value="">
                    <input type="hidden" name="tipo_evaluacion" value="satisfaccion">
                    <div class="mb-3">
                        <label class="form-label">Actividad</label>
                        <input type="text" class="form-control" id="encuestaListado_curso_nombre" value="" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre de la evaluación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nombre_evaluacion"
                            id="encuestaListado_nombre_evaluacion" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Enlace del formulario <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="enlace_formulario"
                            id="encuestaListado_enlace_formulario" placeholder="https://forms.google.com/..." required
                            autocomplete="off">
                        <small class="text-muted">Pegue aquí la URL de Google Forms, Microsoft Forms, etc.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="encuestaListado_descripcion" rows="2"
                            placeholder="Opcional"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Fecha de vencimiento <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_vencimiento"
                                id="encuestaListado_fecha_vencimiento" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="encuestaListado_estado">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                                <option value="borrador">Borrador</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="btnGuardarEncuestaListadoCoord">
                    <i class="fas fa-save me-1"></i><span id="btnGuardarEncuestaListadoCoordTexto">Guardar enlace</span>
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
                <div id="calendario"
                    style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                </div>
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
        const ids = ['tablaVigentes', 'tablaExpiradas'];

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
                const instructorText = (cells[3]?.innerText || '').trim();
                const modalidadText = (cells[4]?.innerText || '').trim();
                const estadoText = (cells[7]?.innerText || '').toLowerCase();

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
        ['tablaVigentes', 'tablaExpiradas'].forEach((tbodyId) => {
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
                eventContent: function (arg) {
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
                eventClick: function (info) {
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
            const totalActividadesEl = document.getElementById('totalActividades');
            if (totalActividadesEl) totalActividadesEl.textContent = stats.totalActividades || 0;

            const cursosActivosEl = document.getElementById('cursosActivos');
            if (cursosActivosEl) cursosActivosEl.textContent = stats.cursosActivos || 0;

            const talleresActivosEl = document.getElementById('talleresActivos');
            if (talleresActivosEl) talleresActivosEl.textContent = stats.talleresActivos || 0;

            const conferenciasActivosEl = document.getElementById('conferenciasActivos');
            if (conferenciasActivosEl) conferenciasActivosEl.textContent = stats.conferenciasActivos || 0;

            const capacitacionesActivosEl = document.getElementById('capacitacionesActivos');
            if (capacitacionesActivosEl) capacitacionesActivosEl.textContent = stats.capacitacionesActivos || 0;

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
        modal.addEventListener('hidden.bs.modal', function () {
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

    // ── Horario interactivo (selector de días + horas + sincronización fechas/duración) ──
    function initHorarioPicker(opts) {
        const container    = document.getElementById(opts.containerId);
        const horaInicio   = document.getElementById(opts.horaInicioId);
        const horaFin      = document.getElementById(opts.horaFinId);
        const preview      = document.getElementById(opts.previewId);
        const hidden       = document.getElementById(opts.hiddenId);
        const fechaInicioEl = document.getElementById(opts.fechaInicioId);
        const fechaFinEl    = document.getElementById(opts.fechaFinId);
        const duracionEl    = document.getElementById(opts.duracionId);
        const duracionInfo  = document.getElementById(opts.duracionInfoId);
        if (!container) return;

        const btns = container.querySelectorAll('.dia-btn');
        // Mapa: nombre día → JS getDay() (0=Domingo)
        const diaToJS = { 'Lunes': 1, 'Martes': 2, 'Miércoles': 3, 'Jueves': 4, 'Viernes': 5, 'Sábado': 6, 'Domingo': 0 };
        const ordenDias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        /** Cuenta cuántos de los días seleccionados caen en el rango fecha inicio–fin */
        function contarDiasEnRango(diasSel) {
            if (!fechaInicioEl || !fechaFinEl) return 0;
            const fi = fechaInicioEl.value;
            const ff = fechaFinEl.value;
            if (!fi || !ff) return 0;

            const inicio = new Date(fi + 'T00:00:00');
            const fin    = new Date(ff + 'T00:00:00');
            if (isNaN(inicio) || isNaN(fin) || fin < inicio) return 0;

            const jsDias = new Set(diasSel.map(d => diaToJS[d]));
            let count = 0;
            const cur = new Date(inicio);
            while (cur <= fin) {
                if (jsDias.has(cur.getDay())) count++;
                cur.setDate(cur.getDate() + 1);
            }
            return count;
        }

        /** Horas diarias a partir del rango horario */
        function horasDiarias() {
            if (!horaInicio || !horaFin || !horaInicio.value || !horaFin.value) return 0;
            const [h1, m1] = horaInicio.value.split(':').map(Number);
            const [h2, m2] = horaFin.value.split(':').map(Number);
            const diff = (h2 * 60 + m2) - (h1 * 60 + m1);
            return diff > 0 ? +(diff / 60).toFixed(1) : 0;
        }

        function actualizarTodo() {
            // 1. Obtener días seleccionados
            const diasSeleccionados = [];
            btns.forEach(btn => {
                if (btn.classList.contains('active')) {
                    diasSeleccionados.push(btn.getAttribute('data-dia'));
                }
            });
            const hi = horaInicio ? horaInicio.value : '';
            const hf = horaFin ? horaFin.value : '';

            // 2. Generar texto horario
            if (diasSeleccionados.length === 0) {
                if (preview) preview.innerHTML = '<span class="text-muted fst-italic">Selecciona al menos un día</span>';
                if (hidden) hidden.value = '';
            } else {
                const indices = diasSeleccionados.map(d => ordenDias.indexOf(d)).sort((a, b) => a - b);
                let grupos = [], grupoActual = [indices[0]];
                for (let i = 1; i < indices.length; i++) {
                    if (indices[i] === grupoActual[grupoActual.length - 1] + 1) {
                        grupoActual.push(indices[i]);
                    } else {
                        grupos.push(grupoActual);
                        grupoActual = [indices[i]];
                    }
                }
                grupos.push(grupoActual);
                const partes = grupos.map(g => {
                    if (g.length >= 3) return ordenDias[g[0]] + ' a ' + ordenDias[g[g.length - 1]];
                    return g.map(i => ordenDias[i]).join(', ');
                });
                let texto = partes.join('; ');
                if (hi && hf) texto += ' ' + hi + '-' + hf;
                if (preview) preview.innerHTML = '<i class="fas fa-calendar-check me-1 text-success"></i>' + texto;
                if (hidden) hidden.value = texto;
            }

            // 3. Calcular duración automática
            const hd = horasDiarias();
            const totalDias = contarDiasEnRango(diasSeleccionados);
            const totalHoras = Math.round(hd * totalDias);

            if (duracionInfo) {
                if (totalDias > 0 && hd > 0) {
                    duracionInfo.innerHTML =
                        '<small class="text-info">' +
                        '<i class="fas fa-calculator me-1"></i>' +
                        totalDias + ' día' + (totalDias !== 1 ? 's' : '') +
                        ' × ' + hd + 'h/día = <strong>' + totalHoras + 'h</strong>' +
                        '</small>';
                    // Auto-rellenar duración si el usuario no la ha editado manualmente
                    if (duracionEl && !duracionEl.dataset.manual) {
                        duracionEl.value = totalHoras;
                    }
                } else if (diasSeleccionados.length > 0 && (!fechaInicioEl?.value || !fechaFinEl?.value)) {
                    duracionInfo.innerHTML = '<small class="text-muted fst-italic"><i class="fas fa-info-circle me-1"></i>Selecciona fechas para calcular automáticamente</small>';
                } else {
                    duracionInfo.innerHTML = '';
                }
            }
        }

        // Toggle botones de días
        btns.forEach(btn => {
            btn.addEventListener('click', function () {
                this.classList.toggle('active');
                const check = this.querySelector('.dia-check');
                if (this.classList.contains('active')) {
                    this.classList.remove('btn-outline-primary', 'btn-outline-secondary');
                    this.classList.add('btn-primary', 'text-white');
                    if (check) check.classList.remove('d-none');
                } else {
                    const esFinde = ['Sábado', 'Domingo'].includes(this.getAttribute('data-dia'));
                    this.classList.remove('btn-primary', 'text-white');
                    this.classList.add(esFinde ? 'btn-outline-secondary' : 'btn-outline-primary');
                    if (check) check.classList.add('d-none');
                }
                // Resetear flag manual al cambiar días
                if (duracionEl) delete duracionEl.dataset.manual;
                actualizarTodo();
            });
        });

        // Escuchar cambios en horas
        if (horaInicio) horaInicio.addEventListener('change', () => { if (duracionEl) delete duracionEl.dataset.manual; actualizarTodo(); });
        if (horaFin)    horaFin.addEventListener('change',    () => { if (duracionEl) delete duracionEl.dataset.manual; actualizarTodo(); });

        // Escuchar cambios en fechas
        if (fechaInicioEl) fechaInicioEl.addEventListener('change', () => {
            // Ajustar fecha_fin mínima
            if (fechaFinEl && fechaInicioEl.value) {
                fechaFinEl.min = fechaInicioEl.value;
                if (fechaFinEl.value && fechaFinEl.value < fechaInicioEl.value) {
                    fechaFinEl.value = fechaInicioEl.value;
                }
            }
            if (duracionEl) delete duracionEl.dataset.manual;
            actualizarTodo();
        });
        if (fechaFinEl) fechaFinEl.addEventListener('change', () => {
            if (duracionEl) delete duracionEl.dataset.manual;
            actualizarTodo();
        });

        // Marcar como manual si el usuario edita la duración directamente
        if (duracionEl) {
            duracionEl.addEventListener('input', function () {
                this.dataset.manual = '1';
            });
        }

        // Sincronizar estado inicial
        actualizarTodo();
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
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        const fechaInicioInput = document.getElementById('fechaInicioNueva');
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

        // Inicializar selector interactivo de horario (sincronizado con fechas y duración)
        initHorarioPicker({
            containerId:   'diasSelectorNueva',
            horaInicioId:  'horaInicioNueva',
            horaFinId:     'horaFinNueva',
            previewId:     'horarioPreviewNueva',
            hiddenId:      'horarioHiddenNueva',
            fechaInicioId: 'fechaInicioNueva',
            fechaFinId:    'fechaFinNueva',
            duracionId:    'duracionHorasNueva',
            duracionInfoId:'duracionInfoNueva'
        });

        // Redirigir a instructores cuando el usuario elige "agregar instructor" en el select
        const selectInstructor = document.getElementById('selectInstructor');
        if (selectInstructor) {
            selectInstructor.addEventListener('change', function () {
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
            campo.addEventListener('input', function () {
                this.classList.remove('is-invalid');
            });
        });

        // Encuesta: modal desde la columna (nuevo = agregar, editar = actualizar)
        document.querySelectorAll('.js-abrir-modal-encuesta-listado').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const modo = this.getAttribute('data-encuesta-modo') || 'nuevo';
                const idAct = this.getAttribute('data-actividad-id') || '';
                const nombre = this.getAttribute('data-actividad-nombre') || '';
                const idEvField = document.getElementById('encuestaListado_evaluacion_id');
                const fv = document.getElementById('encuestaListado_fecha_vencimiento');
                const hoy = new Date().toISOString().slice(0, 10);
                const fin = new Date();
                fin.setMonth(fin.getMonth() + 6);
                const fechaDef = fin.toISOString().slice(0, 10);

                document.getElementById('encuestaListado_curso_id').value = idAct;
                document.getElementById('encuestaListado_curso_nombre').value = nombre;

                const tituloModalTxt = document.getElementById('modalEncuestaListadoCoordTituloTexto');
                const btnTxt = document.getElementById('btnGuardarEncuestaListadoCoordTexto');

                if (modo === 'editar') {
                    idEvField.value = this.getAttribute('data-evaluacion-id') || '';
                    document.getElementById('encuestaListado_nombre_evaluacion').value = this.getAttribute('data-nombre-evaluacion') || '';
                    document.getElementById('encuestaListado_enlace_formulario').value = this.getAttribute('data-enlace-formulario') || '';
                    document.getElementById('encuestaListado_descripcion').value = this.getAttribute('data-descripcion') || '';
                    fv.min = hoy;
                    fv.value = this.getAttribute('data-fecha-vencimiento') || fechaDef;
                    document.getElementById('encuestaListado_estado').value = this.getAttribute('data-estado') || 'activo';
                    if (tituloModalTxt) {
                        tituloModalTxt.textContent = 'Encuesta de satisfacción — agregar o cambiar enlace';
                    }
                    if (btnTxt) {
                        btnTxt.textContent = 'Guardar cambios';
                    }
                } else {
                    idEvField.value = '';
                    document.getElementById('encuestaListado_nombre_evaluacion').value = 'Encuesta de satisfacción - ' + nombre;
                    document.getElementById('encuestaListado_enlace_formulario').value = '';
                    document.getElementById('encuestaListado_descripcion').value = '';
                    document.getElementById('encuestaListado_estado').value = 'activo';
                    fv.min = hoy;
                    fv.value = fechaDef;
                    if (tituloModalTxt) {
                        tituloModalTxt.textContent = 'Encuesta de satisfacción — agregar enlace';
                    }
                    if (btnTxt) {
                        btnTxt.textContent = 'Guardar enlace';
                    }
                }
                new bootstrap.Modal(document.getElementById('modalEncuestaListadoCoord')).show();
            });
        });

        const btnGuardarEncLista = document.getElementById('btnGuardarEncuestaListadoCoord');
        if (btnGuardarEncLista) {
            btnGuardarEncLista.addEventListener('click', function () {
                const form = document.getElementById('formEncuestaListadoCoord');
                if (!form || !form.checkValidity()) {
                    if (form) {
                        form.reportValidity();
                    }
                    return;
                }
                const btn = this;
                const orig = btn.innerHTML;
                const idEv = (document.getElementById('encuestaListado_evaluacion_id') || {}).value || '';
                const url = idEv
                    ? '<?= base_url('coord/evaluaciones/actualizar') ?>/' + encodeURIComponent(idEv)
                    : '<?= base_url('coord/evaluaciones/agregar') ?>';

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
                const fd = new FormData(form);
                fetch(url, {
                    method: 'POST',
                    body: fd,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(function (r) {
                    return r.json();
                }).then(function (data) {
                    if (data.success) {
                        const inst = bootstrap.Modal.getInstance(document.getElementById('modalEncuestaListadoCoord'));
                        if (inst) {
                            inst.hide();
                        }
                        showNotification(data.message || 'Enlace guardado. Actualizando listado...', 'success');
                        setTimeout(function () {
                            window.location.reload();
                        }, 900);
                    } else {
                        showNotification(data.message || 'No se pudo guardar el enlace', 'error');
                        btn.disabled = false;
                        btn.innerHTML = orig;
                    }
                }).catch(function () {
                    showNotification('Error de conexión al guardar', 'error');
                    btn.disabled = false;
                    btn.innerHTML = orig;
                });
            });
        }
    });
</script>

<!-- Incluir FullCalendar CSS y JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/es.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?= $this->endSection() ?>
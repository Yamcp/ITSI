<?= $this->extend('coord/layouts/mainCoord') ?>
<?php
$estadisticas = $estadisticas ?? [
    'totalPracticas' => 0,
    'practicasActivas' => 0,
];
$practicasPreprofesionales = $practicasPreprofesionales ?? [];
$serviciosComunitarios = $serviciosComunitarios ?? [];
$carreras = $carreras ?? [];
?>
<?= $this->section('styles') ?>
<!-- CSS personalizado para prácticas y pestañas -->
<style>
    /* Pestañas estilo pill */
    #practicasTabs.nav-tabs {
        border: none;
        gap: 0.5rem;
        border-radius: 50rem;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
    }
    #practicasTabs .nav-link {
        border: none;
        color: #495057 !important;
        background-color: transparent;
        padding: 0.6rem 1.1rem;
        border-radius: 50rem;
        font-weight: 600;
        transition: background-color 0.2s, color 0.2s;
    }
    #practicasTabs .nav-link:hover {
        background-color: #e9ecef;
        color: #212529 !important;
    }
    #practicasTabs .nav-link.active {
        background-color: #fff !important;
        color: #0d6efd !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    #practicasTabs .nav-link i {
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
                <h3 class="text-center my-3">
                    <i class="fas fa-briefcase me-2"></i>
                    Gestión de Prácticas
                </h3>
            </div>
        </div>

        <!-- Estadísticas y acciones rápidas (una fila en pantallas grandes) -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-5 g-3 mb-4 align-items-stretch">
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="totalPracticas" style="font-size:2.5rem;"><?= (int) ($estadisticas['totalPracticas'] ?? 0) ?></h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Total Prácticas</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="practicasActivas" style="font-size:2.5rem;"><?= (int) ($estadisticas['practicasActivas'] ?? 0) ?></h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Prácticas Activas</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nueva Práctica</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generateReport()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportData()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buscador por estudiante (espacio entre acciones y pestañas) -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-3">
                        <label class="form-label small text-muted mb-2">Buscar estudiante</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" class="form-control" id="busquedaEstudiante" placeholder="Nombre o número de cédula..." autocomplete="off">
                            <button class="btn btn-outline-secondary" type="button" id="btnLimpiarBusqueda" title="Limpiar"><i class="fas fa-times"></i></button>
                        </div>
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
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="preprofesionales-tab" data-bs-toggle="tab" data-bs-target="#preprofesionales" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-building me-2" style="color: #0d6efd;"></i>Prácticas Preprofesionales
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="servicio-tab" data-bs-toggle="tab" data-bs-target="#servicio" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-heart me-2" style="color: #198754;"></i>Servicio Comunitario
                                </button>
                            </li>
                        </ul>
        <!-- Pequeña línea decorativa para separar visualmente las pestañas del contenido -->
        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas en formato tabla mejorado -->
                        <div class="tab-content mt-3" id="practicasTabContent">
                            <!-- Prácticas Preprofesionales -->
                            <div class="tab-pane fade show active" id="preprofesionales" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Estudiante</th>
                                                        <th>Institución</th>
                                                        <th>Período</th>
                                                        <th>Horas</th>
                                                        <th>Estado</th>
                                                        <th>Progreso</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaPreprofesionales">
                                                    <?php if (!empty($practicasPreprofesionales)): ?>
                                                        <?php foreach ($practicasPreprofesionales as $index => $practica): ?>
                                                            <tr class="fila-busqueda" data-estudiante-nombre="<?= esc(strtolower($practica['ESTUDIANTE_NOMBRE'] ?? '')) ?>" data-estudiante-cedula="<?= esc(preg_replace('/\s+/', '', $practica['ESTUDIANTE_CEDULA'] ?? '')) ?>">
                                                                <td><?= str_pad($practica['ID_PRACTICA_PREPROFESIONAL'], 3, '0', STR_PAD_LEFT) ?></td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($practica['ESTUDIANTE_NOMBRE']) ?>&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="<?= substr($practica['ESTUDIANTE_NOMBRE'], 0, 2) ?>">
                                                                        <div>
                                                                            <?php $idEst = (int) ($practica['ID_ESTUDIANTE'] ?? 0); ?>
                                                                            <div class="fw-semibold"><?php if ($idEst): ?><a href="<?= base_url('coord/documentos/practicas?estudiante=' . $idEst) ?>" class="text-decoration-none"><?= esc($practica['ESTUDIANTE_NOMBRE']) ?></a><?php else: ?><?= esc($practica['ESTUDIANTE_NOMBRE']) ?><?php endif; ?></div>
                                                                            <small class="text-muted"><?= $practica['CARRERA_NOMBRE'] ?></small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div><?= $practica['INSTITUCION_NOMBRE'] ?></div>
                                                                    <small class="text-muted"><?= $practica['TIPO_INSTITUCION'] ?></small>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $iniP = !empty($practica['FECHA_INICIO']) ? date('M Y', strtotime($practica['FECHA_INICIO'])) : '—';
                                                                    $finP = !empty($practica['FECHA_FIN']) ? date('M Y', strtotime($practica['FECHA_FIN'])) : null;
                                                                    ?>
                                                                    <div><?= $finP !== null ? esc($iniP . ' - ' . $finP) : esc($iniP) ?></div>
                                                                    <small class="text-muted"><?= $practica['HORAS_PRACTICAS'] ?>h</small>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-info"><?= $practica['HORAS_PRACTICAS'] ?>h</span>
                                                                </td>
                                                                <td>
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
                                                                    <span class="badge <?= $estadoClass ?>"><?= $practica['ESTADO_PRACTICA'] ?></span>
                                                                </td>
                                                                <td>
                                                                    <div class="progress" style="height: 8px;">
                                                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                                                    </div>
                                                                    <small class="text-muted">100%</small>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <button class="btn btn-outline-primary" onclick="verDetalle(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>, 'preprofesional')" title="Ver Detalle">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button class="btn btn-outline-warning" onclick="editarPractica(<?= $practica['ID_PRACTICA_PREPROFESIONAL'] ?>)" title="Editar">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                                No hay prácticas preprofesionales registradas
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio Comunitario -->
                            <div class="tab-pane fade" id="servicio" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Estudiante</th>
                                                        <th>Institución</th>
                                                        <th>Período</th>
                                                        <th>Horas</th>
                                                        <th>Estado</th>
                                                        <th>Progreso</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaServicio">
                                                    <?php if (!empty($serviciosComunitarios)): ?>
                                                        <?php foreach ($serviciosComunitarios as $index => $servicio): ?>
                                                            <tr class="fila-busqueda" data-estudiante-nombre="<?= esc(strtolower($servicio['ESTUDIANTE_NOMBRE'] ?? '')) ?>" data-estudiante-cedula="<?= esc(preg_replace('/\s+/', '', $servicio['ESTUDIANTE_CEDULA'] ?? '')) ?>">
                                                                <td><?= str_pad($servicio['ID_SERVICIO_COMUNITARIO'], 3, '0', STR_PAD_LEFT) ?></td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($servicio['ESTUDIANTE_NOMBRE']) ?>&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="<?= substr($servicio['ESTUDIANTE_NOMBRE'], 0, 2) ?>">
                                                                        <div>
                                                                            <?php $idEstSc = (int) ($servicio['ID_ESTUDIANTE'] ?? 0); ?>
                                                                            <div class="fw-semibold"><?php if ($idEstSc): ?><a href="<?= base_url('coord/documentos/servicio?estudiante=' . $idEstSc) ?>" class="text-decoration-none"><?= esc($servicio['ESTUDIANTE_NOMBRE']) ?></a><?php else: ?><?= esc($servicio['ESTUDIANTE_NOMBRE']) ?><?php endif; ?></div>
                                                                            <small class="text-muted"><?= $servicio['CARRERA_NOMBRE'] ?></small>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div><?= $servicio['INSTITUCION_NOMBRE'] ?></div>
                                                                    <small class="text-muted"><?= $servicio['TIPO_INSTITUCION'] ?></small>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    $iniS = !empty($servicio['FECHA_INICIO']) ? date('M Y', strtotime($servicio['FECHA_INICIO'])) : '—';
                                                                    $finS = !empty($servicio['FECHA_FIN']) ? date('M Y', strtotime($servicio['FECHA_FIN'])) : null;
                                                                    ?>
                                                                    <div><?= $finS !== null ? esc($iniS . ' - ' . $finS) : esc($iniS) ?></div>
                                                                    <small class="text-muted"><?= $servicio['HORAS_SERVICIO'] ?>h</small>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-info"><?= $servicio['HORAS_SERVICIO'] ?>h</span>
                                                                </td>
                                                                <td>
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
                                                                    <span class="badge <?= $estadoClass ?>"><?= $servicio['ESTADO_SERVICIO'] ?></span>
                                                                </td>
                                                                <td>
                                                                    <div class="progress" style="height: 8px;">
                                                                        <div class="progress-bar bg-info" style="width: 47%"></div>
                                                                    </div>
                                                                    <small class="text-muted">47%</small>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group btn-group-sm">
                                                                        <button class="btn btn-outline-primary" onclick="verDetalle(<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>, 'servicio')" title="Ver Detalle">
                                                                            <i class="fas fa-eye"></i>
                                                                        </button>
                                                                        <button class="btn btn-outline-warning" onclick="editarPractica(<?= $servicio['ID_SERVICIO_COMUNITARIO'] ?>)" title="Editar">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                                No hay servicios comunitarios registrados
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

<!-- Modal Nueva Práctica -->
<div class="modal fade" id="modalNuevaPractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nueva Asignación de Práctica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaPractica" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Práctica <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_practica" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Prácticas de Servicio Comunitario</option>
                                    <option value="2">Prácticas Preprofesionales</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor selecciona un tipo de práctica.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estudiante <span class="text-danger">*</span></label>
                                <input type="hidden" name="estudiante" id="estudiante_id" value="">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="buscar_estudiante_nombre" placeholder="Buscar por nombre..." autocomplete="off" onkeydown="if(event.key==='Enter'){event.preventDefault();buscarEstudiantesModal();}">
                                    <button type="button" class="btn btn-outline-primary" onclick="buscarEstudiantesModal()" title="Buscar">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div id="estudiante_seleccionado_display" class="mt-2 small text-success fw-semibold" style="display: none;"></div>
                                <div id="resultados_busqueda_estudiantes" class="list-group mt-2 shadow-sm" style="max-height: 220px; overflow-y: auto; display: none;"></div>
                                <div class="invalid-feedback" id="error_estudiante">Seleccione un estudiante: busque por nombre y elija de la lista.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Institución <span class="text-danger">*</span></label>
                                <select class="form-select" name="institucion" required>
                                    <option value="">Seleccionar institución...</option>
                                    <option value="1">Hospital San Vicente de Paúl</option>
                                    <option value="2">Banco del Pacífico</option>
                                    <option value="3">Fundación Niños del Ecuador</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor selecciona una institución.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Tutor / docente <span class="text-danger">*</span></label>
                                <select class="form-select" name="instructor" required>
                                    <option value="">Seleccionar tutor o docente...</option>
                                </select>
                                <div class="form-text">Instructor que acompañará al estudiante en esta práctica.</div>
                                <div class="invalid-feedback">
                                    Por favor selecciona un tutor o docente.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                                <div class="invalid-feedback">
                                    Por favor selecciona una fecha de inicio.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin <span class="text-muted">(opcional)</span></label>
                                <input type="date" class="form-control" name="fecha_fin">
                                <div class="form-text">Si la dejas vacía, se registrará solo la fecha de inicio.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Horas Totales <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="horas_total" id="horas_total_nueva" min="1" max="1000" value="" readonly required>
                                <div class="form-text">Prácticas preprofesionales: 240 h (una sola vez en la carrera). Servicio comunitario: 60 h (una sola vez por estudiante).</div>
                                <div class="invalid-feedback">
                                    Seleccione el tipo de práctica para asignar las horas correspondientes.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="descripcion" rows="4" placeholder="Describe las actividades a realizar..." minlength="20" maxlength="500" required></textarea>
                        <div class="invalid-feedback">
                            Por favor ingresa una descripción detallada (mínimo 20 caracteres).
                        </div>
                        <div class="form-text">
                            <span id="descripcion-count">0</span>/500 caracteres
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarPractica()">
                    <i class="fas fa-save me-1"></i>Guardar Práctica
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Práctica -->
<div class="modal fade" id="modalDetallePractica" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle de Práctica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                                        <p><strong>Estudiante:</strong> <span id="detalleEstudiante">-</span></p>
                                        <p><strong>Carrera:</strong> <span id="detalleCarrera">-</span></p>
                                        <p><strong>Tipo de Práctica:</strong> <span id="detalleTipo">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Institución:</strong> <span id="detalleInstitucion">-</span></p>
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
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
                                <h6 class="mb-0">Registro de Asistencias</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Entrada</th>
                                                <th>Salida</th>
                                                <th>Horas</th>
                                                <th>Actividades</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaAsistencias">
                                            <tr>
                                                <td>30/08/2025</td>
                                                <td>08:00</td>
                                                <td>17:00</td>
                                                <td>8h</td>
                                                <td>Desarrollo de módulo de usuarios</td>
                                            </tr>
                                            <tr>
                                                <td>29/08/2025</td>
                                                <td>08:00</td>
                                                <td>17:00</td>
                                                <td>8h</td>
                                                <td>Análisis de requerimientos</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                <div class="progress-circle mb-3">
                                    <canvas id="progressChart" width="150" height="150"></canvas>
                                </div>
                                <h4 id="progressPercent">75%</h4>
                                <p class="text-muted" id="progressHours">180 de 240 horas</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Documentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>Carta de Presentación
                                    </button>
                                    <button class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-file-word me-1"></i>Plan de Trabajo
                                    </button>
                                    <button class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-file-excel me-1"></i>Registro de Actividades
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-file-alt me-1"></i>Informe Final
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
                    <i class="fas fa-edit me-1"></i>Editar Práctica
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-clock me-2"></i>
                    Registrar Asistencia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAsistencia" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="fecha_asistencia" required>
                        <div class="invalid-feedback">
                            Por favor selecciona una fecha válida.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Entrada <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_entrada" required>
                                <div class="invalid-feedback">
                                    Por favor selecciona una hora de entrada.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Salida <span class="text-danger">*</span></label>
                                <input type="time" class="form-control" name="hora_salida" required>
                                <div class="invalid-feedback">
                                    Por favor selecciona una hora de salida.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Actividades del Día <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="actividades_dia" rows="4" placeholder="Describe las actividades realizadas durante el día..." minlength="10" maxlength="300" required></textarea>
                        <div class="invalid-feedback">
                            Por favor describe las actividades realizadas (mínimo 10 caracteres).
                        </div>
                        <div class="form-text">
                            <span id="actividades-count">0</span>/300 caracteres
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..." maxlength="200"></textarea>
                        <div class="form-text">
                            <span id="observaciones-count">0</span>/200 caracteres
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="guardarAsistencia()">
                    <i class="fas fa-save me-1"></i>Registrar Asistencia
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filtros de Búsqueda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFiltros">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Práctica</label>
                        <select class="form-select" name="filtro_tipo">
                            <option value="">Todos los tipos</option>
                            <option value="1">Servicio Comunitario</option>
                            <option value="2">Preprofesionales</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="1">Pendiente</option>
                            <option value="2">En Proceso</option>
                            <option value="3">Completada</option>
                            <option value="4">Cancelada</option>
                        </select>
                    </div>
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
                    <div class="mb-3">
                        <label class="form-label">Carrera</label>
                        <select class="form-select" name="filtro_carrera">
                            <option value="">Todas las carreras</option>
                            <?php if (!empty($carreras)): ?>
                                <?php foreach ($carreras as $c): ?>
                                    <option value="<?= (int) $c['ID_CARRERA'] ?>"><?= esc($c['NOMBRE']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    const baseUrlPracticas = '<?= base_url('coord/practicas/') ?>';
    let datosModal = null;

    // Buscar estudiante por nombre o cédula (filtra la tabla del tab visible)
    function aplicarBusquedaEstudiante() {
        const term = (document.getElementById('busquedaEstudiante').value || '').trim().toLowerCase().replace(/\s+/g, '');
        const panes = document.querySelectorAll('#practicasTabContent .tab-pane');
        let visiblesPorPane = {};
        panes.forEach(function(pane) {
            const rows = pane.querySelectorAll('tbody tr.fila-busqueda');
            let visibles = 0;
            rows.forEach(function(row) {
                const nombre = (row.getAttribute('data-estudiante-nombre') || '').replace(/\s+/g, '');
                const cedula = (row.getAttribute('data-estudiante-cedula') || '').replace(/\s+/g, '');
                const coincide = !term || nombre.indexOf(term) !== -1 || cedula.indexOf(term) !== -1;
                row.style.display = coincide ? '' : 'none';
                if (coincide) visibles++;
            });
            visiblesPorPane[pane.id] = visibles;
        });
        // Si hay búsqueda y el tab activo no tiene resultados, pasar al tab que sí tenga (Preprofesional primero, luego Servicio)
        if (term) {
            const paneActivo = document.querySelector('#practicasTabContent .tab-pane.active');
            if (paneActivo && visiblesPorPane[paneActivo.id] === 0) {
                if (visiblesPorPane['preprofesionales'] > 0) {
                    document.querySelector('[data-bs-target="#preprofesionales"]').click();
                } else if (visiblesPorPane['servicio'] > 0) {
                    document.querySelector('[data-bs-target="#servicio"]').click();
                }
            }
        }
    }

    // Funciones principales
    function showModal(modalId) {
        if (modalId === 'modalNuevaPractica') {
            cargarDatosModal();
        }
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cargarDatosModal() {
        if (datosModal) {
            poblarModal();
            return;
        }

        console.log('Cargando datos del modal...');
        fetch(baseUrlPracticas + 'getDatosModal')
            .then(response => {
                const contentType = response.headers.get('Content-Type') || '';
                if (!contentType.includes('application/json')) {
                    return response.text().then(text => {
                        throw new Error('El servidor devolvió una respuesta no válida (no JSON). Comprueba que la URL base de la aplicación sea correcta.');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    datosModal = data.data;
                    poblarModal();
                } else {
                    console.error('Error en respuesta:', data);
                    showNotification('Error al cargar datos del modal: ' + (data.message || 'Error desconocido'), 'error');
                }
            })
            .catch(error => {
                console.error('Error de conexión:', error);
                showNotification('Error de conexión: ' + error.message, 'error');
            });
    }

    function poblarModal() {
        console.log('Poblando modal con datos:', datosModal);
        limpiarBusquedaEstudiante();

        // Poblar instituciones
        const selectInstituciones = document.querySelector('select[name="institucion"]');
        selectInstituciones.innerHTML = '<option value="">Seleccionar institución...</option>';
        
        if (datosModal.instituciones && datosModal.instituciones.length > 0) {
            console.log('Instituciones encontradas:', datosModal.instituciones);
            datosModal.instituciones.forEach(institucion => {
                const option = document.createElement('option');
                option.value = institucion.ID_INSTITUCION_CONVENIO;
                option.textContent = institucion.NOMBRE;
                selectInstituciones.appendChild(option);
            });
        } else {
            console.log('No se encontraron instituciones');
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No hay instituciones disponibles';
            selectInstituciones.appendChild(option);
        }

        // Poblar tipos de prácticas
        const selectTipos = document.querySelector('select[name="tipo_practica"]');
        selectTipos.innerHTML = '<option value="">Seleccionar...</option>';
        
        if (datosModal.tiposPracticas && datosModal.tiposPracticas.length > 0) {
            console.log('Tipos de prácticas encontrados:', datosModal.tiposPracticas);
            datosModal.tiposPracticas.forEach(tipo => {
                const option = document.createElement('option');
                option.value = tipo.ID_TIPO_PRACTICA;
                option.textContent = tipo.PRACTICA;
                selectTipos.appendChild(option);
            });
        } else {
            console.log('No se encontraron tipos de prácticas');
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No hay tipos disponibles';
            selectTipos.appendChild(option);
        }

        // Poblar docentes / tutores
        const selectInstructor = document.querySelector('select[name="instructor"]');
        if (selectInstructor) {
            selectInstructor.innerHTML = '<option value="">Seleccionar docente tutor...</option>';
            if (datosModal.instructores && datosModal.instructores.length > 0) {
                datosModal.instructores.forEach(function(ins) {
                    const opt = document.createElement('option');
                    opt.value = ins.ID_DOCENTE_TUTOR;
                    opt.textContent = ins.NOMBRE_COMPLETO || ('Docente ' + ins.ID_DOCENTE_TUTOR);
                    selectInstructor.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'No hay docentes registrados';
                selectInstructor.appendChild(opt);
            }
        }

        configurarHorasPorTipoPractica();
    }

    function limpiarBusquedaEstudiante() {
        document.getElementById('estudiante_id').value = '';
        document.getElementById('buscar_estudiante_nombre').value = '';
        const disp = document.getElementById('estudiante_seleccionado_display');
        disp.textContent = '';
        disp.style.display = 'none';
        document.getElementById('resultados_busqueda_estudiantes').innerHTML = '';
        document.getElementById('resultados_busqueda_estudiantes').style.display = 'none';
        document.getElementById('estudiante_id').classList.remove('is-invalid');
        restaurarInstitucionesCompletas();
    }

    function restaurarInstitucionesCompletas() {
        const selectInst = document.querySelector('select[name="institucion"]');
        if (!selectInst || !datosModal || !datosModal.instituciones) return;
        selectInst.innerHTML = '<option value="">Seleccionar institución...</option>';
        datosModal.instituciones.forEach(function(inst) {
            const opt = document.createElement('option');
            opt.value = inst.ID_INSTITUCION_CONVENIO;
            opt.textContent = inst.NOMBRE;
            selectInst.appendChild(opt);
        });
    }

    function actualizarInstitucionesPorCarrera(carreraId) {
        const selectInst = document.querySelector('select[name="institucion"]');
        if (!selectInst) return;
        selectInst.innerHTML = '<option value="">Cargando...</option>';
        selectInst.value = '';
        if (!carreraId) {
            restaurarInstitucionesCompletas();
            return;
        }
        fetch(baseUrlPracticas + 'institucionesPorCarrera?carrera_id=' + encodeURIComponent(carreraId))
            .then(function(r) { return r.json(); })
            .then(function(res) {
                selectInst.innerHTML = '<option value="">Seleccionar institución...</option>';
                if (res.success && res.data && res.data.length > 0) {
                    res.data.forEach(function(inst) {
                        const opt = document.createElement('option');
                        opt.value = inst.ID_INSTITUCION_CONVENIO;
                        opt.textContent = inst.NOMBRE;
                        selectInst.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'No hay instituciones con convenio vigente para esta carrera';
                    selectInst.appendChild(opt);
                }
            })
            .catch(function() {
                selectInst.innerHTML = '<option value="">Error al cargar. Intente de nuevo.</option>';
            });
    }

    function buscarEstudiantesModal() {
        const q = document.getElementById('buscar_estudiante_nombre').value.trim();
        const cont = document.getElementById('resultados_busqueda_estudiantes');
        cont.innerHTML = '';
        cont.style.display = 'none';
        if (!q) {
            showNotification('Escriba al menos un carácter para buscar', 'info');
            return;
        }
        cont.innerHTML = '<div class="list-group-item text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Buscando...</div>';
        cont.style.display = 'block';
        fetch(baseUrlPracticas + 'buscarEstudiantes?q=' + encodeURIComponent(q))
            .then(function(r) { return r.json(); })
            .then(function(res) {
                cont.innerHTML = '';
                if (res.success && res.data && res.data.length > 0) {
                    res.data.forEach(function(est) {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action text-start';
                        item.textContent = est.NOMBRE_COMPLETO + ' - ' + (est.CARRERA || '');
                        item.dataset.id = est.ID_ESTUDIANTE;
                        item.dataset.nombre = est.NOMBRE_COMPLETO + ' - ' + (est.CARRERA || '');
                        item.dataset.carreraId = est.ID_CARRERA || '';
                        item.addEventListener('click', function() {
                            document.getElementById('estudiante_id').value = this.dataset.id;
                            document.getElementById('estudiante_seleccionado_display').textContent = '✓ ' + this.dataset.nombre;
                            document.getElementById('estudiante_seleccionado_display').style.display = 'block';
                            document.getElementById('buscar_estudiante_nombre').value = '';
                            cont.innerHTML = '';
                            cont.style.display = 'none';
                            document.getElementById('estudiante_id').classList.remove('is-invalid');
                            actualizarInstitucionesPorCarrera(this.dataset.carreraId);
                        });
                        cont.appendChild(item);
                    });
                    cont.style.display = 'block';
                } else {
                    cont.innerHTML = '<div class="list-group-item text-muted">No se encontraron estudiantes. Pruebe con otro nombre.</div>';
                    cont.style.display = 'block';
                }
            })
            .catch(function() {
                cont.innerHTML = '<div class="list-group-item text-danger">Error de conexión.</div>';
                cont.style.display = 'block';
            });
    }

    function configurarHorasPorTipoPractica() {
        const HORAS_PREPROFESIONALES = 240;
        const HORAS_SERVICIO = 60;
        function setHorasPorTipo(tipoVal, inputHoras) {
            if (!inputHoras) return;
            const v = parseInt(tipoVal, 10);
            if (v === 2) inputHoras.value = HORAS_PREPROFESIONALES;
            else if (v === 1) inputHoras.value = HORAS_SERVICIO;
            else inputHoras.value = '';
        }
        const selectTipo = document.querySelector('select[name="tipo_practica"]');
        const inputHorasNueva = document.getElementById('horas_total_nueva');
        if (selectTipo && inputHorasNueva) {
            selectTipo.removeEventListener('change', selectTipo._horasHandler);
            selectTipo._horasHandler = function() { setHorasPorTipo(selectTipo.value, inputHorasNueva); };
            selectTipo.addEventListener('change', selectTipo._horasHandler);
            setHorasPorTipo(selectTipo.value, inputHorasNueva);
        }
    }

    function verDetalle(id, tipo) {
        fetch(`${baseUrlPracticas}detalle/${id}/${tipo}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const practica = data.data.practica;
                    document.getElementById('detalleEstudiante').textContent = practica.ESTUDIANTE_NOMBRE;
                    document.getElementById('detalleCarrera').textContent = practica.CARRERA_NOMBRE;
                    document.getElementById('detalleTipo').textContent = tipo === 'preprofesional' ? 'Preprofesional' : 'Servicio Comunitario';
                    document.getElementById('detalleInstitucion').textContent = practica.INSTITUCION_NOMBRE;
                    const fin = practica.FECHA_FIN && String(practica.FECHA_FIN).trim() ? practica.FECHA_FIN : '';
                    document.getElementById('detallePeriodo').textContent = fin ? `${practica.FECHA_INICIO} - ${fin}` : (practica.FECHA_INICIO || '—');
                    document.getElementById('detalleEstado').textContent = practica.ESTADO_PRACTICA || practica.ESTADO_SERVICIO;
                    document.getElementById('detalleDescripcion').textContent = practica.PROYECTO_ESPECIFICO || practica.PROYECTO_SOCIAL || 'Sin descripción';
                    document.getElementById('progressPercent').textContent = `${data.data.progreso}%`;
                    document.getElementById('progressHours').textContent = `${data.data.horasCumplidas} de ${practica.HORAS_PRACTICAS || practica.HORAS_SERVICIO} horas`;
                    
                    drawProgressChart(data.data.progreso);
                    showModal('modalDetallePractica');
                } else {
                    showNotification('Error al cargar detalle de la práctica', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión', 'error');
            });
    }

    function editarPractica(id) {
        showNotification('Función de edición en desarrollo', 'info');
    }

    function registrarAsistencia(id, tipo) {
        // Guardar el ID y tipo de práctica para usar en el formulario
        document.getElementById('formAsistencia').setAttribute('data-practica-id', id);
        document.getElementById('formAsistencia').setAttribute('data-tipo-practica', tipo);
        showModal('modalAsistencia');
    }

    function descargarReporte(id) {
        showNotification('Descargando reporte...', 'success');
    }

    function guardarPractica() {
        const form = document.getElementById('formNuevaPractica');
        
        // Validar formulario antes de enviar
        if (!validarFormularioPractica(form)) {
            showNotification('Por favor completa todos los campos obligatorios correctamente', 'error');
            return;
        }

        // Validar fechas
        if (!validarFechasPractica(form)) {
            showNotification('La fecha de fin debe ser posterior a la fecha de inicio', 'error');
            return;
        }

        const formData = new FormData(form);

        fetch(baseUrlPracticas + 'crear', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Práctica guardada exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalNuevaPractica')).hide();
                form.reset();
                limpiarBusquedaEstudiante();
                // Recargar la página para mostrar los nuevos datos
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'Error al guardar la práctica', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        });
    }

    function guardarAsistencia() {
        const form = document.getElementById('formAsistencia');
        
        // Validar formulario antes de enviar
        if (!validarFormularioAsistencia(form)) {
            showNotification('Por favor completa todos los campos obligatorios correctamente', 'error');
            return;
        }

        // Validar horarios
        if (!validarHorariosAsistencia(form)) {
            showNotification('La hora de salida debe ser posterior a la hora de entrada', 'error');
            return;
        }

        const formData = new FormData(form);
        
        // Agregar datos adicionales
        formData.append('practica_id', form.getAttribute('data-practica-id'));
        formData.append('tipo_practica', form.getAttribute('data-tipo-practica'));

        fetch(baseUrlPracticas + 'registrar-asistencia', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Asistencia registrada exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalAsistencia')).hide();
                form.reset();
            } else {
                showNotification(data.message || 'Error al registrar la asistencia', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error de conexión', 'error');
        });
    }

    function aplicarFiltros() {
        showNotification('Filtros aplicados', 'info');
        bootstrap.Modal.getInstance(document.getElementById('modalFiltros')).hide();
    }

    function limpiarFiltros() {
        document.getElementById('formFiltros').reset();
        showNotification('Filtros limpiados', 'info');
    }

    function generateReport() {
        // Redirigir a la vista de reportes (usar base_url por si la app está en subcarpeta)
        window.location.href = '<?= base_url('coord/practicas/reportes') ?>';
    }

    function exportData() {
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

        // Agregar el modal al DOM
        document.body.appendChild(modal);

        // Mostrar el modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        // Limpiar el modal del DOM cuando se cierre
        modal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modal);
        });
    }

    function exportarFormato(formato) {
        // Cerrar el modal primero
        const modal = document.getElementById('modalOpcionesExportacion');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
        }

        // Mostrar notificación de procesamiento
        showNotification(`Generando archivo ${formato.toUpperCase()}...`, 'info');

        // Realizar la exportación
        fetch(`${baseUrlPracticas}exportar-datos/${formato}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                
                // Obtener el nombre del archivo del header Content-Disposition
                const contentDisposition = response.headers.get('Content-Disposition');
                let filename = `practicas_${new Date().toISOString().split('T')[0]}.${formato}`;
                
                if (contentDisposition) {
                    const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                    if (filenameMatch) {
                        filename = filenameMatch[1];
                    }
                }

                return response.blob().then(blob => ({ blob, filename }));
            })
            .then(({ blob, filename }) => {
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

    function drawProgressChart(percentage) {
        const canvas = document.getElementById('progressChart');
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 60;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Background circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 10;
        ctx.stroke();

        // Progress circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
        ctx.strokeStyle = '#667eea';
        ctx.lineWidth = 10;
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set default date for new practice
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="fecha_inicio"]').value = today;

        // Initialize progress chart (canvas en modal de detalle)
        drawProgressChart(75);
    });

    // Tab change handler
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function() {
            aplicarBusquedaEstudiante();
        });
    });

    // Buscador por estudiante (nombre o cédula)
    const busquedaEstudianteEl = document.getElementById('busquedaEstudiante');
    const btnLimpiarBusqueda = document.getElementById('btnLimpiarBusqueda');
    if (busquedaEstudianteEl) {
        busquedaEstudianteEl.addEventListener('input', aplicarBusquedaEstudiante);
        busquedaEstudianteEl.addEventListener('keyup', function(e) {
            if (e.key === 'Escape') {
                busquedaEstudianteEl.value = '';
                aplicarBusquedaEstudiante();
            }
        });
    }
    if (btnLimpiarBusqueda) {
        btnLimpiarBusqueda.addEventListener('click', function() {
            if (busquedaEstudianteEl) {
                busquedaEstudianteEl.value = '';
                aplicarBusquedaEstudiante();
                busquedaEstudianteEl.focus();
            }
        });
    }

    // Auto-calculate hours when time changes
    document.addEventListener('change', function(e) {
        if (e.target.name === 'hora_entrada' || e.target.name === 'hora_salida') {
            const entrada = document.querySelector('input[name="hora_entrada"]').value;
            const salida = document.querySelector('input[name="hora_salida"]').value;
            
            if (entrada && salida) {
                const [horaEntrada, minutoEntrada] = entrada.split(':').map(Number);
                const [horaSalida, minutoSalida] = salida.split(':').map(Number);
                
                const entradaMinutos = horaEntrada * 60 + minutoEntrada;
                const salidaMinutos = horaSalida * 60 + minutoSalida;
                
                if (salidaMinutos > entradaMinutos) {
                    const totalMinutos = salidaMinutos - entradaMinutos;
                    const horas = Math.floor(totalMinutos / 60);
                    const minutos = totalMinutos % 60;
                    
                    console.log(`Horas trabajadas: ${horas}h ${minutos}m`);
                }
            }
        }
    });

    // Funciones de validación
    function validarFormularioPractica(form) {
        let esValido = true;
        
        // Limpiar validaciones previas
        form.classList.remove('was-validated');
        const campos = form.querySelectorAll('input, select, textarea');
        campos.forEach(campo => {
            campo.classList.remove('is-invalid', 'is-valid');
        });

        // Validar cada campo requerido
        const camposRequeridos = form.querySelectorAll('[required]');
        camposRequeridos.forEach(campo => {
            if (!campo.value.trim()) {
                campo.classList.add('is-invalid');
                esValido = false;
            } else {
                campo.classList.add('is-valid');
            }
        });

        // Validar longitud mínima de descripción
        const descripcion = form.querySelector('textarea[name="descripcion"]');
        if (descripcion.value.length < 20) {
            descripcion.classList.add('is-invalid');
            esValido = false;
        }

        // Validar rango de horas
        const horas = form.querySelector('input[name="horas_total"]');
        if (horas.value < 1 || horas.value > 1000) {
            horas.classList.add('is-invalid');
            esValido = false;
        }

        // Validar estudiante (búsqueda por nombre: debe estar seleccionado)
        const estudianteId = document.getElementById('estudiante_id');
        if (!estudianteId || !estudianteId.value.trim()) {
            if (estudianteId) estudianteId.classList.add('is-invalid');
            esValido = false;
        } else if (estudianteId) {
            estudianteId.classList.add('is-valid');
        }

        form.classList.add('was-validated');
        return esValido;
    }

    function validarFechasPractica(form) {
        const inicioVal = form.querySelector('input[name="fecha_inicio"]').value;
        const finVal = form.querySelector('input[name="fecha_fin"]').value;
        if (!finVal || !finVal.trim()) {
            return true;
        }
        const fechaInicio = new Date(inicioVal);
        const fechaFin = new Date(finVal);
        if (isNaN(fechaInicio.getTime()) || isNaN(fechaFin.getTime())) {
            return false;
        }
        return fechaFin > fechaInicio;
    }

    function validarFormularioAsistencia(form) {
        let esValido = true;
        
        // Limpiar validaciones previas
        form.classList.remove('was-validated');
        const campos = form.querySelectorAll('input, select, textarea');
        campos.forEach(campo => {
            campo.classList.remove('is-invalid', 'is-valid');
        });

        // Validar cada campo requerido
        const camposRequeridos = form.querySelectorAll('[required]');
        camposRequeridos.forEach(campo => {
            if (!campo.value.trim()) {
                campo.classList.add('is-invalid');
                esValido = false;
            } else {
                campo.classList.add('is-valid');
            }
        });

        // Validar longitud mínima de actividades
        const actividades = form.querySelector('textarea[name="actividades_dia"]');
        if (actividades.value.length < 10) {
            actividades.classList.add('is-invalid');
            esValido = false;
        }

        form.classList.add('was-validated');
        return esValido;
    }

    function validarHorariosAsistencia(form) {
        const horaEntrada = form.querySelector('input[name="hora_entrada"]').value;
        const horaSalida = form.querySelector('input[name="hora_salida"]').value;
        
        if (!horaEntrada || !horaSalida) return false;
        
        const [horaE, minutoE] = horaEntrada.split(':').map(Number);
        const [horaS, minutoS] = horaSalida.split(':').map(Number);
        
        const entradaMinutos = horaE * 60 + minutoE;
        const salidaMinutos = horaS * 60 + minutoS;
        
        return salidaMinutos > entradaMinutos;
    }

    // Contadores de caracteres
    document.addEventListener('input', function(e) {
        if (e.target.name === 'descripcion') {
            const count = e.target.value.length;
            document.getElementById('descripcion-count').textContent = count;
            
            if (count > 500) {
                e.target.classList.add('is-invalid');
            } else if (count >= 20) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            }
        }
        
        if (e.target.name === 'actividades_dia') {
            const count = e.target.value.length;
            document.getElementById('actividades-count').textContent = count;
            
            if (count > 300) {
                e.target.classList.add('is-invalid');
            } else if (count >= 10) {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            }
        }
        
        if (e.target.name === 'observaciones') {
            const count = e.target.value.length;
            document.getElementById('observaciones-count').textContent = count;
        }
    });
</script>
<?= $this->endSection() ?>
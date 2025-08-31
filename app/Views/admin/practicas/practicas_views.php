<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/practicas.css') ?>" />
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

        <!-- Estadísticas Rápidas en Cuadros -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalPracticas" style="font-size:2.5rem;">24</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Prácticas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="practicasActivas" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Prácticas Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #0c5460 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="practicasFinalizadas" style="font-size:2.5rem;">8</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Finalizadas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="practicasPendientes" style="font-size:2.5rem;">4</h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas en Tarjetas Separadas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div class="fw-bold">Nueva Práctica</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalAsignarEstudiante')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <div class="fw-bold">Asignar Estudiante</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generateReport()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportData()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2"></i>
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
                                <button class="nav-link rounded-pill fw-semibold text-info" id="seguimiento-tab" data-bs-toggle="tab" data-bs-target="#seguimiento" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Seguimiento
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
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-building me-2"></i>
                                            Prácticas Preprofesionales
                                        </span>
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
                                                    <tr>
                                                        <td>001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Yamilex+Campues&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="YC">
                                                                <div>
                                                                    <div class="fw-semibold">Yamilex Campues</div>
                                                                    <small class="text-muted">Sistemas de Información</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Hospital San Vicente de Paúl</div>
                                                            <small class="text-muted">Sector Público</small>
                                                        </td>
                                                        <td>
                                                            <div>Jun 2025 - Ago 2025</div>
                                                            <small class="text-muted">3 meses</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">240/240h</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success text-white">Completada</span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-success" style="width: 100%"></div>
                                                            </div>
                                                            <small class="text-muted">100%</small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(1)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarPractica(1)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success" onclick="descargarReporte(1)" title="Reporte">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>002</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Ana+Yandun&background=6c757d&color=fff&size=32" class="rounded-circle me-2" alt="AY">
                                                                <div>
                                                                    <div class="fw-semibold">Ana Yandun</div>
                                                                    <small class="text-muted">Desarrollo de Software</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Banco del Pacífico</div>
                                                            <small class="text-muted">Sector Privado</small>
                                                        </td>
                                                        <td>
                                                            <div>Jul 2025 - Sep 2025</div>
                                                            <small class="text-muted">3 meses</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning text-dark">180/240h</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning text-dark">En Proceso</span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-warning" style="width: 75%"></div>
                                                            </div>
                                                            <small class="text-muted">75%</small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(2)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarPractica(2)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="registrarAsistencia(2)" title="Asistencia">
                                                                    <i class="fas fa-clock"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Servicio Comunitario -->
                            <div class="tab-pane fade" id="servicio" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-heart me-2"></i>
                                            Prácticas de Servicio Comunitario
                                        </span>
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
                                                        <th>Estudiante</th>
                                                        <th>Comunidad/Organización</th>
                                                        <th>Período</th>
                                                        <th>Horas</th>
                                                        <th>Estado</th>
                                                        <th>Progreso</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaServicio">
                                                    <tr>
                                                        <td>SC001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Pedro+Aguirre&background=198754&color=fff&size=32" class="rounded-circle me-2" alt="PA">
                                                                <div>
                                                                    <div class="fw-semibold">Pedro Aguirre</div>
                                                                    <small class="text-muted">Desarrollo de Software</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Fundación Niños del Ecuador</div>
                                                            <small class="text-muted">ONG</small>
                                                        </td>
                                                        <td>
                                                            <div>Ago 2025 - Oct 2025</div>
                                                            <small class="text-muted">3 meses</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info text-dark">45/96h</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info text-dark">Iniciado</span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-info" style="width: 47%"></div>
                                                            </div>
                                                            <small class="text-muted">47%</small>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(3)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarPractica(3)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="registrarAsistencia(3)" title="Asistencia">
                                                                    <i class="fas fa-clock"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Seguimiento -->
                            <div class="tab-pane fade" id="seguimiento" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-info text-white">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Seguimiento de Prácticas
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row g-0">
                                            <div class="col-md-8 border-end">
                                                <div class="table-responsive">
                                                    <table class="table table-striped align-middle mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Estudiante</th>
                                                                <th>Tipo</th>
                                                                <th>Horas Cumplidas</th>
                                                                <th>Última Actividad</th>
                                                                <th>Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="https://ui-avatars.com/api/?name=Yamilex+Campues&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="YC">
                                                                        Yamilex Campues
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge bg-primary">Preprofesional</span></td>
                                                                <td>240/240h</td>
                                                                <td>Hace 2 días</td>
                                                                <td><span class="badge bg-success text-white">Completada</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="https://ui-avatars.com/api/?name=Ana+Yandun&background=6c757d&color=fff&size=32" class="rounded-circle me-2" alt="AY">
                                                                        Ana Yandun
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge bg-primary">Preprofesional</span></td>
                                                                <td>180/240h</td>
                                                                <td>Hoy</td>
                                                                <td><span class="badge bg-warning text-dark">En Proceso</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <img src="https://ui-avatars.com/api/?name=Pedro+Aguirre&background=198754&color=fff&size=32" class="rounded-circle me-2" alt="PA">
                                                                        Pedro Aguirre
                                                                    </div>
                                                                </td>
                                                                <td><span class="badge bg-success">Servicio Com.</span></td>
                                                                <td>45/96h</td>
                                                                <td>Hace 1 día</td>
                                                                <td><span class="badge bg-info text-dark">Iniciado</span></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-4 p-3">
                                                <h6 class="mb-3">
                                                    <i class="fas fa-calendar-alt me-2"></i>
                                                    Actividades Recientes
                                                </h6>
                                                <ul class="list-group list-group-flush">
                                                    <li class="list-group-item d-flex align-items-start">
                                                        <span class="badge rounded-pill bg-success me-3 mt-1" style="width: 12px; height: 12px;">&nbsp;</span>
                                                        <div>
                                                            <div class="fw-semibold">Práctica Completada</div>
                                                            <div class="text-muted small">Yamilex Campues finalizó su práctica preprofesional</div>
                                                            <small class="text-muted">Hace 2 días</small>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-start">
                                                        <span class="badge rounded-pill bg-info me-3 mt-1" style="width: 12px; height: 12px;">&nbsp;</span>
                                                        <div>
                                                            <div class="fw-semibold">Nuevo Seguimiento</div>
                                                            <div class="text-muted small">Ana Yandun registró 8 horas de práctica</div>
                                                            <small class="text-muted">Hoy</small>
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item d-flex align-items-start">
                                                        <span class="badge rounded-pill bg-warning me-3 mt-1" style="width: 12px; height: 12px;">&nbsp;</span>
                                                        <div>
                                                            <div class="fw-semibold">Documento Pendiente</div>
                                                            <div class="text-muted small">Pedro Aguirre debe subir informe semanal</div>
                                                            <small class="text-muted">Hace 1 día</small>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
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
                <form id="formNuevaPractica">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Práctica</label>
                                <select class="form-select" name="tipo_practica" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Prácticas de Servicio Comunitario</option>
                                    <option value="2">Prácticas Preprofesionales</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estudiante</label>
                                <select class="form-select" name="estudiante" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <option value="1">Yamilex Campues - Sistemas</option>
                                    <option value="2">Ana Yandun - Desarrollo</option>
                                    <option value="3">Pedro Aguirre - Desarrollo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Institución</label>
                                <select class="form-select" name="institucion" required>
                                    <option value="">Seleccionar institución...</option>
                                    <option value="1">Hospital San Vicente de Paúl</option>
                                    <option value="2">Banco del Pacífico</option>
                                    <option value="3">Fundación Niños del Ecuador</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select" name="estado" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="1">Pendiente</option>
                                    <option value="2">En Proceso</option>
                                    <option value="3">Completada</option>
                                    <option value="4">Cancelada</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Horas Totales</label>
                                <input type="number" class="form-control" name="horas_total" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cronograma</label>
                                <input type="text" class="form-control" name="cronograma" placeholder="Ej: Lunes a Viernes 8:00-17:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="4" placeholder="Describe las actividades a realizar..." required></textarea>
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
                <form id="formAsistencia">
                    <div class="mb-3">
                        <label class="form-label">Fecha</label>
                        <input type="date" class="form-control" name="fecha_asistencia" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Entrada</label>
                                <input type="time" class="form-control" name="hora_entrada" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Hora de Salida</label>
                                <input type="time" class="form-control" name="hora_salida" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Actividades del Día</label>
                        <textarea class="form-control" name="actividades_dia" rows="4" placeholder="Describe las actividades realizadas durante el día..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
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
                            <option value="1">Sistemas de Información</option>
                            <option value="2">Desarrollo de Software</option>
                            <option value="3">Redes y Telecomunicaciones</option>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Datos simulados
    let practicasData = {
        preprofesionales: [
            {
                id: 1,
                estudiante: 'Yamilex Campues',
                carrera: 'Sistemas de Información',
                institucion: 'Hospital San Vicente de Paúl',
                sector: 'Público',
                fechaInicio: '2025-06-01',
                fechaFin: '2025-08-30',
                horasTotal: 240,
                horasCumplidas: 240,
                estado: 'Completada',
                progreso: 100,
                descripcion: 'Desarrollo e implementación de sistema de gestión hospitalaria'
            },
            {
                id: 2,
                estudiante: 'Ana Yandun',
                carrera: 'Desarrollo de Software',
                institucion: 'Banco del Pacífico',
                sector: 'Privado',
                fechaInicio: '2025-07-01',
                fechaFin: '2025-09-30',
                horasTotal: 240,
                horasCumplidas: 180,
                estado: 'En Proceso',
                progreso: 75,
                descripcion: 'Desarrollo de aplicaciones móviles para servicios bancarios'
            }
        ],
        servicio: [
            {
                id: 3,
                estudiante: 'Pedro Aguirre',
                carrera: 'Desarrollo de Software',
                institucion: 'Fundación Niños del Ecuador',
                sector: 'ONG',
                fechaInicio: '2025-08-01',
                fechaFin: '2025-10-30',
                horasTotal: 96,
                horasCumplidas: 45,
                estado: 'Iniciado',
                progreso: 47,
                descripcion: 'Desarrollo de plataforma educativa para niños en situación vulnerable'
            }
        ]
    };

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verDetalle(id) {
        // Buscar la práctica en ambos arrays
        let practica = [...practicasData.preprofesionales, ...practicasData.servicio].find(p => p.id === id);
        
        if (practica) {
            document.getElementById('detalleEstudiante').textContent = practica.estudiante;
            document.getElementById('detalleCarrera').textContent = practica.carrera;
            document.getElementById('detalleTipo').textContent = id <= 2 ? 'Preprofesional' : 'Servicio Comunitario';
            document.getElementById('detalleInstitucion').textContent = practica.institucion;
            document.getElementById('detallePeriodo').textContent = `${practica.fechaInicio} - ${practica.fechaFin}`;
            document.getElementById('detalleEstado').textContent = practica.estado;
            document.getElementById('detalleDescripcion').textContent = practica.descripcion;
            document.getElementById('progressPercent').textContent = `${practica.progreso}%`;
            document.getElementById('progressHours').textContent = `${practica.horasCumplidas} de ${practica.horasTotal} horas`;
            
            drawProgressChart(practica.progreso);
            showModal('modalDetallePractica');
        }
    }

    function editarPractica(id) {
        showNotification('Función de edición en desarrollo', 'info');
    }

    function registrarAsistencia(id) {
        showModal('modalAsistencia');
    }

    function descargarReporte(id) {
        showNotification('Descargando reporte...', 'success');
    }

    function guardarPractica() {
        showNotification('Práctica guardada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalNuevaPractica')).hide();
    }

    function guardarAsistencia() {
        showNotification('Asistencia registrada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalAsistencia')).hide();
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
        showNotification('Generando reporte...', 'info');
    }

    function exportData() {
        showNotification('Exportando datos...', 'info');
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
        
        // Set default end date (3 months later)
        const endDate = new Date();
        endDate.setMonth(endDate.getMonth() + 3);
        document.querySelector('input[name="fecha_fin"]').value = endDate.toISOString().split('T')[0];

        // Initialize progress chart
        drawProgressChart(75);

        // Add timeline styles
        const timelineStyle = document.createElement('style');
        timelineStyle.textContent = `
            .timeline {
                position: relative;
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
            .avatar-sm {
                width: 35px;
                height: 35px;
                font-size: 0.75rem;
            }
        `;
        document.head.appendChild(timelineStyle);
    });

    // Tab change handler
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target');
            if (target === '#seguimiento') {
                setTimeout(() => drawProgressChart(75), 100);
            }
        });
    });

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
</script>
<?= $this->endSection() ?>
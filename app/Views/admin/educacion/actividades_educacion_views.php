<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/actividades.css') ?>" />
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
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #667eea 80%, #764ba2 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalActividades" style="font-size:2.5rem;">15</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Actividades</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #f093fb 80%, #f5576c 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="cursosActivos" style="font-size:2.5rem;">8</h2>
                        <p class="card-text fw-bold" style="color: #ffe6e6;">Cursos Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #4facfe 80%, #00f2fe 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="talleresActivos" style="font-size:2.5rem;">5</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Talleres Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #43e97b 80%, #38f9d7 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="seminariosActivos" style="font-size:2.5rem;">2</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Seminarios Activos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaActividad')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div class="fw-bold">Nueva Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevoInstructor')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                            <div class="fw-bold">Nuevo Instructor</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="verCalendario()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                            <div class="fw-bold">Ver Calendario</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarCertificados()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-certificate fa-2x mb-2"></i>
                            <div class="fw-bold">Certificados</div>
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
                                                    <tr>
                                                        <td>001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-laptop-code fa-2x me-2 text-primary"></i>
                                                                <div>
                                                                    <div class="fw-semibold">Desarrollo Web Full Stack</div>
                                                                    <small class="text-muted">Programación avanzada</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Ing. Carlos Mendoza</div>
                                                            <small class="text-muted">Especialista en Desarrollo</small>
                                                        </td>
                                                        <td><span class="badge bg-info">Presencial</span></td>
                                                        <td>
                                                            <div>Sep 2025 - Nov 2025</div>
                                                            <small class="text-muted">3 meses</small>
                                                        </td>
                                                        <td><span class="badge bg-secondary">120h</span></td>
                                                        <td><span class="badge bg-success">Activo</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(1)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarActividad(1)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success" onclick="generarCertificado(1)" title="Certificado">
                                                                    <i class="fas fa-certificate"></i>
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
                                                    <tr>
                                                        <td>T001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-wrench fa-2x me-2 text-success"></i>
                                                                <div>
                                                                    <div class="fw-semibold">Reparación de Equipos</div>
                                                                    <small class="text-muted">Mantenimiento de Hardware</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Tec. Ana Ruiz</div>
                                                            <small class="text-muted">Especialista en Hardware</small>
                                                        </td>
                                                        <td><span class="badge bg-warning text-dark">Virtual</span></td>
                                                        <td>
                                                            <div>Oct 2025 - Oct 2025</div>
                                                            <small class="text-muted">1 mes</small>
                                                        </td>
                                                        <td><span class="badge bg-secondary">40h</span></td>
                                                        <td><span class="badge bg-warning text-dark">Próximo</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(2)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarActividad(2)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="gestionarParticipantes(2)" title="Participantes">
                                                                    <i class="fas fa-users"></i>
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
                                                    <tr>
                                                        <td>S001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-comments fa-2x me-2 text-info"></i>
                                                                <div>
                                                                    <div class="fw-semibold">Inteligencia Artificial</div>
                                                                    <small class="text-muted">Tecnologías Emergentes</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Dr. María González</div>
                                                            <small class="text-muted">Doctora en IA</small>
                                                        </td>
                                                        <td><span class="badge bg-info">Presencial</span></td>
                                                        <td>
                                                            <div>Dic 2025 - Dic 2025</div>
                                                            <small class="text-muted">2 días</small>
                                                        </td>
                                                        <td><span class="badge bg-secondary">16h</span></td>
                                                        <td><span class="badge bg-secondary">Programado</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(3)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarActividad(3)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success" onclick="abrirInscripciones(3)" title="Inscripciones">
                                                                    <i class="fas fa-user-plus"></i>
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
                <form id="formNuevaActividad">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Actividad</label>
                                <select class="form-select" name="tipo_actividad" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Curso">Curso</option>
                                    <option value="Taller">Taller</option>
                                    <option value="Seminario">Seminario</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Actividad</label>
                                <input type="text" class="form-control" name="nombre_actividad" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Instructor</label>
                                <select class="form-select" name="instructor" required>
                                    <option value="">Seleccionar instructor...</option>
                                    <option value="1">Ing. Carlos Mendoza</option>
                                    <option value="2">Tec. Ana Ruiz</option>
                                    <option value="3">Dr. María González</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Modalidad</label>
                                <select class="form-select" name="modalidad" required>
                                    <option value="">Seleccionar modalidad...</option>
                                    <option value="1">Presencial</option>
                                    <option value="2">Virtual</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Duración (horas)</label>
                                <input type="number" class="form-control" name="duracion_horas" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lugar</label>
                                <input type="text" class="form-control" name="lugar" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Horario</label>
                                <input type="text" class="form-control" name="horario" placeholder="Ej: Lunes a Viernes 8:00-12:00" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Objetivos</label>
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
                <button type="button" class="btn btn-primary" onclick="guardarActividad()">
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
                                    <button class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-certificate me-1"></i>Generar Certificados
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

<script>
    // Datos simulados
    let actividadesData = {
        cursos: [
            {
                id: 1,
                nombre: 'Desarrollo Web Full Stack',
                tipo: 'Curso',
                instructor: 'Ing. Carlos Mendoza',
                modalidad: 'Presencial',
                fechaInicio: '2025-09-01',
                fechaFin: '2025-11-30',
                duracionHoras: 120,
                lugar: 'Laboratorio de Programación',
                horario: 'Lunes a Viernes 14:00-18:00',
                estado: 'Activo',
                certificado: true,
                descripcion: 'Curso completo de desarrollo web con tecnologías modernas',
                objetivos: 'Formar desarrolladores full stack competentes'
            }
        ],
        talleres: [
            {
                id: 2,
                nombre: 'Reparación de Equipos',
                tipo: 'Taller',
                instructor: 'Tec. Ana Ruiz',
                modalidad: 'Virtual',
                fechaInicio: '2025-10-01',
                fechaFin: '2025-10-31',
                duracionHoras: 40,
                lugar: 'Plataforma Virtual',
                horario: 'Sábados 9:00-13:00',
                estado: 'Próximo',
                certificado: true,
                descripcion: 'Taller práctico de mantenimiento de hardware',
                objetivos: 'Capacitar en técnicas de reparación'
            }
        ],
        seminarios: [
            {
                id: 3,
                nombre: 'Inteligencia Artificial',
                tipo: 'Seminario',
                instructor: 'Dr. María González',
                modalidad: 'Presencial',
                fechaInicio: '2025-12-15',
                fechaFin: '2025-12-16',
                duracionHoras: 16,
                lugar: 'Auditorio Principal',
                horario: '8:00-17:00',
                estado: 'Programado',
                certificado: true,
                descripcion: 'Seminario sobre tendencias en IA',
                objetivos: 'Actualizar conocimientos en IA'
            }
        ]
    };

    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verDetalle(id) {
        let actividad = [...actividadesData.cursos, ...actividadesData.talleres, ...actividadesData.seminarios].find(a => a.id === id);
        
        if (actividad) {
            document.getElementById('detalleNombre').textContent = actividad.nombre;
            document.getElementById('detalleTipoActividad').textContent = actividad.tipo;
            document.getElementById('detalleInstructor').textContent = actividad.instructor;
            document.getElementById('detalleModalidad').textContent = actividad.modalidad;
            document.getElementById('detallePeriodo').textContent = `${actividad.fechaInicio} - ${actividad.fechaFin}`;
            document.getElementById('detalleDuracion').textContent = `${actividad.duracionHoras} horas`;
            document.getElementById('detalleLugar').textContent = actividad.lugar;
            document.getElementById('detalleHorario').textContent = actividad.horario;
            document.getElementById('detalleDescripcion').textContent = actividad.descripcion;
            document.getElementById('detalleObjetivos').textContent = actividad.objetivos;
            document.getElementById('estadoActividad').textContent = actividad.estado;
            document.getElementById('certificadoInfo').textContent = actividad.certificado ? 'Con certificado' : 'Sin certificado';
            
            showModal('modalDetalleActividad');
        }
    }

    function editarActividad(id) {
        showNotification('Función de edición en desarrollo', 'info');
    }

    function guardarActividad() {
        showNotification('Actividad guardada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalNuevaActividad')).hide();
    }

    function generarCertificado(id) {
        showNotification('Generando certificado...', 'success');
    }

    function verCalendario() {
        showNotification('Abriendo calendario...', 'info');
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="fecha_inicio"]').value = today;
    });
</script>
<?= $this->endSection() ?>

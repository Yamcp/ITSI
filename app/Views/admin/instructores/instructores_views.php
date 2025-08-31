<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/instructores.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Gestión de Instructores
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #667eea 80%, #764ba2 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalInstructores" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Instructores</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #f093fb 80%, #f5576c 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="instructoresActivos" style="font-size:2.5rem;">8</h2>
                        <p class="card-text fw-bold" style="color: #ffe6e6;">Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #4facfe 80%, #00f2fe 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="actividadesActivas" style="font-size:2.5rem;">15</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Actividades Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #43e97b 80%, #38f9d7 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="promedioEvaluacion" style="font-size:2.5rem;">4.8</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Promedio Evaluación</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevoInstructor')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-user-plus fa-2x mb-2"></i>
                            <div class="fw-bold">Nuevo Instructor</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalAsignarActividad')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-tasks fa-2x mb-2"></i>
                            <div class="fw-bold">Asignar Actividad</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporte()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarDatos()" style="text-decoration: none; color: inherit;">
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
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="instructoresTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-users me-2"></i>Todos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="internos-tab" data-bs-toggle="tab" data-bs-target="#internos" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-building me-2"></i>Internos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="externos-tab" data-bs-toggle="tab" data-bs-target="#externos" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-user-tie me-2"></i>Externos
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <!-- Contenido de las pestañas -->
                        <div class="tab-content mt-3" id="instructoresTabContent">
                            <!-- Todos los Instructores -->
                            <div class="tab-pane fade show active" id="todos" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-users me-2"></i>Todos los Instructores</span>
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
                                                        <th>Instructor</th>
                                                        <th>Tipo</th>
                                                        <th>Especialidad</th>
                                                        <th>Actividades</th>
                                                        <th>Evaluación</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaTodos">
                                                    <tr>
                                                        <td>001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Carlos+Mendoza&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="CM">
                                                                <div>
                                                                    <div class="fw-semibold">Ing. Carlos Mendoza</div>
                                                                    <small class="text-muted">carlos.mendoza@itsi.edu.ec</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-success">Interno</span></td>
                                                        <td>
                                                            <div>Desarrollo de Software</div>
                                                            <small class="text-muted">Ingeniería en Sistemas</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">3 Activas</span>
                                                            <small class="text-muted d-block">8 Completadas</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">4.9</span>
                                                                <div class="stars">
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-success">Activo</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(1)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarInstructor(1)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="verActividades(1)" title="Actividades">
                                                                    <i class="fas fa-tasks"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>002</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Ana+Ruiz&background=198754&color=fff&size=32" class="rounded-circle me-2" alt="AR">
                                                                <div>
                                                                    <div class="fw-semibold">Tec. Ana Ruiz</div>
                                                                    <small class="text-muted">ana.ruiz@itsi.edu.ec</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-info">Externo</span></td>
                                                        <td>
                                                            <div>Hardware y Redes</div>
                                                            <small class="text-muted">Técnico en Electrónica</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning text-dark">2 Activas</span>
                                                            <small class="text-muted d-block">5 Completadas</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">4.7</span>
                                                                <div class="stars">
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="far fa-star text-warning"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-success">Activo</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(2)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarInstructor(2)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="verActividades(2)" title="Actividades">
                                                                    <i class="fas fa-tasks"></i>
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

                            <!-- Instructores Internos -->
                            <div class="tab-pane fade" id="internos" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-building me-2"></i>Instructores Internos</span>
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
                                                        <th>Instructor</th>
                                                        <th>Departamento</th>
                                                        <th>Especialidad</th>
                                                        <th>Actividades</th>
                                                        <th>Evaluación</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaInternos">
                                                    <tr>
                                                        <td>001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Carlos+Mendoza&background=0d6efd&color=fff&size=32" class="rounded-circle me-2" alt="CM">
                                                                <div>
                                                                    <div class="fw-semibold">Ing. Carlos Mendoza</div>
                                                                    <small class="text-muted">carlos.mendoza@itsi.edu.ec</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>Desarrollo de Software</div>
                                                            <small class="text-muted">Tecnologías de la Información</small>
                                                        </td>
                                                        <td>
                                                            <div>Desarrollo Web Full Stack</div>
                                                            <small class="text-muted">JavaScript, React, Node.js</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">3 Activas</span>
                                                            <small class="text-muted d-block">8 Completadas</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">4.9</span>
                                                                <div class="stars">
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-success">Activo</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(1)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarInstructor(1)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success" onclick="asignarActividad(1)" title="Asignar">
                                                                    <i class="fas fa-plus"></i>
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

                            <!-- Instructores Externos -->
                            <div class="tab-pane fade" id="externos" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-user-tie me-2"></i>Instructores Externos</span>
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
                                                        <th>Instructor</th>
                                                        <th>Empresa/Institución</th>
                                                        <th>Especialidad</th>
                                                        <th>Actividades</th>
                                                        <th>Evaluación</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaExternos">
                                                    <tr>
                                                        <td>002</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="https://ui-avatars.com/api/?name=Ana+Ruiz&background=198754&color=fff&size=32" class="rounded-circle me-2" alt="AR">
                                                                <div>
                                                                    <div class="fw-semibold">Tec. Ana Ruiz</div>
                                                                    <small class="text-muted">ana.ruiz@empresa.com</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>TechCorp Solutions</div>
                                                            <small class="text-muted">Empresa Privada</small>
                                                        </td>
                                                        <td>
                                                            <div>Hardware y Redes</div>
                                                            <small class="text-muted">Cisco, Microsoft, Linux</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning text-dark">2 Activas</span>
                                                            <small class="text-muted d-block">5 Completadas</small>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">4.7</span>
                                                                <div class="stars">
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="fas fa-star text-warning"></i>
                                                                    <i class="far fa-star text-warning"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="badge bg-success">Activo</span></td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(2)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarInstructor(2)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="evaluarInstructor(2)" title="Evaluar">
                                                                    <i class="fas fa-star"></i>
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

<!-- Modal Nuevo Instructor -->
<div class="modal fade" id="modalNuevoInstructor" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Nuevo Instructor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoInstructor">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Instructor</label>
                                <select class="form-select" name="tipo_instructor" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Interno</option>
                                    <option value="2">Externo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Título Profesional</label>
                                <input type="text" class="form-control" name="titulo_profesional" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cédula</label>
                                <input type="text" class="form-control" name="cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Celular</label>
                                <input type="text" class="form-control" name="celular" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Género</label>
                                <select class="form-select" name="genero" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="direccion" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad</label>
                        <textarea class="form-control" name="especialidad" rows="3" placeholder="Describe las áreas de especialización del instructor..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control" name="nacionalidad" value="Ecuatoriana" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado Civil</label>
                                <select class="form-select" name="estado_civil" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Soltero">Soltero</option>
                                    <option value="Casado">Casado</option>
                                    <option value="Divorciado">Divorciado</option>
                                    <option value="Viudo">Viudo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarInstructor()">
                    <i class="fas fa-save me-1"></i>Guardar Instructor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Instructor -->
<div class="modal fade" id="modalDetalleInstructor" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalle del Instructor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Información Personal</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> <span id="detalleNombre">-</span></p>
                                        <p><strong>Título:</strong> <span id="detalleTitulo">-</span></p>
                                        <p><strong>Tipo:</strong> <span id="detalleTipo">-</span></p>
                                        <p><strong>Email:</strong> <span id="detalleEmail">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Cédula:</strong> <span id="detalleCedula">-</span></p>
                                        <p><strong>Celular:</strong> <span id="detalleCelular">-</span></p>
                                        <p><strong>Género:</strong> <span id="detalleGenero">-</span></p>
                                        <p><strong>Estado Civil:</strong> <span id="detalleEstadoCivil">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Dirección:</strong></p>
                                        <p class="text-muted" id="detalleDireccion">-</p>
                                        <p><strong>Especialidad:</strong></p>
                                        <p class="text-muted" id="detalleEspecialidad">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Actividades Recientes</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Actividad</th>
                                                <th>Tipo</th>
                                                <th>Modalidad</th>
                                                <th>Período</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaActividadesInstructor">
                                            <tr>
                                                <td>Desarrollo Web Full Stack</td>
                                                <td><span class="badge bg-primary">Curso</span></td>
                                                <td><span class="badge bg-info">Presencial</span></td>
                                                <td>Sep 2025 - Nov 2025</td>
                                                <td><span class="badge bg-success">Activo</span></td>
                                            </tr>
                                            <tr>
                                                <td>JavaScript Avanzado</td>
                                                <td><span class="badge bg-success">Taller</span></td>
                                                <td><span class="badge bg-warning text-dark">Virtual</span></td>
                                                <td>Jul 2025 - Ago 2025</td>
                                                <td><span class="badge bg-secondary">Completado</span></td>
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
                                <h6 class="mb-0">Estadísticas</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <h4 id="totalActividadesInstructor">11</h4>
                                    <p class="text-muted">Total Actividades</p>
                                </div>
                                <div class="mb-3">
                                    <h4 id="evaluacionPromedio">4.9</h4>
                                    <p class="text-muted">Evaluación Promedio</p>
                                    <div class="stars">
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                        <i class="fas fa-star text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <h4 id="participantesTotal">156</h4>
                                    <p class="text-muted">Participantes Totales</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Acciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-tasks me-1"></i>Ver Todas las Actividades
                                    </button>
                                    <button class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-plus me-1"></i>Asignar Nueva Actividad
                                    </button>
                                    <button class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-star me-1"></i>Evaluar Instructor
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-file-alt me-1"></i>Generar Reporte
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
                    <i class="fas fa-edit me-1"></i>Editar Instructor
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Datos simulados
    let instructoresData = {
        todos: [
            {
                id: 1,
                nombre: 'Ing. Carlos Mendoza',
                email: 'carlos.mendoza@itsi.edu.ec',
                tipo: 'Interno',
                especialidad: 'Desarrollo de Software',
                titulo: 'Ingeniería en Sistemas',
                actividades: { activas: 3, completadas: 8 },
                evaluacion: 4.9,
                estado: 'Activo',
                cedula: '1234567890',
                celular: '0987654321',
                genero: 'Masculino',
                estadoCivil: 'Casado',
                direccion: 'Av. Principal 123, Ibarra',
                nacionalidad: 'Ecuatoriana'
            },
            {
                id: 2,
                nombre: 'Tec. Ana Ruiz',
                email: 'ana.ruiz@empresa.com',
                tipo: 'Externo',
                especialidad: 'Hardware y Redes',
                titulo: 'Técnico en Electrónica',
                actividades: { activas: 2, completadas: 5 },
                evaluacion: 4.7,
                estado: 'Activo',
                cedula: '0987654321',
                celular: '0912345678',
                genero: 'Femenino',
                estadoCivil: 'Soltera',
                direccion: 'Calle Secundaria 456, Quito',
                nacionalidad: 'Ecuatoriana'
            }
        ]
    };

    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verDetalle(id) {
        let instructor = instructoresData.todos.find(i => i.id === id);
        
        if (instructor) {
            document.getElementById('detalleNombre').textContent = instructor.nombre;
            document.getElementById('detalleTitulo').textContent = instructor.titulo;
            document.getElementById('detalleTipo').textContent = instructor.tipo;
            document.getElementById('detalleEmail').textContent = instructor.email;
            document.getElementById('detalleCedula').textContent = instructor.cedula;
            document.getElementById('detalleCelular').textContent = instructor.celular;
            document.getElementById('detalleGenero').textContent = instructor.genero;
            document.getElementById('detalleEstadoCivil').textContent = instructor.estadoCivil;
            document.getElementById('detalleDireccion').textContent = instructor.direccion;
            document.getElementById('detalleEspecialidad').textContent = instructor.especialidad;
            document.getElementById('totalActividadesInstructor').textContent = instructor.actividades.activas + instructor.actividades.completadas;
            document.getElementById('evaluacionPromedio').textContent = instructor.evaluacion;
            
            showModal('modalDetalleInstructor');
        }
    }

    function editarInstructor(id) {
        showNotification('Función de edición en desarrollo', 'info');
    }

    function verActividades(id) {
        showNotification('Abriendo actividades del instructor...', 'info');
    }

    function asignarActividad(id) {
        showModal('modalAsignarActividad');
    }

    function evaluarInstructor(id) {
        showNotification('Abriendo evaluación del instructor...', 'info');
    }

    function guardarInstructor() {
        showNotification('Instructor guardado exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalNuevoInstructor')).hide();
    }

    function generarReporte() {
        showNotification('Generando reporte...', 'info');
    }

    function exportarDatos() {
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set default values
        const today = new Date().toISOString().split('T')[0];
        
        // Add any initialization code here
    });
</script>
<?= $this->endSection() ?>

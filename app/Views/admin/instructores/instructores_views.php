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
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalInstructores" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Instructores</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="instructoresActivos" style="font-size:2.5rem;">8</h2>
                        <p class="card-text fw-bold" style="color: #ffe6e6;">Activos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="actividadesActivas" style="font-size:2.5rem;">15</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Actividades Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 80%, #a71e2a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="promedioEvaluacion" style="font-size:2.5rem;">4.8</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Promedio Evaluación</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevoInstructor')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-user-plus fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nuevo Instructor</div>
                        </a>
                    </div>
                </div>
            </div>            
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporte()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarDatos()" style="text-decoration: none; color: inherit;">
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
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="instructoresTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="todos-tab" data-bs-toggle="tab" data-bs-target="#todos" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-users me-2" style="color: #007bff;"></i>Todos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="internos-tab" data-bs-toggle="tab" data-bs-target="#internos" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-building me-2" style="color: #28a745;"></i>Internos
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-info" id="externos-tab" data-bs-toggle="tab" data-bs-target="#externos" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-user-tie me-2" style="color: #17a2b8;"></i>Externos
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
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                </div>
                <form id="formNuevoInstructor">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Instructor<span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_instructor" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Interno</option>
                                    <option value="2">Externo</option>
                                </select>
                                <div class="invalid-feedback">
                                    Por favor selecciona un tipo de instructor.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Título Profesional<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="titulo_profesional" required>
                                <div class="invalid-feedback">
                                    Por favor ingresa el título profesional.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Apellido<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cédula<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Celular<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="celular" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Género<span class="text-danger">*</span></label>
                                <select class="form-select" name="genero" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="direccion" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="especialidad" rows="3" placeholder="Describe las áreas de especialización del instructor..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nacionalidad<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nacionalidad" value="Ecuatoriana" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado Civil<span class="text-danger">*</span></label>
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

<!-- Modal Opciones de Reporte -->
<div class="modal fade" id="modalOpcionesReporte" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar me-2" style="color: #ffc107;"></i>Opciones de Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x mb-3" style="color: #dc3545;"></i>
                                <h5>Reporte PDF</h5>
                                <p class="text-muted">Genera un reporte completo de instructores en formato PDF</p>
                                <button class="btn btn-danger" onclick="generarReportePDF()">
                                    <i class="fas fa-file-pdf me-2"></i>Generar PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Opciones de Exportación -->
<div class="modal fade" id="modalOpcionesExportacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-download me-2" style="color: #dc3545;"></i>Opciones de Exportación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-excel fa-3x mb-3" style="color: #28a745;"></i>
                                <h6>Excel</h6>
                                <p class="text-muted small">Exportar a formato Excel (.xlsx)</p>
                                <button class="btn btn-success btn-sm" onclick="exportarExcel()">
                                    <i class="fas fa-file-excel me-1"></i>Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-csv fa-3x mb-3" style="color: #007bff;"></i>
                                <h6>CSV</h6>
                                <p class="text-muted small">Exportar a formato CSV</p>
                                <button class="btn btn-primary btn-sm" onclick="exportarCSV()">
                                    <i class="fas fa-file-csv me-1"></i>CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let instructoresData = [];
    let currentInstructorId = null;

    // Cargar datos desde la base de datos
    async function cargarInstructores() {
        try {
            const response = await fetch('<?= base_url('admin/instructores/getInstructores') ?>');
            const result = await response.json();
            
            if (result.success) {
                instructoresData = result.data;
                actualizarTablaInstructores();
                cargarEstadisticas();
            } else {
                showNotification('Error al cargar instructores: ' + result.message, 'error');
            }
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
        }
    }

    // Cargar estadísticas
    async function cargarEstadisticas() {
        try {
            const response = await fetch('<?= base_url('admin/instructores/getEstadisticas') ?>');
            const result = await response.json();
            
            if (result.success) {
                const stats = result.data;
                document.getElementById('totalInstructores').textContent = stats.total_instructores;
                document.getElementById('instructoresActivos').textContent = stats.instructores_activos;
                document.getElementById('actividadesActivas').textContent = stats.actividades_activas;
                document.getElementById('promedioEvaluacion').textContent = stats.promedio_evaluacion;
            }
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    // Actualizar tabla de instructores
    function actualizarTablaInstructores() {
        const tbodyTodos = document.getElementById('tablaTodos');
        const tbodyInternos = document.getElementById('tablaInternos');
        const tbodyExternos = document.getElementById('tablaExternos');
        
        // Limpiar tablas
        tbodyTodos.innerHTML = '';
        tbodyInternos.innerHTML = '';
        tbodyExternos.innerHTML = '';
        
        instructoresData.forEach((instructor, index) => {
            const row = crearFilaInstructor(instructor, index + 1);
            
            // Agregar a tabla "Todos"
            tbodyTodos.appendChild(row.cloneNode(true));
            
            // Agregar a tabla específica según tipo
            if (instructor.TIPO_INSTRUCTOR === 'Interno') {
                tbodyInternos.appendChild(row.cloneNode(true));
            } else if (instructor.TIPO_INSTRUCTOR === 'Externo') {
                tbodyExternos.appendChild(row.cloneNode(true));
            }
        });
    }

    // Crear fila de instructor
    function crearFilaInstructor(instructor, numero) {
        const tr = document.createElement('tr');
        
        const tipoBadge = instructor.TIPO_INSTRUCTOR === 'Interno' ? 
            '<span class="badge bg-success">Interno</span>' : 
            '<span class="badge bg-info">Externo</span>';
        
        const actividadesActivas = instructor.actividades_activas || 0;
        const actividadesCompletadas = instructor.actividades_completadas || 0;
        
        tr.innerHTML = `
            <td>${numero.toString().padStart(3, '0')}</td>
            <td>
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(instructor.NOMBRE + '+' + instructor.APELLIDO)}&background=${instructor.TIPO_INSTRUCTOR === 'Interno' ? '0d6efd' : '198754'}&color=fff&size=32" class="rounded-circle me-2" alt="${instructor.NOMBRE.charAt(0)}${instructor.APELLIDO.charAt(0)}">
                    <div>
                        <div class="fw-semibold">${instructor.TITULO_PROFESIONAL} ${instructor.NOMBRE} ${instructor.APELLIDO}</div>
                        <small class="text-muted">${instructor.EMAIL}</small>
                    </div>
                </div>
            </td>
            <td>${tipoBadge}</td>
            <td>
                <div>${instructor.ESPECIALIDAD}</div>
                <small class="text-muted">${instructor.TITULO_PROFESIONAL}</small>
            </td>
            <td>
                <span class="badge bg-info">${actividadesActivas} Activas</span>
                <small class="text-muted d-block">${actividadesCompletadas} Completadas</small>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <span class="me-2">4.8</span>
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
                    <button class="btn btn-outline-primary" onclick="verDetalle(${instructor.ID_INSTRUCTOR})" title="Ver Detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="editarInstructor(${instructor.ID_INSTRUCTOR})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-info" onclick="verActividades(${instructor.ID_INSTRUCTOR})" title="Actividades">
                        <i class="fas fa-tasks"></i>
                    </button>
                </div>
            </td>
        `;
        
        return tr;
    }

    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    async function verDetalle(id) {
        try {
            const response = await fetch(`<?= base_url('admin/instructores/getInstructor') ?>/${id}`);
            const result = await response.json();
            
            if (result.success) {
                const instructor = result.data;
                
                document.getElementById('detalleNombre').textContent = `${instructor.TITULO_PROFESIONAL} ${instructor.NOMBRE} ${instructor.APELLIDO}`;
                document.getElementById('detalleTitulo').textContent = instructor.TITULO_PROFESIONAL;
                document.getElementById('detalleTipo').textContent = instructor.TIPO_INSTRUCTOR;
                document.getElementById('detalleEmail').textContent = instructor.EMAIL;
                document.getElementById('detalleCedula').textContent = instructor.CEDULA;
                document.getElementById('detalleCelular').textContent = instructor.CELULAR;
                document.getElementById('detalleGenero').textContent = instructor.GENERO;
                document.getElementById('detalleEstadoCivil').textContent = instructor.ESTADO_CIVIL;
                document.getElementById('detalleDireccion').textContent = instructor.DIRECCION;
                document.getElementById('detalleEspecialidad').textContent = instructor.ESPECIALIDAD;
                document.getElementById('totalActividadesInstructor').textContent = instructor.actividades ? instructor.actividades.length : 0;
                document.getElementById('evaluacionPromedio').textContent = '4.8';
                
                // Actualizar tabla de actividades
                const tbodyActividades = document.getElementById('tablaActividadesInstructor');
                tbodyActividades.innerHTML = '';
                
                if (instructor.actividades && instructor.actividades.length > 0) {
                    instructor.actividades.forEach(actividad => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${actividad.NOMBRE_ACTIVIDAD}</td>
                            <td><span class="badge bg-primary">Curso</span></td>
                            <td><span class="badge bg-info">Presencial</span></td>
                            <td>${new Date(actividad.FECHA_INICIO).toLocaleDateString()} - ${new Date(actividad.FECHA_FIN).toLocaleDateString()}</td>
                            <td><span class="badge ${new Date(actividad.FECHA_FIN) >= new Date() ? 'bg-success' : 'bg-secondary'}">${new Date(actividad.FECHA_FIN) >= new Date() ? 'Activo' : 'Completado'}</span></td>
                        `;
                        tbodyActividades.appendChild(tr);
                    });
                }
                
                showModal('modalDetalleInstructor');
            } else {
                showNotification('Error al obtener detalles del instructor: ' + result.message, 'error');
            }
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
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

    async function guardarInstructor() {
        const form = document.getElementById('formNuevoInstructor');
        const formData = new FormData(form);
        
        // Limpiar validaciones anteriores
        form.classList.remove('was-validated');
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });
        
        // Validar campos obligatorios
        const camposObligatorios = [
            'tipo_instructor', 
            'titulo_profesional', 
            'nombre', 
            'apellido', 
            'cedula', 
            'email', 
            'celular', 
            'genero', 
            'direccion', 
            'especialidad', 
            'nacionalidad', 
            'estado_civil'
        ];
        
        let hayErrores = false;
        
        camposObligatorios.forEach(campo => {
            const input = form.querySelector(`[name="${campo}"]`);
            const valor = formData.get(campo);
            
            if (!valor || valor.trim() === '') {
                input.classList.add('is-invalid');
                hayErrores = true;
            } else {
                input.classList.add('is-valid');
            }
        });
        
        // Validar formato de email
        const email = formData.get('email');
        const emailInput = form.querySelector('[name="email"]');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            emailInput.classList.add('is-invalid');
            hayErrores = true;
        }
        
        // Validar formato de cédula (10 dígitos para Ecuador)
        const cedula = formData.get('cedula');
        const cedulaInput = form.querySelector('[name="cedula"]');
        if (cedula && (cedula.length !== 10 || !/^\d+$/.test(cedula))) {
            cedulaInput.classList.add('is-invalid');
            hayErrores = true;
        }
        
        // Validar formato de celular (10 dígitos para Ecuador)
        const celular = formData.get('celular');
        const celularInput = form.querySelector('[name="celular"]');
        if (celular && (celular.length !== 10 || !/^\d+$/.test(celular))) {
            celularInput.classList.add('is-invalid');
            hayErrores = true;
        }
        
        if (hayErrores) {
            form.classList.add('was-validated');
            showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }
        
        // Enviar datos al servidor
        try {
            const response = await fetch('<?= base_url('admin/instructores/crear') ?>', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                showNotification('Instructor guardado exitosamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoInstructor')).hide();
                form.reset();
                cargarInstructores(); // Recargar la lista
                cargarEstadisticas(); // Actualizar estadísticas
            } else {
                if (result.errors) {
                    // Mostrar errores específicos de validación
                    let errorMessage = 'Errores de validación:\n';
                    Object.keys(result.errors).forEach(campo => {
                        errorMessage += `• ${result.errors[campo]}\n`;
                    });
                    showNotification(errorMessage, 'error');
                } else {
                    showNotification('Error al guardar instructor: ' + result.message, 'error');
                }
            }
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
        }
    }

    function generarReporte() {
        // Mostrar modal de opciones de reporte
        showModal('modalOpcionesReporte');
    }

    function exportarDatos() {
        // Mostrar modal dinámico de opciones de exportación
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

        // Agregar el modal al body
        document.body.appendChild(modal);

        // Mostrar el modal
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        // Limpiar el modal cuando se cierre
        modal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modal);
        });
    }

    function exportarFormato(formato) {
        // Cerrar el modal primero
        const modal = document.getElementById('modalOpcionesExportacion');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }

        // Ejecutar la exportación según el formato seleccionado
        switch (formato) {
            case 'pdf':
                generarReportePDF();
                break;
            case 'excel':
                exportarExcel();
                break;
            default:
                showNotification('Formato de exportación no válido', 'error');
        }
    }

    function generarReportePDF() {
        // Abrir reporte PDF en nueva ventana
        window.open('<?= base_url('admin/instructores/generarReporte') ?>', '_blank');
        showNotification('Generando reporte PDF...', 'info');
    }

    function exportarExcel() {
        // Descargar archivo Excel
        window.location.href = '<?= base_url('admin/instructores/exportarExcel') ?>';
        showNotification('Exportando datos a Excel...', 'info');
    }

    function exportarCSV() {
        // Descargar archivo CSV
        window.location.href = '<?= base_url('admin/instructores/exportarCSV') ?>';
        showNotification('Exportando datos a CSV...', 'info');
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
        // Cargar datos iniciales
        cargarInstructores();
        cargarEstadisticas();
        
        // Set default values
        const today = new Date().toISOString().split('T')[0];
        
        // Cargar tipos de instructores en el select
        cargarTiposInstructores();
        
        // Agregar validación en tiempo real
        agregarValidacionTiempoReal();
    });

    // Agregar validación en tiempo real a los campos
    function agregarValidacionTiempoReal() {
        const form = document.getElementById('formNuevoInstructor');
        const inputs = form.querySelectorAll('.form-control, .form-select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validarCampo(this);
            });
            
            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validarCampo(this);
                }
            });
        });
    }

    // Validar campo individual
    function validarCampo(input) {
        const valor = input.value.trim();
        const nombre = input.name;
        
        // Limpiar clases anteriores
        input.classList.remove('is-valid', 'is-invalid');
        
        // Validar campo vacío
        if (!valor) {
            input.classList.add('is-invalid');
            return false;
        }
        
        // Validaciones específicas
        let esValido = true;
        
        switch (nombre) {
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                esValido = emailRegex.test(valor);
                break;
            case 'cedula':
                esValido = valor.length === 10 && /^\d+$/.test(valor);
                break;
            case 'celular':
                esValido = valor.length === 10 && /^\d+$/.test(valor);
                break;
            case 'nombre':
            case 'apellido':
                esValido = valor.length >= 2;
                break;
            case 'titulo_profesional':
            case 'especialidad':
                esValido = valor.length >= 3;
                break;
        }
        
        if (esValido) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
        
        return esValido;
    }

    // Cargar tipos de instructores para el formulario
    async function cargarTiposInstructores() {
        try {
            const response = await fetch('<?= base_url('admin/instructores/getTiposInstructores') ?>');
            const result = await response.json();
            
            if (result.success) {
                const select = document.querySelector('select[name="tipo_instructor"]');
                select.innerHTML = '<option value="">Seleccionar...</option>';
                
                result.data.forEach(tipo => {
                    const option = document.createElement('option');
                    option.value = tipo.ID_TIPO_INSTRUCTOR;
                    option.textContent = tipo.TIPO;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al cargar tipos de instructores:', error);
        }
    }
</script>
<?= $this->endSection() ?>

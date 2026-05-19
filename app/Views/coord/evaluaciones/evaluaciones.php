<?= $this->extend('coord/layouts/mainCoord') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para evaluaciones -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
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

    .link-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d;
        word-break: break-all;
    }

    /* Estilos para campos obligatorios */
    .form-label .text-danger {
        font-weight: bold;
    }

    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }

    .form-control.is-valid,
    .form-select.is-valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }

    .form-control.is-invalid~.invalid-feedback,
    .form-select.is-invalid~.invalid-feedback {
        display: block;
    }

    .valid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #28a745;
    }

    .form-control.is-valid~.valid-feedback,
    .form-select.is-valid~.valid-feedback {
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Volver a Actividades educativas -->
        <div class="row mb-2">
            <div class="col-12">
                <a href="<?= base_url('coord/educacion') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Volver a Actividades educativas
                </a>
            </div>
        </div>
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-clipboard-check me-2"></i>
                    Gestión de Evaluaciones y Formularios
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalEvaluaciones" style="font-size:2.5rem;">3</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Evaluaciones</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="evaluacionesActivas" style="font-size:2.5rem;">3</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Activas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalRespuestas" style="font-size:2.5rem;">146</h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Total Respuestas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 80%, #117a8b 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="promedioRespuestas" style="font-size:2.5rem;">48.7</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Promedio Respuestas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaEvaluacion')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 2, 0.3);"></i>
                            <div class="fw-bold">Nueva Evaluación</div>
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

        <!-- Tabla de Evaluaciones -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-clipboard-check me-2"></i>
                            Formularios de Evaluación
                        </span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="cambiarVista('grid')">
                                <i class="fas fa-th-large me-1"></i>Grid
                            </button>
                            <button class="btn btn-light btn-sm" onclick="cambiarVista('list')">
                                <i class="fas fa-list me-1"></i>Lista
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Vista Grid -->
                        <div id="vistaGrid" class="row g-3">
                            <!-- Las evaluaciones se cargarán dinámicamente aquí -->
                        </div>

                        <!-- Vista Lista -->
                        <div id="vistaLista" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Evaluación</th>
                                            <th>Tipo</th>
                                            <th>Enlace</th>
                                            <th>Estado</th>
                                            <th>Respuestas</th>
                                            <th>Vencimiento</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaEvaluacionesLista">
                                        <!-- Las evaluaciones se cargarán dinámicamente aquí -->
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

<!-- Modal Nueva Evaluación -->
<div class="modal fade" id="modalNuevaEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nueva Evaluación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevaEvaluacion">
                    <?= csrf_field() ?>
                    <input type="hidden" name="curso_id" id="cursoIdHidden">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Curso <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" name="curso_select" id="selectCurso" onchange="cargarDatosCurso()" required>
                                        <option value="">Seleccionar curso...</option>
                                        <!-- Los cursos se cargarán dinámicamente desde la BD -->
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" onclick="limpiarDatosCurso()" title="Limpiar selección">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Selecciona el curso para el cual crearás la evaluación</small>
                                <div class="invalid-feedback" id="error-curso">
                                    Debes seleccionar un curso
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Evaluación <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_evaluacion" id="tipoEvaluacion" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="satisfaccion">Satisfacción del Participante</option>
                                    <option value="instructores">Evaluación del Instructor</option>
                                    <option value="contenido">Evaluación del Contenido</option>
                                    <option value="metodologia">Evaluación de la Metodología</option>
                                    <option value="recursos">Evaluación de Recursos</option>
                                    <option value="general">Evaluación General</option>
                                </select>
                                <div class="invalid-feedback" id="error-tipo">
                                    Debes seleccionar un tipo de evaluación
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Evaluación <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre_evaluacion" id="nombreEvaluacion" placeholder="Se generará automáticamente al seleccionar el curso" required readonly>
                                <small class="text-muted">Se genera automáticamente basado en el curso seleccionado</small>
                                <div class="invalid-feedback" id="error-nombre">
                                    El nombre de la evaluación es requerido
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado del Curso</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="estadoCurso" readonly>
                                    <span class="input-group-text" id="estadoCursoIcon">
                                        <i class="fas fa-info-circle text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Inicio del Curso</label>
                                <input type="text" class="form-control" id="fechaInicioCurso" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Fin del Curso</label>
                                <input type="text" class="form-control" id="fechaFinCurso" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Enlace del Formulario <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="enlace_formulario" id="enlaceFormulario" placeholder="https://forms.google.com/..." required>
                        <small class="text-muted">Pega aquí el enlace del formulario que creaste en Google Forms, Microsoft Forms, etc.</small>
                        <div class="invalid-feedback" id="error-enlace">
                            Debes proporcionar un enlace válido del formulario
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" rows="3" placeholder="Describe el propósito de esta evaluación, qué se evalúa, etc."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_vencimiento" id="fechaVencimiento" required>
                                <div class="invalid-feedback" id="error-fecha">
                                    Debes seleccionar una fecha de vencimiento
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" name="estado" id="estadoEvaluacion" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="borrador">Borrador</option>
                                </select>
                                <div class="invalid-feedback" id="error-estado">
                                    Debes seleccionar un estado
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnGuardarEvaluacion" onclick="agregarEvaluacion()">
                    <i class="fas fa-save me-1"></i>Guardar Evaluación
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Evaluación -->
<div class="modal fade" id="modalEditarEvaluacion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Editar Evaluación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarEvaluacion">
                    <?= csrf_field() ?>
                    <input type="hidden" name="evaluacion_id" id="evaluacionIdEditar">
                    <input type="hidden" name="curso_id" id="cursoIdEditar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Seleccionar Curso <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-select" name="curso_select" id="selectCursoEditar" onchange="cargarDatosCursoEditar()" required>
                                        <option value="">Seleccionar curso...</option>
                                        <!-- Los cursos se cargarán dinámicamente desde la BD -->
                                    </select>
                                    <button class="btn btn-outline-secondary" type="button" onclick="limpiarDatosCursoEditar()" title="Limpiar selección">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Selecciona el curso para el cual editarás la evaluación</small>
                                <div class="invalid-feedback" id="error-curso-editar">
                                    Debes seleccionar un curso
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Evaluación <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_evaluacion" id="tipoEvaluacionEditar" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="satisfaccion">Satisfacción del Participante</option>
                                    <option value="instructores">Evaluación del Instructor</option>
                                    <option value="contenido">Evaluación del Contenido</option>
                                    <option value="metodologia">Evaluación de la Metodología</option>
                                    <option value="recursos">Evaluación de Recursos</option>
                                    <option value="general">Evaluación General</option>
                                </select>
                                <div class="invalid-feedback" id="error-tipo-editar">
                                    Debes seleccionar un tipo de evaluación
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Evaluación <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre_evaluacion" id="nombreEvaluacionEditar" placeholder="Se generará automáticamente al seleccionar el curso" required>
                                <small class="text-muted">Se genera automáticamente basado en el curso seleccionado</small>
                                <div class="invalid-feedback" id="error-nombre-editar">
                                    El nombre de la evaluación es requerido
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Estado del Curso</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="estadoCursoEditar" readonly>
                                    <span class="input-group-text" id="estadoCursoIconEditar">
                                        <i class="fas fa-info-circle text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Inicio del Curso</label>
                                <input type="text" class="form-control" id="fechaInicioCursoEditar" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Fin del Curso</label>
                                <input type="text" class="form-control" id="fechaFinCursoEditar" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Enlace del Formulario <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" name="enlace_formulario" id="enlaceFormularioEditar" placeholder="https://forms.google.com/..." required>
                        <small class="text-muted">Pega aquí el enlace del formulario que creaste en Google Forms, Microsoft Forms, etc.</small>
                        <div class="invalid-feedback" id="error-enlace-editar">
                            Debes proporcionar un enlace válido del formulario
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcionEditar" rows="3" placeholder="Describe el propósito de esta evaluación, qué se evalúa, etc."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_vencimiento" id="fechaVencimientoEditar" required>
                                <div class="invalid-feedback" id="error-fecha-editar">
                                    Debes seleccionar una fecha de vencimiento
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-select" name="estado" id="estadoEvaluacionEditar" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="activo">Activo</option>
                                    <option value="inactivo">Inactivo</option>
                                    <option value="borrador">Borrador</option>
                                </select>
                                <div class="invalid-feedback" id="error-estado-editar">
                                    Debes seleccionar un estado
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnActualizarEvaluacion" onclick="actualizarEvaluacion()">
                    <i class="fas fa-save me-1"></i>Actualizar Evaluación
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltrosEvaluaciones" tabindex="-1">
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
                <form id="formFiltrosEvaluaciones">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Evaluación</label>
                        <select class="form-select" name="filtro_tipo">
                            <option value="">Todos los tipos</option>
                            <option value="satisfaccion">Satisfacción</option>
                            <option value="instructores">Instructores</option>
                            <option value="practicas">Prácticas</option>
                            <option value="cursos">Cursos</option>
                            <option value="comunidad">Servicio Comunitario</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                            <option value="borrador">Borrador</option>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltrosEvaluaciones()">Limpiar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltrosEvaluaciones()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let evaluaciones = [];
    let vistaActual = 'grid';
    let cursos = []; // Array para almacenar los cursos

    // Funciones principales
    function showModal(modalId) {
        if (modalId === 'modalNuevaEvaluacion') {
            cargarCursos(); // Cargar cursos cuando se abre el modal
            // Inicializar estado del botón
            setTimeout(() => {
                verificarFormularioCompleto();
            }, 100);
        }
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cambiarVista(tipo) {
        vistaActual = tipo;
        if (tipo === 'grid') {
            document.getElementById('vistaGrid').classList.remove('d-none');
            document.getElementById('vistaLista').classList.add('d-none');
            generarVistaGrid();
        } else {
            document.getElementById('vistaGrid').classList.add('d-none');
            document.getElementById('vistaLista').classList.remove('d-none');
            generarVistaLista();
        }
    }

    function generarVistaGrid() {
        console.log('Generando vista grid con', evaluaciones.length, 'evaluaciones');
        const container = document.getElementById('vistaGrid');
        container.innerHTML = '';

        if (evaluaciones.length === 0) {
            console.log('No hay evaluaciones para mostrar');
            container.innerHTML = '<div class="col-12"><div class="alert alert-info text-center">No hay evaluaciones disponibles</div></div>';
            return;
        }

        evaluaciones.forEach(eval => {
            const card = document.createElement('div');
            card.className = 'col-md-6 col-lg-4';
            card.innerHTML = `
                <div class="card evaluation-card ${eval.tipo.toLowerCase()} h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="card-title mb-0">${eval.nombre}</h6>
                            <span class="status-badge status-${eval.estado}">${eval.estado}</span>
                        </div>
                        <p class="card-text text-muted small mb-3">${eval.descripcion}</p>
                        <div class="link-preview mb-3">
                            <i class="fas fa-link me-2"></i>${eval.enlace}
                        </div>
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <small class="text-muted">Respuestas</small>
                                <div class="fw-bold">${eval.respuestas}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Vence</small>
                                <div class="fw-bold">${formatearFecha(eval.fecha_vencimiento)}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="${eval.enlace}" target="_blank" class="btn btn-primary btn-sm flex-fill">
                                <i class="fas fa-external-link-alt me-1"></i>Abrir
                            </a>
                            <button class="btn btn-outline-info btn-sm" onclick="copiarEnlace('${eval.enlace}')" title="Copiar enlace">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="btn btn-outline-warning btn-sm" onclick="editarEvaluacion(${eval.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="eliminarEvaluacion(${eval.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function generarVistaLista() {
        const tbody = document.getElementById('tablaEvaluacionesLista');
        tbody.innerHTML = '';

        evaluaciones.forEach(eval => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="fw-semibold">${eval.nombre}</div>
                    <small class="text-muted">${eval.descripcion}</small>
                </td>
                <td><span class="badge bg-secondary">${eval.tipo}</span></td>
                <td>
                    <div class="link-preview">
                        <i class="fas fa-link me-2"></i>${eval.enlace}
                    </td>
                <td><span class="status-badge status-${eval.estado}">${eval.estado}</span></td>
                <td class="text-center">${eval.respuestas}</td>
                <td>${formatearFecha(eval.fecha_vencimiento)}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="${eval.enlace}" target="_blank" class="btn btn-primary" title="Abrir">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                        <button class="btn btn-info" onclick="copiarEnlace('${eval.enlace}')" title="Copiar enlace">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button class="btn btn-warning" onclick="editarEvaluacion(${eval.id})" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" onclick="eliminarEvaluacion(${eval.id})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function formatearFecha(fecha) {
        return new Date(fecha).toLocaleDateString('es-ES');
    }

    function copiarEnlace(enlace) {
        navigator.clipboard.writeText(enlace).then(() => {
            showNotification('Enlace copiado al portapapeles', 'success');
        });
    }

    function agregarEvaluacion() {
        // Limpiar validaciones anteriores
        limpiarValidaciones();

        // Validar formulario
        const esValido = validarFormulario();

        if (!esValido) {
            showNotification('Por favor completa todos los campos obligatorios correctamente', 'error');
            return;
        }

        const form = document.getElementById('formNuevaEvaluacion');
        const formData = new FormData(form);

        // Enviar datos al servidor
        fetch('<?= base_url('coord/evaluaciones/agregar') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalNuevaEvaluacion')).hide();
                    form.reset();
                    limpiarDatosCurso();
                    limpiarValidaciones();
                    cargarEvaluaciones();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al agregar la evaluación', 'error');
            });
    }

    function validarFormulario() {
        let esValido = true;

        // Validar curso seleccionado
        const cursoId = document.getElementById('cursoIdHidden').value;
        const selectCurso = document.getElementById('selectCurso');
        if (!cursoId || selectCurso.value === '') {
            marcarCampoInvalido(selectCurso, 'error-curso');
            esValido = false;
        } else {
            marcarCampoValido(selectCurso);
        }

        // Validar tipo de evaluación
        const tipoEvaluacion = document.getElementById('tipoEvaluacion');
        if (!tipoEvaluacion.value) {
            marcarCampoInvalido(tipoEvaluacion, 'error-tipo');
            esValido = false;
        } else {
            marcarCampoValido(tipoEvaluacion);
        }

        // Validar nombre de evaluación
        const nombreEvaluacion = document.getElementById('nombreEvaluacion');
        if (!nombreEvaluacion.value.trim()) {
            marcarCampoInvalido(nombreEvaluacion, 'error-nombre');
            esValido = false;
        } else {
            marcarCampoValido(nombreEvaluacion);
        }

        // Validar enlace del formulario
        const enlaceFormulario = document.getElementById('enlaceFormulario');
        if (!enlaceFormulario.value.trim()) {
            marcarCampoInvalido(enlaceFormulario, 'error-enlace');
            esValido = false;
        } else if (!esUrlValida(enlaceFormulario.value)) {
            marcarCampoInvalido(enlaceFormulario, 'error-enlace');
            esValido = false;
        } else {
            marcarCampoValido(enlaceFormulario);
        }

        // Validar fecha de vencimiento
        const fechaVencimiento = document.getElementById('fechaVencimiento');
        if (!fechaVencimiento.value) {
            marcarCampoInvalido(fechaVencimiento, 'error-fecha');
            esValido = false;
        } else {
            // Validar que la fecha no sea anterior a hoy
            const hoy = new Date().toISOString().split('T')[0];
            if (fechaVencimiento.value < hoy) {
                marcarCampoInvalido(fechaVencimiento, 'error-fecha');
                document.getElementById('error-fecha').textContent = 'La fecha de vencimiento no puede ser anterior a hoy';
                esValido = false;
            } else {
                marcarCampoValido(fechaVencimiento);
            }
        }

        // Validar estado
        const estadoEvaluacion = document.getElementById('estadoEvaluacion');
        if (!estadoEvaluacion.value) {
            marcarCampoInvalido(estadoEvaluacion, 'error-estado');
            esValido = false;
        } else {
            marcarCampoValido(estadoEvaluacion);
        }

        return esValido;
    }

    function marcarCampoInvalido(campo, errorId) {
        campo.classList.remove('is-valid');
        campo.classList.add('is-invalid');
        document.getElementById(errorId).style.display = 'block';
    }

    function marcarCampoValido(campo) {
        campo.classList.remove('is-invalid');
        campo.classList.add('is-valid');
    }

    function limpiarValidaciones() {
        const campos = ['selectCurso', 'tipoEvaluacion', 'nombreEvaluacion', 'enlaceFormulario', 'fechaVencimiento', 'estadoEvaluacion'];
        const errores = ['error-curso', 'error-tipo', 'error-nombre', 'error-enlace', 'error-fecha', 'error-estado'];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.classList.remove('is-valid', 'is-invalid');
            }
        });

        errores.forEach(id => {
            const error = document.getElementById(id);
            if (error) {
                error.style.display = 'none';
            }
        });
    }

    function esUrlValida(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    function editarEvaluacion(id) {
        // Cargar datos de la evaluación
        fetch(`<?= base_url('coord/evaluaciones/obtener') ?>/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const evaluacion = data.data;

                    // Llenar el formulario de edición
                    document.getElementById('evaluacionIdEditar').value = evaluacion.id;
                    document.getElementById('cursoIdEditar').value = evaluacion.curso_id;
                    document.getElementById('selectCursoEditar').value = evaluacion.curso_id;
                    document.getElementById('tipoEvaluacionEditar').value = evaluacion.tipo;
                    document.getElementById('nombreEvaluacionEditar').value = evaluacion.nombre;
                    document.getElementById('enlaceFormularioEditar').value = evaluacion.enlace;
                    document.getElementById('descripcionEditar').value = evaluacion.descripcion || '';
                    document.getElementById('fechaVencimientoEditar').value = evaluacion.fecha_vencimiento;
                    document.getElementById('estadoEvaluacionEditar').value = evaluacion.estado;

                    // Cargar datos del curso si está disponible
                    if (evaluacion.curso_id) {
                        cargarDatosCursoEditar();
                    }

                    // Limpiar validaciones
                    limpiarValidacionesEditar();

                    // Mostrar modal
                    const modal = new bootstrap.Modal(document.getElementById('modalEditarEvaluacion'));
                    modal.show();

                } else {
                    showNotification('Error al cargar los datos de la evaluación: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al cargar los datos de la evaluación', 'error');
            });
    }

    function eliminarEvaluacion(id) {
        if (true) {
            fetch(`<?= base_url('coord/evaluaciones/eliminar') ?>/${id}`, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        cargarEvaluaciones();
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error al eliminar la evaluación', 'error');
                });
        }
    }

    function aplicarFiltrosEvaluaciones() {
        const form = document.getElementById('formFiltrosEvaluaciones');
        const formData = new FormData(form);

        fetch('<?= base_url('coord/evaluaciones/filtros') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    evaluaciones = data.data;
                    if (vistaActual === 'grid') {
                        generarVistaGrid();
                    } else {
                        generarVistaLista();
                    }
                    showNotification('Filtros aplicados correctamente', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalFiltrosEvaluaciones')).hide();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al aplicar filtros', 'error');
            });
    }

    function limpiarFiltrosEvaluaciones() {
        document.getElementById('formFiltrosEvaluaciones').reset();
        cargarEvaluaciones(); // Recargar todas las evaluaciones
        showNotification('Filtros limpiados', 'info');
    }

    function generarReporteEvaluaciones() {
        // Redirigir a la página de reportes
        window.location.href = '<?= base_url('coord/reportes-evaluaciones') ?>';
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
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        // Limpiar modal cuando se cierre
        modal.addEventListener('hidden.bs.modal', () => {
            document.body.removeChild(modal);
        });
    }

    function exportarEvaluaciones() {
        showModalOpcionesExportacion();
    }

    function exportarFormato(formato) {
        // Cerrar el modal primero
        const modal = document.getElementById('modalOpcionesExportacion');
        if (modal) {
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) {
                bsModal.hide();
            }
        }

        // Ejecutar la exportación según el formato
        switch (formato) {
            case 'pdf':
                exportarPDF();
                break;
            case 'excel':
                exportarExcel();
                break;
            default:
                showNotification('Formato de exportación no válido', 'error');
        }
    }

    function exportarPDF() {
        const url = '<?= base_url('coord/reportes-evaluaciones/pdf') ?>';
        window.open(url, '_blank');
        showNotification('Generando reporte PDF...', 'info');
    }

    function exportarExcel() {
        const url = '<?= base_url('coord/reportes-evaluaciones/excel') ?>';
        window.open(url, '_blank');
        showNotification('Exportando a Excel...', 'info');
    }

    function exportarCSV() {
        const url = '<?= base_url('coord/reportes-evaluaciones/csv') ?>';
        window.open(url, '_blank');
        showNotification('Exportando a CSV...', 'info');
    }

    function actualizarEvaluacion() {
        // Limpiar validaciones anteriores
        limpiarValidacionesEditar();

        // Validar formulario
        const esValido = validarFormularioEditar();

        if (!esValido) {
            showNotification('Por favor completa todos los campos obligatorios correctamente', 'error');
            return;
        }

        const form = document.getElementById('formEditarEvaluacion');
        const formData = new FormData(form);
        const evaluacionId = document.getElementById('evaluacionIdEditar').value;

        // Enviar datos al servidor
        fetch(`<?= base_url('coord/evaluaciones/actualizar') ?>/${evaluacionId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalEditarEvaluacion')).hide();
                    form.reset();
                    limpiarDatosCursoEditar();
                    limpiarValidacionesEditar();
                    cargarEvaluaciones();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al actualizar la evaluación', 'error');
            });
    }

    function validarFormularioEditar() {
        let esValido = true;

        // Validar curso seleccionado
        const cursoId = document.getElementById('cursoIdEditar').value;
        const selectCurso = document.getElementById('selectCursoEditar');
        if (!cursoId || selectCurso.value === '') {
            marcarCampoInvalidoEditar(selectCurso, 'error-curso-editar');
            esValido = false;
        } else {
            marcarCampoValidoEditar(selectCurso);
        }

        // Validar tipo de evaluación
        const tipoEvaluacion = document.getElementById('tipoEvaluacionEditar');
        if (!tipoEvaluacion.value) {
            marcarCampoInvalidoEditar(tipoEvaluacion, 'error-tipo-editar');
            esValido = false;
        } else {
            marcarCampoValidoEditar(tipoEvaluacion);
        }

        // Validar nombre de evaluación
        const nombreEvaluacion = document.getElementById('nombreEvaluacionEditar');
        if (!nombreEvaluacion.value.trim()) {
            marcarCampoInvalidoEditar(nombreEvaluacion, 'error-nombre-editar');
            esValido = false;
        } else {
            marcarCampoValidoEditar(nombreEvaluacion);
        }

        // Validar enlace del formulario
        const enlaceFormulario = document.getElementById('enlaceFormularioEditar');
        if (!enlaceFormulario.value.trim()) {
            marcarCampoInvalidoEditar(enlaceFormulario, 'error-enlace-editar');
            esValido = false;
        } else if (!esUrlValida(enlaceFormulario.value)) {
            marcarCampoInvalidoEditar(enlaceFormulario, 'error-enlace-editar');
            esValido = false;
        } else {
            marcarCampoValidoEditar(enlaceFormulario);
        }

        // Validar fecha de vencimiento
        const fechaVencimiento = document.getElementById('fechaVencimientoEditar');
        if (!fechaVencimiento.value) {
            marcarCampoInvalidoEditar(fechaVencimiento, 'error-fecha-editar');
            esValido = false;
        } else {
            // Validar que la fecha no sea anterior a hoy
            const hoy = new Date().toISOString().split('T')[0];
            if (fechaVencimiento.value < hoy) {
                marcarCampoInvalidoEditar(fechaVencimiento, 'error-fecha-editar');
                document.getElementById('error-fecha-editar').textContent = 'La fecha de vencimiento no puede ser anterior a hoy';
                esValido = false;
            } else {
                marcarCampoValidoEditar(fechaVencimiento);
            }
        }

        // Validar estado
        const estadoEvaluacion = document.getElementById('estadoEvaluacionEditar');
        if (!estadoEvaluacion.value) {
            marcarCampoInvalidoEditar(estadoEvaluacion, 'error-estado-editar');
            esValido = false;
        } else {
            marcarCampoValidoEditar(estadoEvaluacion);
        }

        return esValido;
    }

    function marcarCampoInvalidoEditar(campo, errorId) {
        campo.classList.remove('is-valid');
        campo.classList.add('is-invalid');
        document.getElementById(errorId).style.display = 'block';
    }

    function marcarCampoValidoEditar(campo) {
        campo.classList.remove('is-invalid');
        campo.classList.add('is-valid');
    }

    function limpiarValidacionesEditar() {
        const campos = ['selectCursoEditar', 'tipoEvaluacionEditar', 'nombreEvaluacionEditar', 'enlaceFormularioEditar', 'fechaVencimientoEditar', 'estadoEvaluacionEditar'];
        const errores = ['error-curso-editar', 'error-tipo-editar', 'error-nombre-editar', 'error-enlace-editar', 'error-fecha-editar', 'error-estado-editar'];

        campos.forEach(id => {
            const campo = document.getElementById(id);
            if (campo) {
                campo.classList.remove('is-valid', 'is-invalid');
            }
        });

        errores.forEach(id => {
            const error = document.getElementById(id);
            if (error) {
                error.style.display = 'none';
            }
        });
    }

    function cargarDatosCursoEditar() {
        const selectCurso = document.getElementById('selectCursoEditar');
        const cursoId = selectCurso.value;

        if (!cursoId) {
            limpiarDatosCursoEditar();
            return;
        }

        const curso = cursos.find(c => c.ID_ACTIVIDAD_EDUCACION == cursoId);
        if (curso) {
            // Llenar campos con datos del curso
            document.getElementById('cursoIdEditar').value = curso.ID_ACTIVIDAD_EDUCACION;
            document.getElementById('estadoCursoEditar').value = curso.ESTADO;
            document.getElementById('fechaInicioCursoEditar').value = formatearFecha(curso.FECHA_INICIO);
            document.getElementById('fechaFinCursoEditar').value = formatearFecha(curso.FECHA_FIN);

            // Actualizar icono del estado
            const estadoIcon = document.getElementById('estadoCursoIconEditar');
            if (curso.ESTADO === 'activo') {
                estadoIcon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
            } else if (curso.ESTADO === 'finalizado') {
                estadoIcon.innerHTML = '<i class="fas fa-flag-checkered text-info"></i>';
            } else {
                estadoIcon.innerHTML = '<i class="fas fa-clock text-warning"></i>';
            }

            // Validar el campo curso como válido
            marcarCampoValidoEditar(selectCurso);
        }
    }

    function limpiarDatosCursoEditar() {
        document.getElementById('cursoIdEditar').value = '';
        document.getElementById('estadoCursoEditar').value = '';
        document.getElementById('fechaInicioCursoEditar').value = '';
        document.getElementById('fechaFinCursoEditar').value = '';

        // Resetear icono del estado
        const estadoIcon = document.getElementById('estadoCursoIconEditar');
        estadoIcon.innerHTML = '<i class="fas fa-info-circle text-muted"></i>';

        // Resetear select de curso
        document.getElementById('selectCursoEditar').value = '';

        // Limpiar validaciones
        limpiarValidacionesEditar();
    }

    function llenarSelectCursosEditar() {
        const select = document.getElementById('selectCursoEditar');
        select.innerHTML = '<option value="">Seleccionar curso...</option>';

        cursos.forEach(curso => {
            const option = document.createElement('option');
            option.value = curso.ID_ACTIVIDAD_EDUCACION;
            option.textContent = `${curso.NOMBRE_ACTIVIDAD} (${curso.TIPO_ACTIVIDAD})`;
            select.appendChild(option);
        });
    }

    function cargarCursos() {
        // Cargar cursos desde la base de datos
        fetch('<?= base_url('coord/evaluaciones/cursos') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cursos = data.data;
                    llenarSelectCursos();
                    llenarSelectCursosEditar(); // También llenar el select de edición
                } else {
                    console.error('Error cargando cursos:', data.message);
                    showNotification('Error al cargar cursos: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error cargando cursos:', error);
                showNotification('Error al cargar cursos desde la base de datos', 'error');
            });
    }

    function llenarSelectCursos() {
        const select = document.getElementById('selectCurso');
        select.innerHTML = '<option value="">Seleccionar curso...</option>';

        cursos.forEach(curso => {
            const option = document.createElement('option');
            option.value = curso.ID_ACTIVIDAD_EDUCACION;
            option.textContent = `${curso.NOMBRE_ACTIVIDAD} (${curso.TIPO_ACTIVIDAD})`;
            select.appendChild(option);
        });
    }

    function cargarDatosCurso() {
        const selectCurso = document.getElementById('selectCurso');
        const cursoId = selectCurso.value;

        if (!cursoId) {
            limpiarDatosCurso();
            return;
        }

        const curso = cursos.find(c => c.ID_ACTIVIDAD_EDUCACION == cursoId);
        if (curso) {
            // Llenar campos con datos del curso
            document.getElementById('cursoIdHidden').value = curso.ID_ACTIVIDAD_EDUCACION;
            document.getElementById('nombreEvaluacion').value = `Evaluación - ${curso.NOMBRE_ACTIVIDAD}`;
            document.getElementById('estadoCurso').value = curso.ESTADO;
            document.getElementById('fechaInicioCurso').value = formatearFecha(curso.FECHA_INICIO);
            document.getElementById('fechaFinCurso').value = formatearFecha(curso.FECHA_FIN);

            // Actualizar icono del estado
            const estadoIcon = document.getElementById('estadoCursoIcon');
            if (curso.ESTADO === 'activo') {
                estadoIcon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
            } else if (curso.ESTADO === 'finalizado') {
                estadoIcon.innerHTML = '<i class="fas fa-flag-checkered text-info"></i>';
            } else {
                estadoIcon.innerHTML = '<i class="fas fa-clock text-warning"></i>';
            }

            // Habilitar campos que dependen del curso
            document.getElementById('nombreEvaluacion').readOnly = false;

            // Validar el campo curso como válido
            marcarCampoValido(selectCurso);
        }
    }

    function limpiarDatosCurso() {
        document.getElementById('cursoIdHidden').value = '';
        document.getElementById('nombreEvaluacion').value = '';
        document.getElementById('estadoCurso').value = '';
        document.getElementById('fechaInicioCurso').value = '';
        document.getElementById('fechaFinCurso').value = '';
        document.getElementById('nombreEvaluacion').readOnly = true;

        // Resetear icono del estado
        const estadoIcon = document.getElementById('estadoCursoIcon');
        estadoIcon.innerHTML = '<i class="fas fa-info-circle text-muted"></i>';

        // Resetear select de curso
        document.getElementById('selectCurso').value = '';

        // Limpiar validaciones y verificar formulario
        limpiarValidaciones();
        verificarFormularioCompleto();
    }

    function cargarEvaluaciones() {
        console.log('Cargando evaluaciones...');
        fetch('<?= base_url('coord/evaluaciones/obtener') ?>')
            .then(response => {
                console.log('Respuesta recibida:', response);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    evaluaciones = data.data;
                    console.log('Evaluaciones cargadas:', evaluaciones.length, 'elementos');
                    console.log('Debug count:', data.debug_count);

                    if (vistaActual === 'grid') {
                        generarVistaGrid();
                    } else {
                        generarVistaLista();
                    }
                } else {
                    console.error('Error en respuesta:', data.message);
                }
            })
            .catch(error => {
                console.error('Error cargando evaluaciones:', error);
                // Usar datos de ejemplo si falla la carga
                evaluaciones = [{
                        id: 1,
                        nombre: 'Evaluación de Satisfacción - Cursos 2024',
                        tipo: 'Satisfacción',
                        enlace: 'https://forms.google.com/evaluacion-satisfaccion',
                        descripcion: 'Formulario para evaluar la satisfacción de los participantes en los cursos',
                        fecha_vencimiento: '2024-12-31',
                        estado: 'activo',
                        respuestas: 45,
                        fecha_creacion: '2024-01-15'
                    },
                    {
                        id: 2,
                        nombre: 'Evaluación de Instructores - Semestre 1',
                        tipo: 'Instructores',
                        enlace: 'https://forms.google.com/evaluacion-instructores',
                        descripcion: 'Evaluación del desempeño de los instructores por parte de los estudiantes',
                        fecha_vencimiento: '2024-06-30',
                        estado: 'activo',
                        respuestas: 78,
                        fecha_creacion: '2024-01-10'
                    },
                    {
                        id: 3,
                        nombre: 'Evaluación de Prácticas Preprofesionales',
                        tipo: 'Prácticas',
                        enlace: 'https://forms.google.com/evaluacion-practicas',
                        descripcion: 'Evaluación de las prácticas preprofesionales por parte de las entidades receptoras',
                        fecha_vencimiento: '2024-08-31',
                        estado: 'activo',
                        respuestas: 23,
                        fecha_creacion: '2024-01-20'
                    }
                ];
                generarVistaGrid();
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

    // Función para cargar estadísticas
    function cargarEstadisticas() {
        fetch('<?= base_url('coord/evaluaciones/estadisticas') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('totalEvaluaciones').textContent = data.data.total;
                    document.getElementById('evaluacionesActivas').textContent = data.data.activas;
                    document.getElementById('totalRespuestas').textContent = data.data.total_respuestas;
                    document.getElementById('promedioRespuestas').textContent = data.data.promedio_respuestas;
                }
            })
            .catch(error => {
                console.error('Error cargando estadísticas:', error);
            });
    }

    // Verificar si el formulario está completo
    function verificarFormularioCompleto() {
        const cursoId = document.getElementById('cursoIdHidden').value;
        const tipoEvaluacion = document.getElementById('tipoEvaluacion').value;
        const nombreEvaluacion = document.getElementById('nombreEvaluacion').value.trim();
        const enlaceFormulario = document.getElementById('enlaceFormulario').value.trim();
        const fechaVencimiento = document.getElementById('fechaVencimiento').value;
        const estadoEvaluacion = document.getElementById('estadoEvaluacion').value;

        const esCompleto = cursoId &&
            tipoEvaluacion &&
            nombreEvaluacion &&
            enlaceFormulario &&
            esUrlValida(enlaceFormulario) &&
            fechaVencimiento &&
            estadoEvaluacion;

        const btnGuardar = document.getElementById('btnGuardarEvaluacion');
        if (btnGuardar) {
            if (esCompleto) {
                btnGuardar.disabled = false;
                btnGuardar.classList.remove('btn-secondary');
                btnGuardar.classList.add('btn-primary');
            } else {
                btnGuardar.disabled = true;
                btnGuardar.classList.remove('btn-primary');
                btnGuardar.classList.add('btn-secondary');
            }
        }
    }

    // Agregar validaciones en tiempo real
    function agregarValidacionesEnTiempoReal() {
        // Validar enlace mientras se escribe
        const enlaceFormulario = document.getElementById('enlaceFormulario');
        if (enlaceFormulario) {
            enlaceFormulario.addEventListener('input', function() {
                if (this.value.trim()) {
                    if (esUrlValida(this.value)) {
                        marcarCampoValido(this);
                    } else {
                        marcarCampoInvalido(this, 'error-enlace');
                    }
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
                verificarFormularioCompleto();
            });
        }

        // Validar fecha de vencimiento
        const fechaVencimiento = document.getElementById('fechaVencimiento');
        if (fechaVencimiento) {
            fechaVencimiento.addEventListener('change', function() {
                if (this.value) {
                    const hoy = new Date().toISOString().split('T')[0];
                    if (this.value < hoy) {
                        marcarCampoInvalido(this, 'error-fecha');
                        document.getElementById('error-fecha').textContent = 'La fecha de vencimiento no puede ser anterior a hoy';
                    } else {
                        marcarCampoValido(this);
                    }
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
                verificarFormularioCompleto();
            });
        }

        // Validar nombre de evaluación
        const nombreEvaluacion = document.getElementById('nombreEvaluacion');
        if (nombreEvaluacion) {
            nombreEvaluacion.addEventListener('input', function() {
                if (this.value.trim()) {
                    marcarCampoValido(this);
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
                verificarFormularioCompleto();
            });
        }

        // Validar tipo de evaluación
        const tipoEvaluacion = document.getElementById('tipoEvaluacion');
        if (tipoEvaluacion) {
            tipoEvaluacion.addEventListener('change', function() {
                if (this.value) {
                    marcarCampoValido(this);
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
                verificarFormularioCompleto();
            });
        }

        // Validar estado
        const estadoEvaluacion = document.getElementById('estadoEvaluacion');
        if (estadoEvaluacion) {
            estadoEvaluacion.addEventListener('change', function() {
                if (this.value) {
                    marcarCampoValido(this);
                } else {
                    this.classList.remove('is-valid', 'is-invalid');
                }
                verificarFormularioCompleto();
            });
        }

        // Validar curso
        const selectCurso = document.getElementById('selectCurso');
        if (selectCurso) {
            selectCurso.addEventListener('change', function() {
                verificarFormularioCompleto();
            });
        }
    }

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vista de evaluaciones cargada');
        cargarEvaluaciones();
        cargarEstadisticas();
        agregarValidacionesEnTiempoReal();
    });
</script>
<?= $this->endSection() ?>
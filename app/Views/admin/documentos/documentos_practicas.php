<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para documentos de prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .documento-card {
        transition: all 0.3s ease;
        border-left: 4px solid #28a745;
    }
    .documento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .estado-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .tipo-documento-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 8px 8px 0 0;
    }
    .filtros-rapidos {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .estadistica-card {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }
    .estadistica-card:hover {
        transform: scale(1.05);
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
                    Gestión de Documentos de Prácticas Preprofesionales
                </h3>
                <p class="text-center text-muted">Administra todos los documentos de prácticas de los estudiantes clasificados por tipo</p>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card estadistica-card text-center shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalDocumentosPracticas" style="font-size:2.5rem;"><?= $estadisticas['total'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Documentos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #fff;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosAprobadosPracticas" style="font-size:2.5rem;"><?= $estadisticas['aprobados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: #fff;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosPendientesPracticas" style="font-size:2.5rem;"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); color: #fff;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosRechazadosPracticas" style="font-size:2.5rem;"><?= $estadisticas['rechazados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #ffe0e0;">Rechazados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Rápidos -->
        <div class="filtros-rapidos">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filtrar por Estado:</label>
                    <select class="form-select" id="filtroEstado" onchange="aplicarFiltros()">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Revisión">En Revisión</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                        <option value="Requiere Corrección">Requiere Corrección</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filtrar por Tipo:</label>
                    <select class="form-select" id="filtroTipo" onchange="aplicarFiltros()">
                        <option value="">Todos los tipos</option>
                        <?php if (isset($tiposDocumentos)): ?>
                            <?php foreach ($tiposDocumentos as $tipo): ?>
                                <option value="<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Buscar Estudiante:</label>
                    <input type="text" class="form-control" id="buscarEstudiante" placeholder="Nombre o cédula..." onkeyup="aplicarFiltros()">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary" onclick="limpiarFiltros()">
                            <i class="fas fa-times me-1"></i>Limpiar
                        </button>
                        <button class="btn btn-primary" onclick="generarReportePracticas()">
                            <i class="fas fa-download me-1"></i>Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalSubirDocumentoPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #28a745;"></i>
                            <div class="fw-bold">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="revisionMasiva()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-tasks fa-2x mb-2" style="color: #007bff;"></i>
                            <div class="fw-bold">Revisión Masiva</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="cambiarVista('grid')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-th-large fa-2x mb-2" style="color: #6f42c1;"></i>
                            <div class="fw-bold">Vista Grid</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="cambiarVista('list')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-list fa-2x mb-2" style="color: #fd7e14;"></i>
                            <div class="fw-bold">Vista Lista</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista Grid de Documentos por Tipo -->
        <div id="vistaGrid">
            <?php if (!empty($tiposDocumentos)): ?>
                <?php foreach ($tiposDocumentos as $tipo): ?>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="tipo-documento-header p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">
                                                <i class="fas fa-file-alt me-2"></i>
                                                <?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?>
                                            </h5>
                                            <small class="opacity-75"><?= $tipo['DESCRIPCION'] ?></small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark">
                                                <?= $tipo['REQUERIDO'] ? 'Obligatorio' : 'Opcional' ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="documentos-<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>">
                                        <!-- Los documentos de este tipo se cargarán aquí -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay tipos de documentos configurados
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Vista Lista -->
        <div id="vistaLista" class="d-none">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-list me-2"></i>
                                Lista de Documentos de Prácticas
                            </span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light btn-sm" onclick="cambiarVista('grid')">
                                    <i class="fas fa-th-large me-1"></i>Grid
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Tipo de Documento</th>
                                            <th>Estado</th>
                                            <th>Fecha Subida</th>
                                            <th>Entidad Receptora</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDocumentosLista">
                                        <!-- Los documentos se cargarán dinámicamente aquí -->
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

<!-- Modal Subir Documento de Práctica -->
<div class="modal fade" id="modalSubirDocumentoPractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Subir Documento de Práctica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumentoPractica">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Documento</label>
                                <select class="form-select" name="tipo_documento" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <?php if (isset($tipos_documentos)): ?>
                                        <?php foreach ($tipos_documentos as $tipo): ?>
                                            <option value="<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estudiante</label>
                                <select class="form-select" name="estudiante" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <?php if (isset($estudiantes)): ?>
                                        <?php foreach ($estudiantes as $estudiante): ?>
                                            <option value="<?= $estudiante['ID_ESTUDIANTE'] ?>"><?= $estudiante['NOMBRE_COMPLETO'] ?> - <?= $estudiante['CEDULA'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Entidad Receptora</label>
                                <input type="text" class="form-control" name="entidad_receptora" placeholder="Ej: Instituto Tecnológico Superior Ibarra" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Docente Tutor</label>
                                <select class="form-select" name="docente_tutor" required>
                                    <option value="">Seleccionar docente tutor...</option>
                                    <option value="1">Dr. Mario Montenegro - Rector</option>
                                    <option value="2">Ing. Juan Pérez - Coordinador</option>
                                    <option value="3">Mg. María González - Tutora</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <div class="upload-card p-4 text-center" id="uploadAreaPractica">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Arrastra y suelta archivos aquí</h5>
                            <p class="text-muted mb-3">o</p>
                            <input type="file" class="form-control" name="archivo" id="archivoInputPractica" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" required>
                            <small class="text-muted">Máximo 50 MB. Formatos: PDF, DOC, XLS, JPG, ZIP</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado de Revisión</label>
                                <select class="form-select" name="estado_revision" required>
                                    <option value="">Seleccionar estado...</option>
                                    <?php if (isset($estados_revision)): ?>
                                        <?php foreach ($estados_revision as $estado): ?>
                                            <option value="<?= $estado['ID_ESTADO_REVISION'] ?>"><?= $estado['ESTADO'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prioridad</label>
                                <select class="form-select" name="prioridad" required>
                                    <option value="">Seleccionar prioridad...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones del Administrador</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales sobre el documento, estado de revisión, correcciones necesarias..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="subirDocumentoPractica()">
                    <i class="fas fa-upload me-1"></i>Subir Documento
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltrosPracticas" tabindex="-1">
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
                <form id="formFiltrosPracticas">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select class="form-select" name="filtro_tipo_documento">
                            <option value="">Todos los tipos</option>
                            <?php if (isset($tipos_documentos)): ?>
                                <?php foreach ($tipos_documentos as $tipo): ?>
                                    <option value="<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado de Revisión</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <?php if (isset($estados_revision)): ?>
                                <?php foreach ($estados_revision as $estado): ?>
                                    <option value="<?= $estado['ID_ESTADO_REVISION'] ?>"><?= $estado['ESTADO'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Docente Tutor</label>
                        <select class="form-select" name="filtro_docente">
                            <option value="">Todos los docentes</option>
                            <option value="1">Dr. Mario Montenegro - Rector</option>
                            <option value="2">Ing. Juan Pérez - Coordinador</option>
                            <option value="3">Mg. María González - Tutora</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Entidad Receptora</label>
                        <input type="text" class="form-control" name="filtro_entidad" placeholder="Buscar por entidad...">
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
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltrosPracticas()">Limpiar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltrosPracticas()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cambiar Estado del Documento -->
<div class="modal fade" id="modalCambiarEstado" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Cambiar Estado del Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formCambiarEstado">
                    <input type="hidden" name="documento_id" id="documento_id_estado">
                    <div class="mb-3">
                        <label class="form-label">Documento</label>
                        <input type="text" class="form-control" id="nombre_documento_estado" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nuevo Estado</label>
                        <select class="form-select" name="nuevo_estado" required>
                            <option value="">Seleccionar nuevo estado...</option>
                            <?php if (isset($estados_revision)): ?>
                                <?php foreach ($estados_revision as $estado): ?>
                                    <option value="<?= $estado['ID_ESTADO_REVISION'] ?>"><?= $estado['ESTADO'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios del Administrador</label>
                        <textarea class="form-control" name="comentarios_estado" rows="3" placeholder="Comentarios sobre el cambio de estado, correcciones necesarias, etc..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCambioEstado()">
                    <i class="fas fa-save me-1"></i>Guardar Cambio
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Variables globales
    let documentosPracticas = [];
    let vistaActual = 'grid';

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cambiarVista(tipo) {
        vistaActual = tipo;
        if (tipo === 'grid') {
            document.getElementById('vistaGrid').classList.remove('d-none');
            document.getElementById('vistaLista').classList.add('d-none');
            cargarDocumentosGrid();
        } else {
            document.getElementById('vistaGrid').classList.add('d-none');
            document.getElementById('vistaLista').classList.remove('d-none');
            generarVistaLista();
        }
    }

    function cargarDocumentosGrid() {
        // Cargar documentos para cada tipo
        fetch('<?= base_url('admin/documentos/practicas/obtenerDocumentos') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    documentosPracticas = data.documentos;
                    mostrarDocumentosPorTipo();
                } else {
                    showNotification('Error al cargar documentos: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al cargar documentos', 'error');
            });
    }

    function mostrarDocumentosPorTipo() {
        const tiposDocumentos = <?= json_encode($tiposDocumentos ?? []) ?>;
        
        tiposDocumentos.forEach(tipo => {
            const contenedor = document.getElementById(`documentos-${tipo.ID_TIPO_DOCUMENTO_PREPROFESIONAL}`);
            if (contenedor) {
                contenedor.innerHTML = '';
                
                // Filtrar documentos de este tipo
                const documentosTipo = documentosPracticas.filter(doc => 
                    doc.ID_TIPO_DOCUMENTO_PREPROFESIONAL == tipo.ID_TIPO_DOCUMENTO_PREPROFESIONAL
                );
                
                if (documentosTipo.length === 0) {
                    contenedor.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <div class="alert alert-light">
                                <i class="fas fa-inbox me-2"></i>
                                No hay documentos subidos para este tipo
                            </div>
                        </div>
                    `;
                } else {
                    documentosTipo.forEach(doc => {
                        const documentoCard = crearCardDocumento(doc);
                        contenedor.appendChild(documentoCard);
                    });
                }
            }
        });
    }

    function crearCardDocumento(doc) {
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4 mb-3';
        
        const estadoClass = obtenerClaseEstado(doc.ESTADO_REVISION);
        const fecha = new Date(doc.FECHA_SUBIDA).toLocaleDateString('es-ES');
        
        col.innerHTML = `
            <div class="card documento-card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}</h6>
                        <span class="badge ${estadoClass} estado-badge">${doc.ESTADO_REVISION}</span>
                    </div>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-file me-1"></i>${doc.NOMBRE_ARCHIVO}
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <i class="fas fa-building me-1"></i>${doc.ENTIDAD_RECEPTORA || 'No especificada'}
                    </p>
                    <p class="card-text text-muted small mb-3">
                        <i class="fas fa-calendar me-1"></i>${fecha}
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">${doc.CEDULA_ESTUDIANTE}</small>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" onclick="verDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Descargar">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Cambiar Estado">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        return col;
    }

    function generarVistaLista() {
        const tbody = document.getElementById('tablaDocumentosLista');
        tbody.innerHTML = '';

        if (documentosPracticas.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay documentos para mostrar
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        documentosPracticas.forEach(doc => {
            const estadoClass = obtenerClaseEstado(doc.ESTADO_REVISION);
            const fecha = new Date(doc.FECHA_SUBIDA).toLocaleDateString('es-ES');
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="file-icon bg-primary me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}</div>
                            <small class="text-muted">${doc.CEDULA_ESTUDIANTE}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div class="fw-semibold">${doc.TIPO_DOCUMENTO_NOMBRE}</div>
                        <small class="text-muted">${doc.NOMBRE_ARCHIVO}</small>
                    </div>
                </td>
                <td><span class="badge ${estadoClass} estado-badge">${doc.ESTADO_REVISION}</span></td>
                <td>${fecha}</td>
                <td>${doc.ENTIDAD_RECEPTORA || 'No especificada'}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-primary" onclick="verDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Descargar">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Cambiar Estado">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-danger" onclick="eliminarDocumento(${doc.ID_DOCUMENTO_PRACTICA})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function obtenerClaseEstado(estado) {
        switch (estado) {
            case 'Aprobado': return 'bg-success text-white';
            case 'Rechazado': return 'bg-danger text-white';
            case 'En Revisión': return 'bg-info text-white';
            case 'Requiere Corrección': return 'bg-warning text-dark';
            case 'Pendiente': return 'bg-secondary text-white';
            default: return 'bg-secondary text-white';
        }
    }

    function aplicarFiltros() {
        const filtroEstado = document.getElementById('filtroEstado').value;
        const filtroTipo = document.getElementById('filtroTipo').value;
        const buscarEstudiante = document.getElementById('buscarEstudiante').value.toLowerCase();
        
        let documentosFiltrados = [...documentosPracticas];
        
        if (filtroEstado) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ESTADO_REVISION === filtroEstado);
        }
        
        if (filtroTipo) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ID_TIPO_DOCUMENTO_PREPROFESIONAL == filtroTipo);
        }
        
        if (buscarEstudiante) {
            documentosFiltrados = documentosFiltrados.filter(doc => 
                doc.NOMBRE_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.APELLIDO_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.CEDULA_ESTUDIANTE.includes(buscarEstudiante)
            );
        }
        
        // Actualizar la vista con los documentos filtrados
        const documentosOriginales = documentosPracticas;
        documentosPracticas = documentosFiltrados;
        
        if (vistaActual === 'grid') {
            mostrarDocumentosPorTipo();
        } else {
            generarVistaLista();
        }
        
        // Restaurar documentos originales para futuros filtros
        documentosPracticas = documentosOriginales;
    }

    function limpiarFiltros() {
        document.getElementById('filtroEstado').value = '';
        document.getElementById('filtroTipo').value = '';
        document.getElementById('buscarEstudiante').value = '';
        
        if (vistaActual === 'grid') {
            cargarDocumentosGrid();
        } else {
            generarVistaLista();
        }
    }

    function verDocumento(id) {
        // Abrir el documento en una nueva ventana
        window.open(`<?= base_url('admin/documentos/practicas/ver') ?>/${id}`, '_blank');
    }

    function descargarDocumento(id) {
        // Descargar el documento
        window.location.href = `<?= base_url('admin/documentos/practicas/download') ?>/${id}`;
    }

    function eliminarDocumento(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
            fetch(`<?= base_url('admin/documentos/practicas/eliminar') ?>/${id}`, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Recargar la página para mostrar los cambios
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al eliminar el documento', 'error');
            });
        }
    }

    function subirDocumentoPractica() {
        const form = document.getElementById('formSubirDocumentoPractica');
        const formData = new FormData(form);
        
        // Agregar el archivo al FormData
        const archivo = document.getElementById('archivoInputPractica').files[0];
        if (archivo) {
            formData.append('archivo', archivo);
        }
        
        fetch('<?= base_url('admin/documentos/practicas/store') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumentoPractica')).hide();
                form.reset();
                // Recargar la página para mostrar los nuevos datos
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al subir el documento', 'error');
        });
    }

    function aplicarFiltrosPracticas() {
        const form = document.getElementById('formFiltrosPracticas');
        const formData = new FormData(form);
        
        fetch('<?= base_url('admin/documentos/practicas/filtros') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Filtros aplicados correctamente', 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalFiltrosPracticas')).hide();
                // Aquí podrías actualizar la vista con los datos filtrados
                console.log('Documentos filtrados:', data.data);
            } else {
                showNotification('Error al aplicar filtros', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al aplicar filtros', 'error');
        });
    }

    function limpiarFiltrosPracticas() {
        document.getElementById('formFiltrosPracticas').reset();
        showNotification('Filtros limpiados', 'info');
    }

    function exportarDocumentosPracticas() {
        showNotification('Exportando documentos...', 'info');
    }

    function generarReportePracticas() {
        // Redirigir a la vista de reportes en la misma ventana
        window.location.href = '<?= base_url('admin/documentos/practicas/reportes') ?>';
    }

    function cambiarEstadoDocumento(id) {
        // Simular obtención de datos del documento
        document.getElementById('documento_id_estado').value = id;
        document.getElementById('nombre_documento_estado').value = 'Informe_Final_Practica.pdf';
        
        // Mostrar modal
        showModal('modalCambiarEstado');
    }

    function guardarCambioEstado() {
        const nuevoEstado = document.querySelector('select[name="nuevo_estado"]').value;
        const comentarios = document.querySelector('textarea[name="comentarios_estado"]').value;
        const documentoId = document.getElementById('documento_id_estado').value;
        
        if (!nuevoEstado) {
            showNotification('Debe seleccionar un nuevo estado', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('estado', nuevoEstado);
        formData.append('observaciones_revisor', comentarios);
        
        fetch(`<?= base_url('admin/documentos/practicas/cambiar-estado') ?>/${documentoId}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                document.getElementById('formCambiarEstado').reset();
                // Recargar la página para mostrar los cambios
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al cambiar el estado', 'error');
        });
    }

    function revisionMasiva() {
        showNotification('Función de revisión masiva en desarrollo. Permite cambiar el estado de múltiples documentos a la vez.', 'info');
    }

    function subirDocumento(id) {
        // Simular subida de documento
        showNotification(`Documento ${id} subido exitosamente`, 'success');
        // Recargar la vista de lista
        generarVistaLista();
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

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vista de documentos de prácticas cargada');
        
        // Cargar documentos inicialmente
        cargarDocumentosGrid();
        
        // Configurar drag and drop para el modal de subida
        const uploadArea = document.getElementById('uploadAreaPractica');
        const archivoInput = document.getElementById('archivoInputPractica');

        if (uploadArea) {
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    archivoInput.files = files;
                    const event = new Event('change', { bubbles: true });
                    archivoInput.dispatchEvent(event);
                }
            });
        }

        if (archivoInput) {
            archivoInput.addEventListener('change', function(e) {
                if (this.files.length > 0) {
                    const file = this.files[0];
                    const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                    
                    uploadArea.innerHTML = `
                        <i class="fas fa-file fa-3x text-primary mb-3"></i>
                        <h5 class="text-primary">${file.name}</h5>
                        <p class="text-muted mb-2">Tamaño: ${fileSize} MB</p>
                        <small class="text-muted">Archivo seleccionado correctamente</small>
                    `;
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
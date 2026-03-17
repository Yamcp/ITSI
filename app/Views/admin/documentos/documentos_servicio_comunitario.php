<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para documentos de servicio comunitario -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .documento-card {
        transition: all 0.3s ease;
        border-left: 4px solid #17a2b8;
    }

    .documento-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .estado-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .tipo-documento-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
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
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .estadistica-card:hover {
        transform: scale(1.05);
    }

    .table-responsive {
        font-size: 0.9rem;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: white;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }

    .form-select option {
        padding: 8px 12px;
    }

    .form-select option.text-success {
        background-color: #d4edda;
        color: #155724;
    }

    .form-select option.text-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .form-select option.text-info {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .form-select option.text-warning {
        background-color: #fff3cd;
        color: #856404;
    }

    .form-select option.text-secondary {
        background-color: #e2e3e5;
        color: #383d41;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Volver a Prácticas Asignadas -->
        <div class="row mb-2">
            <div class="col-12">
                <a href="<?= base_url('admin/practicas') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Volver a Prácticas Asignadas
                </a>
            </div>
        </div>
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-hands-helping me-2"></i>
                    Documentos - Servicio Comunitario
                </h3>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="Aprobados" style="font-size:2.5rem; color: #fff; text-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);"><?= $estadisticas['Aprobados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fff;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="Pendientes" style="font-size:2.5rem; color: #fff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fff;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="RequiereCorreccion" style="font-size:2.5rem; color: #fff; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"><?= $estadisticas['requiere_correccion'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fff;">Requiere Corrección</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="Rechazados" style="font-size:2.5rem; color: #fff; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"><?= $estadisticas['rechazados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold" style="color: #fff;">Rechazados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalSubirDocumentoServicio')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #17a2b8; text-shadow: 0 2px 4px rgba(23, 162, 184, 0.3);"></i>
                            <div class="fw-bold">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="revisionMasiva()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-tasks fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Revisión Masiva</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporteServicio()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="<?= base_url('admin/documentos/servicio/reportes') ?>" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs (igual que en Documentos Preprofesionales) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="documentosTabsServicio" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="documentos-tipo-tab-servicio" data-bs-toggle="tab" data-bs-target="#documentos-por-tipo-servicio" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-folder-open me-2"></i>Documentos por tipo
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="formatos-docs-tab-servicio" data-bs-toggle="tab" data-bs-target="#formatos-documentos-servicio" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-file-upload me-2"></i>Documentos de formato
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <div class="tab-content mt-3" id="documentosTabContentServicio">
                            <!-- Pestaña: Documentos por tipo -->
                            <div class="tab-pane fade show active" id="documentos-por-tipo-servicio" role="tabpanel">
                                <!-- Filtros rápidos -->
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <span class="fw-semibold"><i class="fas fa-filter me-1"></i>Filtros</span>
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <select class="form-select form-select-sm" id="filtroEstado" onchange="aplicarFiltros()" style="width: auto;">
                                                <option value="">Todos los estados</option>
                                                <option value="Pendiente">Pendiente</option>
                                                <option value="En Revisión">En Revisión</option>
                                                <option value="Aprobado">Aprobado</option>
                                                <option value="Rechazado">Rechazado</option>
                                                <option value="Requiere Corrección">Requiere Corrección</option>
                                            </select>
                                            <select class="form-select form-select-sm" id="filtroTipo" onchange="aplicarFiltros()" style="width: auto;">
                                                <option value="">Todos los tipos</option>
                                                <?php if (isset($tiposDocumentos)): ?>
                                                    <?php foreach ($tiposDocumentos as $tipo): ?>
                                                        <option value="<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                            <input type="text" class="form-control form-control-sm" id="buscarEstudiante" placeholder="Estudiante o cédula..." onkeyup="aplicarFiltros()" style="width: 180px;">
                                            <button class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros()"><i class="fas fa-times me-1"></i>Limpiar</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tablas por tipo -->
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
                                                                        <i class="fas fa-hands-helping me-2"></i>
                                                                        <?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?>
                                                                    </h5>
                                                                    <small class="opacity-75"><?= $tipo['DESCRIPCION'] ?></small>
                                                                </div>
                                                                <div class="text-end">
                                                                    <span class="badge bg-light text-dark">
                                                                        <?= $tipo['OBLIGATORIO'] ? 'Obligatorio' : 'Opcional' ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="table-responsive">
                                                                <table class="table table-hover mb-0" id="tabla-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th width="5%">#</th>
                                                                            <th width="20%">Estudiante</th>
                                                                            <th width="15%">Cédula</th>
                                                                            <th width="20%">Proyecto Social</th>
                                                                            <th width="15%">Archivo</th>
                                                                            <th width="10%">Estado</th>
                                                                            <th width="10%">Fecha</th>
                                                                            <th width="15%">Acciones</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="documentos-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                                        <!-- Los documentos de este tipo se cargarán aquí -->
                                                                    </tbody>
                                                                </table>
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
                            </div>

                            <!-- Pestaña: Documentos de formato (Servicio Comunitario) -->
                            <div class="tab-pane fade" id="formatos-documentos-servicio" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-file-upload me-2"></i>Documentos de formato – Servicio Comunitario</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">Estos documentos se muestran en el perfil del estudiante (Servicio Comunitario) para que puedan descargarlos.</p>
                                        <div class="row mb-4">
                                            <div class="col-md-8">
                                                <form id="formDocumentoFormatoServicio" enctype="multipart/form-data">
                                                    <div class="row g-2">
                                                        <div class="col-md-5">
                                                            <label class="form-label fw-bold">Nombre del documento</label>
                                                            <input type="text" class="form-control" name="nombre" placeholder="Ej: Modelo informe servicio comunitario" required />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label fw-bold">Archivo (PDF, DOC, DOCX)</label>
                                                            <input type="file" class="form-control" name="documento" id="docFormatoServicio" accept=".pdf,.doc,.docx" required />
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="submit" class="btn btn-success w-100" id="btnSubirDocFormatoServicio">
                                                                <i class="fas fa-upload me-1"></i> Subir
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th>Archivo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodyFormatosServicio">
                                                    <?php
                                                    $docsFormatosServ = $documentos_formatos_servicio ?? [];
                                                    foreach ($docsFormatosServ as $i => $item): ?>
                                                        <tr>
                                                            <td><?= $i + 1 ?></td>
                                                            <td><?= esc($item['nombre'] ?? '') ?></td>
                                                            <td><span class="text-muted small"><?= esc($item['archivo'] ?? '') ?></span></td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocFormatoServicio('<?= esc($item['archivo'] ?? '') ?>')" title="Eliminar">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($docsFormatosServ)): ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-3">No hay documentos. Suba uno arriba.</td>
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

<!-- Modal para formulario para crear nuevo tipo de servicio comunitario -->
<div class="modal fade" id="modalSubirDocumentoServicio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Crear Nuevo Tipo de Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumentoServicio">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" id="nuevo_codigo" placeholder="Ej: PSC-013" pattern="PSC-\d{3}">
                                    <div class="form-text">Formato: PSC-XXX (ej: PSC-013)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Documento</label>
                                    <input type="text" class="form-control" id="nuevo_nombre" placeholder="Ej: Informe de Impacto Social">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control" id="nuevo_descripcion" rows="2" placeholder="Descripción detallada del tipo de documento..."></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Orden</label>
                                    <input type="number" class="form-control" id="nuevo_orden" min="1" max="99" placeholder="13">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo</label>
                                    <select class="form-select" id="nuevo_obligatorio">
                                        <option value="1">Obligatorio</option>
                                        <option value="0">Opcional</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success" onclick="crearNuevoTipo()">
                                <i class="fas fa-save me-1"></i>Crear Tipo
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="limpiarFormularioNuevoTipo()">
                                <i class="fas fa-times me-1"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </form>
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
                        <select class="form-select" name="nuevo_estado" id="selectNuevoEstado" required>
                            <option value="">Seleccionar nuevo estado...</option>
                            <option value="1">Aprobado</option>
                            <option value="2">Rechazado</option>
                            <option value="4">Requiere Corrección</option>
                            <option value="5">Pendiente</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios del Coordinador</label>
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

<!-- Modal Ver Documento -->
<div class="modal fade" id="modalVerDocumento" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-eye me-2"></i>
                    Visualizar Documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe
                    id="iframeDocumento"
                    src=""
                    style="width: 100%; height: 70vh; border: none;"
                    title="Vista previa del documento">
                </iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="descargarDesdeModal()">
                    <i class="fas fa-download me-1"></i>Descargar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let documentosServicio = [];
    let documentoActualId = null;

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cargarDocumentosGrid() {
        console.log('Iniciando carga de documentos...');
        // Cargar documentos para cada tipo
        fetch('<?= base_url('admin/documentos/servicio/obtenerDocumentos') ?>')
            .then(response => {
                console.log('Respuesta recibida:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    documentosServicio = data.documentos || data.data || [];
                    console.log('Documentos cargados:', documentosServicio);
                    console.log('Cantidad de documentos:', documentosServicio.length);
                    mostrarDocumentosPorTipo();
                } else {
                    console.error('Error en respuesta:', data.message);
                    showNotification('Error al cargar documentos: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                showNotification('Error al cargar documentos: ' + error.message, 'error');
            });
    }

    function mostrarDocumentosPorTipo() {
        const tiposDocumentos = <?= json_encode($tiposDocumentos ?? []) ?>;
        console.log('Tipos de documentos disponibles:', tiposDocumentos);
        console.log('Documentos a procesar:', documentosServicio);

        tiposDocumentos.forEach(tipo => {
            console.log(`Procesando tipo: ${tipo.CODIGO} - ${tipo.NOMBRE}`);
            const contenedor = document.getElementById(`documentos-${tipo.ID_TIPO_DOCUMENTO_SERVICIO}`);
            if (contenedor) {
                contenedor.innerHTML = '';

                // Filtrar documentos de este tipo
                const documentosTipo = documentosServicio.filter(doc =>
                    doc.ID_TIPO_DOCUMENTO == tipo.ID_TIPO_DOCUMENTO_SERVICIO
                );

                console.log(`Documentos encontrados para tipo ${tipo.CODIGO}:`, documentosTipo.length);

                if (documentosTipo.length === 0) {
                    contenedor.innerHTML = `
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="alert alert-light mb-0">
                                    <i class="fas fa-inbox me-2"></i>
                                    No hay documentos subidos para este tipo
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    documentosTipo.forEach((doc, index) => {
                        const filaTabla = crearFilaTabla(doc, index + 1);
                        contenedor.appendChild(filaTabla);
                    });
                }
            } else {
                console.error(`No se encontró el contenedor para tipo ${tipo.ID_TIPO_DOCUMENTO_SERVICIO}`);
            }
        });
    }

    function crearFilaTabla(doc, numero) {
        const fila = document.createElement('tr');

        const estadoInfo = obtenerEstadoInfo(doc.ESTADO_REVISION);
        const fecha = new Date(doc.FECHA_SUBIDA).toLocaleDateString('es-ES');

        fila.innerHTML = `
            <td class="text-center">${numero}</td>
            <td>
                <div class="fw-bold">${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}</div>
            </td>
            <td>
                <span class="text-muted">${doc.CEDULA_ESTUDIANTE}</span>
            </td>
            <td>
                <div class="text-truncate" style="max-width: 200px;" title="${doc.PROYECTO_SOCIAL || 'No especificado'}">
                    <i class="fas fa-project-diagram me-1 text-muted"></i>
                    ${doc.PROYECTO_SOCIAL || 'No especificado'}
                </div>
            </td>
            <td>
                <div class="text-truncate" style="max-width: 150px;" title="${doc.NOMBRE_ARCHIVO}">
                    <i class="fas fa-file me-1 text-muted"></i>
                    ${doc.NOMBRE_ARCHIVO}
                </div>
            </td>
            <td class="text-center">
                <span class="badge ${estadoInfo.clase} estado-badge">${estadoInfo.texto}</span>
            </td>
            <td class="text-center">
                <small class="text-muted">${fecha}</small>
            </td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="verDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Descargar">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.ID_DOCUMENTO_SERVICIO})" title="Cambiar Estado">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;

        return fila;
    }

    function obtenerEstadoInfo(estado) {
        // Mapeo de estados (tanto números como texto)
        const estadosMap = {
            '1': {
                texto: 'Aprobado',
                clase: 'bg-success text-white'
            },
            '2': {
                texto: 'Rechazado',
                clase: 'bg-danger text-white'
            },
            '4': {
                texto: 'Requiere Corrección',
                clase: 'bg-warning text-dark'
            },
            '5': {
                texto: 'Pendiente',
                clase: 'bg-secondary text-white'
            },
            'Aprobado': {
                texto: 'Aprobado',
                clase: 'bg-success text-white'
            },
            'Rechazado': {
                texto: 'Rechazado',
                clase: 'bg-danger text-white'
            },
            'Requiere Corrección': {
                texto: 'Requiere Corrección',
                clase: 'bg-warning text-dark'
            },
            'Pendiente': {
                texto: 'Pendiente',
                clase: 'bg-secondary text-white'
            }
        };

        return estadosMap[estado] || {
            texto: 'Desconocido',
            clase: 'bg-secondary text-white'
        };
    }

    function obtenerClaseEstado(estado) {
        return obtenerEstadoInfo(estado).clase;
    }

    function aplicarFiltros() {
        const filtroEstado = document.getElementById('filtroEstado').value;
        const filtroTipo = document.getElementById('filtroTipo').value;
        const buscarEstudiante = document.getElementById('buscarEstudiante').value.toLowerCase();

        let documentosFiltrados = [...documentosServicio];

        if (filtroEstado) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ESTADO_REVISION === filtroEstado);
        }

        if (filtroTipo) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ID_TIPO_DOCUMENTO == filtroTipo);
        }

        if (buscarEstudiante) {
            documentosFiltrados = documentosFiltrados.filter(doc =>
                doc.NOMBRE_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.APELLIDO_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.CEDULA_ESTUDIANTE.includes(buscarEstudiante)
            );
        }

        // Actualizar la vista con los documentos filtrados
        const documentosOriginales = documentosServicio;
        documentosServicio = documentosFiltrados;

        mostrarDocumentosPorTipo();

        // Restaurar documentos originales para futuros filtros
        documentosServicio = documentosOriginales;
    }

    function limpiarFiltros() {
        document.getElementById('filtroEstado').value = '';
        document.getElementById('filtroTipo').value = '';
        document.getElementById('buscarEstudiante').value = '';

        cargarDocumentosGrid();
    }

    function verDocumento(id) {
        // Almacenar el ID del documento actual
        documentoActualId = id;

        // Mostrar el documento en un modal
        const modal = document.getElementById('modalVerDocumento');
        const iframe = document.getElementById('iframeDocumento');

        if (iframe) {
            iframe.src = `<?= base_url('admin/documentos/servicio/ver') ?>/${id}`;
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    }

    function descargarDesdeModal() {
        if (documentoActualId) {
            // Cerrar el modal primero
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalVerDocumento'));
            if (modal) {
                modal.hide();
            }

            // Descargar el documento
            descargarDocumento(documentoActualId);
        }
    }

    function descargarDocumento(id) {
        // Descargar el documento
        window.location.href = `<?= base_url('admin/documentos/servicio/download') ?>/${id}`;
    }

    function cambiarEstadoDocumento(id) {
        // Buscar el documento en el array de documentos
        const documento = documentosServicio.find(doc => doc.ID_DOCUMENTO_SERVICIO == id);

        if (documento) {
            document.getElementById('documento_id_estado').value = id;
            document.getElementById('nombre_documento_estado').value = `${documento.NOMBRE_ARCHIVO} - ${documento.NOMBRE_ESTUDIANTE} ${documento.APELLIDO_ESTUDIANTE}`;
        } else {
            document.getElementById('documento_id_estado').value = id;
            document.getElementById('nombre_documento_estado').value = 'Documento no encontrado';
        }

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

        fetch(`<?= base_url('admin/documentos/servicio/cambiar-estado') ?>/${documentoId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
                    document.getElementById('formCambiarEstado').reset();

                    // Actualizar el estado del documento en el array local
                    actualizarEstadoDocumentoLocal(documentoId, nuevoEstado);

                    // Actualizar las estadísticas
                    actualizarEstadisticas();

                    // Recargar la vista de documentos
                    mostrarDocumentosPorTipo();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al cambiar el estado', 'error');
            });
    }

    function actualizarEstadoDocumentoLocal(documentoId, nuevoEstado) {
        // Buscar y actualizar el documento en el array local
        const documento = documentosServicio.find(doc => doc.ID_DOCUMENTO_SERVICIO == documentoId);
        if (documento) {
            // Convertir el número del estado a texto
            const estadoInfo = obtenerEstadoInfo(nuevoEstado);
            documento.ESTADO_REVISION = estadoInfo.texto;
        }
    }

    function actualizarEstadisticas() {
        // Contar documentos por estado
        const aprobados = documentosServicio.filter(doc => doc.ESTADO_REVISION === 'Aprobado').length;
        const rechazados = documentosServicio.filter(doc => doc.ESTADO_REVISION === 'Rechazado').length;
        const requiereCorreccion = documentosServicio.filter(doc => doc.ESTADO_REVISION === 'Requiere Corrección').length;
        const pendientes = documentosServicio.filter(doc => doc.ESTADO_REVISION === 'Pendiente').length;

        // Actualizar los elementos HTML con los IDs correctos
        const aprobadosElement = document.getElementById('Aprobados');
        const rechazadosElement = document.getElementById('Rechazados');
        const requiereCorreccionElement = document.getElementById('RequiereCorreccion');
        const pendientesElement = document.getElementById('Pendientes');

        if (aprobadosElement) aprobadosElement.textContent = aprobados;
        if (rechazadosElement) rechazadosElement.textContent = rechazados;
        if (requiereCorreccionElement) requiereCorreccionElement.textContent = requiereCorreccion;
        if (pendientesElement) pendientesElement.textContent = pendientes;

        // Agregar animación de actualización
        [aprobadosElement, rechazadosElement, requiereCorreccionElement, pendientesElement].forEach(element => {
            if (element) {
                element.style.transform = 'scale(1.1)';
                element.style.transition = 'transform 0.3s ease';
                setTimeout(() => {
                    element.style.transform = 'scale(1)';
                }, 300);
            }
        });
    }

    function generarReporteServicio() {
        // Redirigir a la vista de reportes en la misma ventana
        window.location.href = '<?= base_url('admin/documentos/servicio/reportes') ?>';
    }

    function revisionMasiva() {
        showNotification('Función de revisión masiva en desarrollo. Permite cambiar el estado de múltiples documentos a la vez.', 'info');
    }

    // Funciones para manejar nuevo tipo de servicio comunitario

    function limpiarFormularioNuevoTipo() {
        document.getElementById('nuevo_codigo').value = '';
        document.getElementById('nuevo_nombre').value = '';
        document.getElementById('nuevo_descripcion').value = '';
        document.getElementById('nuevo_orden').value = '';
        document.getElementById('nuevo_obligatorio').value = '1';
    }

    function crearNuevoTipo() {
        const codigo = document.getElementById('nuevo_codigo').value.trim();
        const nombre = document.getElementById('nuevo_nombre').value.trim();
        const descripcion = document.getElementById('nuevo_descripcion').value.trim();
        const orden = document.getElementById('nuevo_orden').value;
        const obligatorio = document.getElementById('nuevo_obligatorio').value;

        // Validaciones
        if (!codigo) {
            showNotification('El código PSC es requerido', 'error');
            return;
        }

        if (!/^PSC-\d{3}$/.test(codigo)) {
            showNotification('El código debe tener el formato PSC-XXX (ej: PSC-013)', 'error');
            return;
        }

        if (!nombre) {
            showNotification('El nombre del documento es requerido', 'error');
            return;
        }

        if (!orden) {
            showNotification('El orden es requerido', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('codigo', codigo);
        formData.append('nombre', nombre);
        formData.append('descripcion', descripcion);
        formData.append('orden', orden);
        formData.append('obligatorio', obligatorio);

        fetch('<?= base_url('admin/documentos/servicio/crear-tipo') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    // Agregar la nueva opción al select
                    agregarOpcionAlSelect(data.tipo);
                    // Limpiar formulario
                    limpiarFormularioNuevoTipo();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al crear el nuevo tipo de documento', 'error');
            });
    }

    function agregarOpcionAlSelect(tipo) {
        const select = document.getElementById('filtroTipo');
        const option = document.createElement('option');
        option.value = tipo.ID_TIPO_DOCUMENTO_SERVICIO;
        option.textContent = `${tipo.CODIGO}. ${tipo.NOMBRE}`;
        select.appendChild(option);

        // Seleccionar la nueva opción
        select.value = tipo.ID_TIPO_DOCUMENTO_SERVICIO;
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

    // Formulario subir documento de formato (Servicio Comunitario)
    document.getElementById('formDocumentoFormatoServicio')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const input = document.getElementById('docFormatoServicio');
        const nombre = form.querySelector('[name="nombre"]').value.trim();
        if (!nombre) {
            showNotification('Indique el nombre del documento', 'error');
            return;
        }
        if (!input?.files?.length) {
            showNotification('Seleccione un archivo', 'error');
            return;
        }
        const formData = new FormData(form);
        const btn = document.getElementById('btnSubirDocFormatoServicio');
        btn.disabled = true;
        fetch('<?= base_url('admin/documentos/servicio/subir-formato') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                if (data.success) {
                    showNotification(data.message, 'success');
                    form.reset();
                    actualizarTablaFormatosServicio(data.lista || []);
                } else {
                    showNotification(data.message || 'Error al subir', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                showNotification('Error de conexión', 'error');
            });
    });

    function actualizarTablaFormatosServicio(lista) {
        const tbody = document.getElementById('tbodyFormatosServicio');
        if (!tbody) return;
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">No hay documentos. Suba uno arriba.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map((item, i) => {
            const archivo = (item.archivo || '').replace(/"/g, '&quot;');
            return `<tr>
                <td>${i + 1}</td>
                <td>${escapeHtmlServ(item.nombre || '')}</td>
                <td><span class="text-muted small">${escapeHtmlServ(item.archivo || '')}</span></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-archivo="${escapeHtmlServ(archivo)}" onclick="eliminarDocFormatoServicio(this.getAttribute('data-archivo'))" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        }).join('');
    }

    function eliminarDocFormatoServicio(archivo) {
        if (!archivo || !confirm('¿Eliminar este documento de formato?')) return;
        fetch('<?= base_url('admin/documentos/servicio/eliminar-formato/') ?>' + encodeURIComponent(archivo), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    actualizarTablaFormatosServicio(data.lista || []);
                } else {
                    showNotification(data.message || 'Error', 'error');
                }
            })
            .catch(() => showNotification('Error de conexión', 'error'));
    }

    function escapeHtmlServ(s) {
        const div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vista de documentos de servicio comunitario cargada');

        // Verificar tipos de documentos disponibles
        const tiposDocumentos = <?= json_encode($tiposDocumentos ?? []) ?>;
        console.log('Tipos de documentos desde PHP:', tiposDocumentos);

        if (!tiposDocumentos || tiposDocumentos.length === 0) {
            console.warn('No hay tipos de documentos configurados');
            showNotification('No hay tipos de documentos configurados. Contacte al administrador.', 'warning');
        }

        // Cargar documentos inicialmente
        cargarDocumentosGrid();

        // Limpiar iframe cuando se cierre el modal de ver documento
        const modalVerDocumento = document.getElementById('modalVerDocumento');
        if (modalVerDocumento) {
            modalVerDocumento.addEventListener('hidden.bs.modal', function() {
                const iframe = document.getElementById('iframeDocumento');
                if (iframe) {
                    iframe.src = '';
                }
                documentoActualId = null;
            });
        }
    });
</script>
<?= $this->endSection() ?>
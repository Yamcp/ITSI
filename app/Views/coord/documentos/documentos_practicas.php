<?= $this->extend('coord/layouts/mainCoord') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .estado-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .filtros-bar {
        border: 1px solid #e9ecef;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    /* Barra de pestañas: mismo estilo que Documentos - Servicio Comunitario (anula practicas.css) */
    #documentosTabs.nav-tabs {
        border: none !important;
        border-bottom: none !important;
        gap: 0.5rem;
        border-radius: 50rem;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        margin-bottom: 0 !important;
    }
    #documentosTabs .nav-link {
        border: none !important;
        border-bottom: none !important;
        background-color: transparent;
        padding: 0.6rem 1.1rem;
        border-radius: 50rem !important;
        font-weight: 600;
        transition: background-color 0.2s, color 0.2s;
        color: inherit;
    }
    #documentosTabs .nav-link:hover {
        background-color: #e9ecef;
    }
    #documentosTabs .nav-link.active {
        background-color: #fff !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border-bottom: none !important;
    }
    #documentosTabs #documentos-tipo-tab.active,
    #documentosTabs #documentos-tipo-tab.active i {
        color: #0d6efd !important;
    }
    #documentosTabs #formatos-docs-tab,
    #documentosTabs #formatos-docs-tab i,
    #documentosTabs #formatos-docs-tab.active,
    #documentosTabs #formatos-docs-tab.active i {
        color: #198754 !important;
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
                    <i class="fas fa-folder-open me-2"></i>
                    Documentos - Prácticas Preprofesionales
                </h3>
            </div>
        </div>

        <!-- Estadísticas y acciones (una fila en pantallas grandes: 7 columnas) -->
        <div class="row g-3 mb-4 align-items-stretch">
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="Pendientes" style="font-size:2.5rem;"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="Aprobados" style="font-size:2.5rem;"><?= $estadisticas['Aprobados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="RequiereCorreccion" style="font-size:2.5rem;"><?= $estadisticas['requiere_correccion'] ?? 0 ?></h2>
                        <p class="card-text fw-bold mb-0 small" style="color: #fffbe6;">Requiere Corrección</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #dc3545 80%, #c82333 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="Rechazados" style="font-size:2.5rem;"><?= $estadisticas['rechazados'] ?? 0 ?></h2>
                        <p class="card-text fw-bold mb-0" style="color: #ffe0e0;">Rechazados</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalSubirDocumentoPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold small">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReportePracticas()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold small">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="<?= base_url('coord/documentos/practicas/reportes') ?>" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold small">Exportar Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros (entre Acciones Rápidas y la card de pestañas) -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="filtros-bar rounded-pill bg-light px-3 py-2 d-flex flex-wrap align-items-center gap-2">
                    <span class="fw-semibold text-muted"><i class="fas fa-filter me-1"></i>Filtros</span>
                    <select class="form-select form-select-sm" id="filtroEstado" onchange="aplicarFiltros()" style="width: auto; max-width: 160px;">
                        <option value="">Todos los estados</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En Revisión">En Revisión</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Rechazado">Rechazado</option>
                        <option value="Requiere Corrección">Requiere Corrección</option>
                    </select>
                    <select class="form-select form-select-sm" id="filtroTipo" onchange="aplicarFiltros()" style="width: auto; max-width: 200px;">
                        <option value="">Todos los tipos</option>
                        <?php if (isset($tiposDocumentos)): ?>
                            <?php foreach ($tiposDocumentos as $tipo): ?>
                                <option value="<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="buscarEstudiante" placeholder="Estudiante o cédula..." onkeyup="aplicarFiltros()" style="width: 180px;">
                    <button class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros()"><i class="fas fa-times me-1"></i>Limpiar</button>
                    <button class="btn btn-outline-primary btn-sm" onclick="showModal('modalFiltrosPracticas')"><i class="fas fa-sliders-h me-1"></i>Más filtros</button>
                </div>
            </div>
        </div>

        <!-- Tabs (igual que Documentos - Servicio Comunitario) -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="documentosTabs" role="tablist" style="gap: 0.5rem;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active rounded-pill fw-semibold text-primary" id="documentos-tipo-tab" data-bs-toggle="tab" data-bs-target="#documentos-por-tipo" type="button" role="tab" aria-selected="true">
                                    <i class="fas fa-folder-open me-2"></i>Documentos por tipo
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link rounded-pill fw-semibold text-success" id="formatos-docs-tab" data-bs-toggle="tab" data-bs-target="#formatos-documentos" type="button" role="tab" aria-selected="false">
                                    <i class="fas fa-file-upload me-2"></i>Documentos de formato
                                </button>
                            </li>
                        </ul>
                        <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                        <div class="tab-content mt-3" id="documentosTabContent">
                            <!-- Pestaña: Documentos por tipo -->
                            <div class="tab-pane fade show active" id="documentos-por-tipo" role="tabpanel">
                                <div id="vistaGrid">
                                    <?php if (!empty($tiposDocumentos)): ?>
                                        <?php foreach ($tiposDocumentos as $tipo): ?>
                                            <div class="card shadow-sm border-0 mb-4" id="card-tipo-<?= (int) $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>">
                                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                    <span class="tipo-titulo flex-grow-1"><i class="fas fa-file-alt me-2"></i><?= esc($tipo['CODIGO']) ?>. <?= esc($tipo['NOMBRE']) ?></span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button type="button" class="btn btn-sm btn-light text-primary" onclick="abrirEditarTipoPractica(<?= (int) $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>)" title="Editar nombre, descripción y demás datos del tipo">
                                                            <i class="fas fa-pen me-1"></i>Editar tipo
                                                        </button>
                                                        <span class="badge bg-light text-dark tipo-badge-oblig"><?= $tipo['OBLIGATORIO'] ? 'Obligatorio' : 'Opcional' ?></span>
                                                    </div>
                                                </div>
                                                <?php
                                                $descTipo = trim((string) ($tipo['DESCRIPCION'] ?? ''));
                                                ?>
                                                <div class="px-3 py-2 bg-light border-bottom small text-muted tipo-detalle"><?php if ($descTipo !== ''): ?><?= nl2br(esc($descTipo)) ?><?php else: ?><span class="fst-italic">Sin descripción. Use «Editar tipo» para añadir el detalle visible para estudiantes y coordinación.</span><?php endif; ?></div>
                                                <div class="card-body p-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped align-middle mb-0 table-documentos" id="tabla-<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Estudiante</th>
                                                                    <th>Cédula</th>
                                                                    <th>Entidad Receptora</th>
                                                                    <th>Archivo</th>
                                                                    <th>Estado</th>
                                                                    <th>Fecha</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="documentos-<?= $tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?>"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="card shadow-sm border-0">
                                            <div class="card-body text-center py-4">
                                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No hay tipos de documentos configurados</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Pestaña: Documentos de formato (Prácticas Preprofesionales) -->
                            <div class="tab-pane fade" id="formatos-documentos" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-file-upload me-2"></i>Documentos de formato – Prácticas Preprofesionales</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">Estos documentos se muestran en el perfil del estudiante (Formatos - Prácticas Laborales) para que puedan descargarlos.</p>
                                        <div class="row mb-4">
                                            <div class="col-md-8">
                                                <form id="formDocumentoFormatoPracticas" enctype="multipart/form-data">
                                                    <div class="row g-2">
                                                        <div class="col-md-5">
                                                            <label class="form-label fw-bold">Nombre del documento</label>
                                                            <input type="text" class="form-control" name="nombre" placeholder="Ej: Modelo informe prácticas" required />
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label fw-bold">Archivo (PDF, DOC, DOCX)</label>
                                                            <input type="file" class="form-control" name="documento" id="docFormatoPracticas" accept=".pdf,.doc,.docx" required />
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="submit" class="btn btn-success w-100" id="btnSubirDocFormatoPracticas">
                                                                <i class="fas fa-upload me-1"></i> Subir
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered table-hover table-documentos table-coordinador-formatos-lista">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th class="formatos-archivo-col">Archivo</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbodyFormatosPracticas">
                                                    <?php
                                                    $docsFormatos = $documentos_formatos_practicas ?? [];
                                                    foreach ($docsFormatos as $i => $item): ?>
                                                        <tr>
                                                            <td><?= $i + 1 ?></td>
                                                            <td><?= esc($item['nombre'] ?? '') ?></td>
                                                            <td class="formatos-archivo-celda">
                                                                <span class="text-muted formatos-archivo-texto" title="<?= esc($item['archivo'] ?? '', 'attr') ?>"><?= esc($item['archivo'] ?? '') ?></span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-secondary btn-sm me-1 btn-editar-nombre-formato-practicas" data-archivo="<?= esc($item['archivo'] ?? '', 'attr') ?>" data-nombre="<?= esc($item['nombre'] ?? '', 'attr') ?>" title="Editar nombre">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocFormatoPracticas('<?= esc($item['archivo'] ?? '') ?>')" title="Eliminar">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php if (empty($docsFormatos)): ?>
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

<!-- Modal: editar nombre visible de documento de formato (PPR) -->
<div class="modal fade" id="modalEditarNombreFormatoPracticas" tabindex="-1" aria-labelledby="modalEditarNombreFormatoPracticasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarNombreFormatoPracticasLabel">
                    <i class="fas fa-pen me-2"></i>Editar nombre del documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditarNombreFormatoPracticas">
                <div class="modal-body">
                    <input type="hidden" id="editFormatoPracticasArchivo" name="archivo" value="" />
                    <label for="editFormatoPracticasNombre" class="form-label">Nombre visible para estudiantes</label>
                    <input type="text" class="form-control" id="editFormatoPracticasNombre" name="nombre" maxlength="500" required autocomplete="off" />
                    <p class="text-muted small mt-2 mb-0">No se renombra el archivo en el servidor; solo se corrige el título en la lista de formatos.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarNombreFormatoPracticas">
                        <i class="fas fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para formulario para crear nuevo tipo PPR -->
<div class="modal fade" id="modalSubirDocumentoPractica" tabindex="-1">
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
                <form id="formSubirDocumentoPractica">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Código</label>
                                    <input type="text" class="form-control" id="nuevo_codigo" placeholder="Ej: PPR-013" pattern="PPR-\d{3}">
                                    <div class="form-text">Formato: PPR-XXX (ej: PPR-013)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Documento</label>
                                    <input type="text" class="form-control" id="nuevo_nombre" placeholder="Ej: Informe Técnico Especializado">
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
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Modal editar tipo de documento PPR (descripción / detalle) -->
<div class="modal fade" id="modalEditarTipoPractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-pen me-2"></i>
                    Editar tipo de documento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditarTipoPractica">
                    <input type="hidden" id="edit_tipo_id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_codigo">Código</label>
                                <input type="text" class="form-control" id="edit_codigo" pattern="PPR-\d{3}" required>
                                <div class="form-text">Formato: PPR-XXX</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_nombre">Nombre del documento</label>
                                <input type="text" class="form-control" id="edit_nombre" maxlength="150" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="edit_descripcion">Descripción / detalle</label>
                        <textarea class="form-control" id="edit_descripcion" rows="4" placeholder="Texto que explica qué es este documento (se muestra en coordinador y perfil del estudiante)."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_orden">Orden</label>
                                <input type="number" class="form-control" id="edit_orden" min="1" max="99" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_obligatorio">Tipo</label>
                                <select class="form-select" id="edit_obligatorio">
                                    <option value="1">Obligatorio</option>
                                    <option value="0">Opcional</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEditarTipoPractica()">
                    <i class="fas fa-save me-1"></i>Guardar cambios
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
                            <option value="1">Dr. José Pijal - Rector</option>
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
                        <select class="form-select" name="nuevo_estado" id="selectNuevoEstado" required>
                            <option value="">Seleccionar nuevo estado...</option>
                            <option value="1">Pendiente</option>
                            <option value="2">En Revisión</option>
                            <option value="3">Aprobado</option>
                            <option value="4">Rechazado</option>
                            <option value="5">Requiere Corrección</option>
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
    const tiposDocumentosCatalogoPracticas = <?= json_encode($tiposDocumentos ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    // Variables globales
    let documentosPracticas = [];
    let documentoActualId = null;

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }


    function cargarDocumentosGrid() {
        const urlEstudiante = <?= json_encode(!empty($estudiante_filtro) ? (int)$estudiante_filtro : null) ?>;
        const url = '<?= base_url('coord/documentos/practicas/obtenerDocumentos') ?>' + (urlEstudiante ? '?estudiante=' + urlEstudiante : '');
        fetch(url)
            .then(response => response.json())
            .then(data => {
                console.log('Datos recibidos:', data);
                if (data.success) {
                    documentosPracticas = data.documentos || data.data || [];
                    console.log('Documentos cargados:', documentosPracticas);
                    mostrarDocumentosPorTipo();
                } else {
                    console.error('Error en respuesta:', data.message);
                    showNotification('Error al cargar documentos: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error en fetch:', error);
                showNotification('Error al cargar documentos', 'error');
            });
    }

    function mostrarDocumentosPorTipo() {
        const tiposDocumentos = tiposDocumentosCatalogoPracticas;

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
                <div class="text-truncate" style="max-width: 200px;" title="${doc.ENTIDAD_RECEPTORA || 'No especificada'}">
                    <i class="fas fa-building me-1 text-muted"></i>
                    ${doc.ENTIDAD_RECEPTORA || 'No especificada'}
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
                    <button class="btn btn-outline-primary" onclick="verDocumento(${doc.ID_DOCUMENTO_PREPROFESIONAL})" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.ID_DOCUMENTO_PREPROFESIONAL})" title="Descargar">
                        <i class="fas fa-download"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.ID_DOCUMENTO_PREPROFESIONAL})" title="Cambiar Estado">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;

        return fila;
    }


    function obtenerEstadoInfo(estado) {
        // Mapeo de estados según la base de datos
        const estadosMap = {
            '1': {
                texto: 'Pendiente',
                clase: 'bg-secondary text-white'
            },
            '2': {
                texto: 'En Revisión',
                clase: 'bg-info text-white'
            },
            '3': {
                texto: 'Aprobado',
                clase: 'bg-success text-white'
            },
            '4': {
                texto: 'Rechazado',
                clase: 'bg-danger text-white'
            },
            '5': {
                texto: 'Requiere Corrección',
                clase: 'bg-warning text-dark'
            },
            'Pendiente': {
                texto: 'Pendiente',
                clase: 'bg-secondary text-white'
            },
            'En Revisión': {
                texto: 'En Revisión',
                clase: 'bg-info text-white'
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

        mostrarDocumentosPorTipo();

        // Restaurar documentos originales para futuros filtros
        documentosPracticas = documentosOriginales;
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
            iframe.src = `<?= base_url('coord/documentos/practicas/ver') ?>/${id}`;
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
        window.location.href = `<?= base_url('coord/documentos/practicas/download') ?>/${id}`;
    }


    function subirDocumentoPractica() {
        const form = document.getElementById('formSubirDocumentoPractica');
        const formData = new FormData(form);

        // Agregar el archivo al FormData
        const archivo = document.getElementById('archivoInputPractica').files[0];
        if (archivo) {
            formData.append('archivo', archivo);
        }

        fetch('<?= base_url('coord/documentos/practicas/store') ?>', {
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

        fetch('<?= base_url('coord/documentos/practicas/filtros') ?>', {
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
        window.location.href = '<?= base_url('coord/documentos/practicas/reportes') ?>';
    }

    function cambiarEstadoDocumento(id) {
        // Buscar el documento en el array de documentos
        const documento = documentosPracticas.find(doc => doc.ID_DOCUMENTO_PREPROFESIONAL == id);

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

        fetch(`<?= base_url('coord/documentos/practicas/cambiar-estado') ?>/${documentoId}`, {
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
        const documento = documentosPracticas.find(doc => doc.ID_DOCUMENTO_PREPROFESIONAL == documentoId);
        if (documento) {
            // Convertir el número del estado a texto
            const estadoInfo = obtenerEstadoInfo(nuevoEstado);
            documento.ESTADO_REVISION = estadoInfo.texto;
            documento.ID_ESTADO_REVISION = nuevoEstado;
        }
    }

    function actualizarEstadisticas() {
        // Contar documentos por estado
        const aprobados = documentosPracticas.filter(doc => doc.ESTADO_REVISION === 'Aprobado').length;
        const rechazados = documentosPracticas.filter(doc => doc.ESTADO_REVISION === 'Rechazado').length;
        const requiereCorreccion = documentosPracticas.filter(doc => doc.ESTADO_REVISION === 'Requiere Corrección').length;
        const pendientes = documentosPracticas.filter(doc => doc.ESTADO_REVISION === 'Pendiente').length;

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

    function abrirEditarTipoPractica(id) {
        const tipo = tiposDocumentosCatalogoPracticas.find(t => String(t.ID_TIPO_DOCUMENTO_PREPROFESIONAL) === String(id));
        if (!tipo) {
            showNotification('No se encontró el tipo de documento', 'error');
            return;
        }
        document.getElementById('edit_tipo_id').value = tipo.ID_TIPO_DOCUMENTO_PREPROFESIONAL;
        document.getElementById('edit_codigo').value = tipo.CODIGO || '';
        document.getElementById('edit_nombre').value = tipo.NOMBRE || '';
        document.getElementById('edit_descripcion').value = tipo.DESCRIPCION || '';
        document.getElementById('edit_orden').value = tipo.ORDEN != null ? tipo.ORDEN : '';
        const obl = tipo.OBLIGATORIO === 1 || tipo.OBLIGATORIO === true || tipo.OBLIGATORIO === '1';
        document.getElementById('edit_obligatorio').value = obl ? '1' : '0';
        showModal('modalEditarTipoPractica');
    }

    function guardarEditarTipoPractica() {
        const id = document.getElementById('edit_tipo_id').value;
        const codigo = document.getElementById('edit_codigo').value.trim();
        const nombre = document.getElementById('edit_nombre').value.trim();
        const descripcion = document.getElementById('edit_descripcion').value.trim();
        const orden = document.getElementById('edit_orden').value;
        const obligatorio = document.getElementById('edit_obligatorio').value;

        if (!codigo || !/^PPR-\d{3}$/.test(codigo)) {
            showNotification('El código debe tener el formato PPR-XXX', 'error');
            return;
        }
        if (!nombre) {
            showNotification('El nombre es requerido', 'error');
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

        fetch('<?= base_url('coord/documentos/practicas/actualizar-tipo/') ?>' + encodeURIComponent(id), {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    const modalEl = document.getElementById('modalEditarTipoPractica');
                    const inst = bootstrap.Modal.getInstance(modalEl);
                    if (inst) inst.hide();
                    setTimeout(() => location.reload(), 600);
                } else {
                    showNotification(data.message || 'No se pudo guardar', 'error');
                }
            })
            .catch(() => showNotification('Error de conexión al guardar', 'error'));
    }

    // Funciones para manejar nuevo tipo PPR

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
            showNotification('El código PPR es requerido', 'error');
            return;
        }

        if (!/^PPR-\d{3}$/.test(codigo)) {
            showNotification('El código debe tener el formato PPR-XXX (ej: PPR-013)', 'error');
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

        fetch('<?= base_url('coord/documentos/practicas/crear-tipo') ?>', {
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
        const select = document.getElementById('selectTipoDocumento');
        const option = document.createElement('option');
        option.value = tipo.ID_TIPO_DOCUMENTO_PREPROFESIONAL;
        option.textContent = `${tipo.CODIGO}. ${tipo.NOMBRE}`;
        select.appendChild(option);

        // Seleccionar la nueva opción
        select.value = tipo.ID_TIPO_DOCUMENTO_PREPROFESIONAL;
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

    // Formulario subir documento de formato (Prácticas Preprofesionales)
    document.getElementById('formDocumentoFormatoPracticas')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const input = document.getElementById('docFormatoPracticas');
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
        const btn = document.getElementById('btnSubirDocFormatoPracticas');
        btn.disabled = true;
        fetch('<?= base_url('coord/documentos/practicas/subir-formato') ?>', {
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
                    actualizarTablaFormatosPracticas(data.lista || []);
                } else {
                    showNotification(data.message || 'Error al subir', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                showNotification('Error de conexión', 'error');
            });
    });

    function actualizarTablaFormatosPracticas(lista) {
        const tbody = document.getElementById('tbodyFormatosPracticas');
        if (!tbody) return;
        if (!lista.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">No hay documentos. Suba uno arriba.</td></tr>';
            return;
        }
        tbody.innerHTML = lista.map((item, i) => {
            const archivo = item.archivo || '';
            const nombre = item.nombre || '';
            return `<tr>
                <td>${i + 1}</td>
                <td>${escapeHtml(nombre)}</td>
                <td class="formatos-archivo-celda"><span class="text-muted formatos-archivo-texto" title="${escapeAttr(archivo)}">${escapeHtml(archivo)}</span></td>
                <td>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 btn-editar-nombre-formato-practicas" data-archivo="${escapeAttr(archivo)}" data-nombre="${escapeAttr(nombre)}" title="Editar nombre">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocFormatoPracticas(${JSON.stringify(archivo)})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        }).join('');
    }

    function escapeAttr(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;');
    }

    function abrirModalEditarNombreFormatoPracticas(archivo, nombre) {
        const modalEl = document.getElementById('modalEditarNombreFormatoPracticas');
        if (!modalEl) return;
        const hid = document.getElementById('editFormatoPracticasArchivo');
        const inp = document.getElementById('editFormatoPracticasNombre');
        if (hid) hid.value = archivo || '';
        if (inp) inp.value = nombre || '';
        if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
            showNotification('No se pudo abrir el modal. Recargue la página.', 'error');
            return;
        }
        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-editar-nombre-formato-practicas');
        if (!btn || !document.getElementById('tbodyFormatosPracticas')?.contains(btn)) return;
        e.preventDefault();
        abrirModalEditarNombreFormatoPracticas(btn.getAttribute('data-archivo') || '', btn.getAttribute('data-nombre') || '');
    });

    document.getElementById('formEditarNombreFormatoPracticas')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const archivo = document.getElementById('editFormatoPracticasArchivo').value;
        const nombre = document.getElementById('editFormatoPracticasNombre').value.trim();
        if (!archivo || !nombre) {
            showNotification('Complete el nombre del documento.', 'error');
            return;
        }
        const btn = document.getElementById('btnGuardarNombreFormatoPracticas');
        const fd = new FormData();
        fd.append('archivo', archivo);
        fd.append('nombre', nombre);
        if (btn) btn.disabled = true;
        fetch('<?= base_url('coord/documentos/practicas/actualizar-nombre-formato') ?>', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (data.success) {
                    showNotification(data.message, 'success');
                    actualizarTablaFormatosPracticas(data.lista || []);
                    const modalEl = document.getElementById('modalEditarNombreFormatoPracticas');
                    const inst = modalEl ? bootstrap.Modal.getInstance(modalEl) : null;
                    if (inst) inst.hide();
                } else {
                    showNotification(data.message || 'Error al guardar', 'error');
                }
            })
            .catch(() => {
                if (btn) btn.disabled = false;
                showNotification('Error de conexión', 'error');
            });
    });

    function eliminarDocFormatoPracticas(archivo) {
        if (!archivo || !confirm('¿Eliminar este documento de formato?')) return;
        fetch('<?= base_url('coord/documentos/practicas/eliminar-formato/') ?>' + encodeURIComponent(archivo), {
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
                    actualizarTablaFormatosPracticas(data.lista || []);
                } else {
                    showNotification(data.message || 'Error', 'error');
                }
            })
            .catch(() => showNotification('Error de conexión', 'error'));
    }

    function escapeHtml(s) {
        const div = document.createElement('div');
        div.textContent = s;
        return div.innerHTML;
    }

    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Vista de documentos de prácticas cargada');

        // Si se llegó con hash #formatos-documentos, activar esa pestaña
        if (window.location.hash === '#formatos-documentos') {
            const tabFormatos = document.getElementById('formatos-docs-tab');
            if (tabFormatos && typeof bootstrap !== 'undefined') {
                const tab = new bootstrap.Tab(tabFormatos);
                tab.show();
            }
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
                    const event = new Event('change', {
                        bubbles: true
                    });
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
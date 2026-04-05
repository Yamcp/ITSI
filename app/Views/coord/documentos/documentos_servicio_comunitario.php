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

    /* Misma barra de pestañas que Documentos - Prácticas Preprofesionales */
    #documentosTabsServicio.nav-tabs {
        border: none !important;
        border-bottom: none !important;
        gap: 0.5rem;
        border-radius: 50rem;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        margin-bottom: 0 !important;
    }
    #documentosTabsServicio .nav-link {
        border: none !important;
        border-bottom: none !important;
        background-color: transparent;
        padding: 0.6rem 1.1rem;
        border-radius: 50rem !important;
        font-weight: 600;
        transition: background-color 0.2s, color 0.2s;
        color: inherit;
    }
    #documentosTabsServicio .nav-link:hover {
        background-color: #e9ecef;
    }
    #documentosTabsServicio .nav-link.active {
        background-color: #fff !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border-bottom: none !important;
    }
    #documentosTabsServicio #documentos-tipo-tab-servicio.active,
    #documentosTabsServicio #documentos-tipo-tab-servicio.active i {
        color: #0d6efd !important;
    }
    #documentosTabsServicio #formatos-docs-tab-servicio,
    #documentosTabsServicio #formatos-docs-tab-servicio i,
    #documentosTabsServicio #formatos-docs-tab-servicio.active,
    #documentosTabsServicio #formatos-docs-tab-servicio.active i {
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
                    Documentos - Servicio Comunitario
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
                        <a href="#" onclick="showModal('modalSubirDocumentoServicio')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold small">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporteServicio()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold small">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="<?= base_url('coord/documentos/servicio/reportes') ?>" style="text-decoration: none; color: inherit;">
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
                                <option value="<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="buscarEstudiante" placeholder="Estudiante o cédula..." onkeyup="aplicarFiltros()" style="width: 180px;">
                    <button class="btn btn-outline-secondary btn-sm" onclick="limpiarFiltros()"><i class="fas fa-times me-1"></i>Limpiar</button>
                    <button class="btn btn-outline-primary btn-sm" onclick="showModal('modalFiltrosServicio')"><i class="fas fa-sliders-h me-1"></i>Más filtros</button>
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
                                <div id="vistaGrid">
                                    <?php if (!empty($tiposDocumentos)): ?>
                                        <?php foreach ($tiposDocumentos as $tipo): ?>
                                            <div class="card shadow-sm border-0 mb-4" id="card-tipo-<?= (int) $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                    <span class="tipo-titulo flex-grow-1"><i class="fas fa-file-alt me-2"></i><?= esc($tipo['CODIGO']) ?>. <?= esc($tipo['NOMBRE']) ?></span>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <button type="button" class="btn btn-sm btn-light text-primary" onclick="abrirEditarTipoServicio(<?= (int) $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>)" title="Editar nombre, descripción y demás datos del tipo">
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
                                                        <table class="table table-bordered table-striped align-middle mb-0 table-documentos" id="tabla-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Estudiante</th>
                                                                    <th>Cédula</th>
                                                                    <th>Proyecto Social</th>
                                                                    <th>Archivo</th>
                                                                    <th>Estado</th>
                                                                    <th>Fecha</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="documentos-<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>"></tbody>
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

                            <!-- Pestaña: Documentos de formato (Servicio Comunitario) -->
                            <div class="tab-pane fade" id="formatos-documentos-servicio" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-file-upload me-2"></i>Documentos de formato – Servicio Comunitario</span>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">Estos documentos se muestran en el perfil del estudiante (Formatos - Servicio Comunitario) para que puedan descargarlos.</p>
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
                                            <table class="table table-sm table-bordered table-hover table-documentos table-coordinador-formatos-lista">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Nombre</th>
                                                        <th class="formatos-archivo-col">Archivo</th>
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
                                                            <td class="formatos-archivo-celda">
                                                                <span class="text-muted formatos-archivo-texto" title="<?= esc($item['archivo'] ?? '', 'attr') ?>"><?= esc($item['archivo'] ?? '') ?></span>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-outline-secondary btn-sm me-1 btn-editar-nombre-formato-servicio" data-archivo="<?= esc($item['archivo'] ?? '', 'attr') ?>" data-nombre="<?= esc($item['nombre'] ?? '', 'attr') ?>" title="Editar nombre">
                                                                    <i class="fas fa-pen"></i>
                                                                </button>
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

<!-- Modal: editar nombre visible de documento de formato (Servicio Comunitario) -->
<div class="modal fade" id="modalEditarNombreFormatoServicio" tabindex="-1" aria-labelledby="modalEditarNombreFormatoServicioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarNombreFormatoServicioLabel">
                    <i class="fas fa-pen me-2"></i>Editar nombre del documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formEditarNombreFormatoServicio">
                <div class="modal-body">
                    <input type="hidden" id="editFormatoServicioArchivo" name="archivo" value="" />
                    <label for="editFormatoServicioNombre" class="form-label">Nombre visible para estudiantes</label>
                    <input type="text" class="form-control" id="editFormatoServicioNombre" name="nombre" maxlength="500" required autocomplete="off" />
                    <p class="text-muted small mt-2 mb-0">No se renombra el archivo en el servidor; solo se corrige el título en la lista de formatos.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarNombreFormatoServicio">
                        <i class="fas fa-save me-1"></i>Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal filtros avanzados (misma idea que Prácticas Preprofesionales) -->
<div class="modal fade" id="modalFiltrosServicio" tabindex="-1">
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
                <form id="formFiltrosServicio">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <select class="form-select" name="filtro_tipo_documento">
                            <option value="">Todos los tipos</option>
                            <?php if (isset($tipos_documentos)): ?>
                                <?php foreach ($tipos_documentos as $tipo): ?>
                                    <option value="<?= $tipo['ID_TIPO_DOCUMENTO_SERVICIO'] ?>"><?= esc($tipo['CODIGO']) ?>. <?= esc($tipo['NOMBRE']) ?></option>
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
                                    <option value="<?= $estado['ID_ESTADO_REVISION'] ?>"><?= esc($estado['ESTADO']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proyecto social (texto)</label>
                        <input type="text" class="form-control" name="filtro_proyecto" placeholder="Buscar por proyecto...">
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
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltrosServicioModal()">Limpiar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltrosServicioModal()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
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

<!-- Modal editar tipo de documento PSC -->
<div class="modal fade" id="modalEditarTipoServicio" tabindex="-1">
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
                <form id="formEditarTipoServicio">
                    <input type="hidden" id="edit_tipo_id_serv" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_codigo_serv">Código</label>
                                <input type="text" class="form-control" id="edit_codigo_serv" pattern="PSC-\d{3}" required>
                                <div class="form-text">Formato: PSC-XXX</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_nombre_serv">Nombre del documento</label>
                                <input type="text" class="form-control" id="edit_nombre_serv" maxlength="255" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="edit_descripcion_serv">Descripción / detalle</label>
                        <textarea class="form-control" id="edit_descripcion_serv" rows="4" placeholder="Texto que explica qué es este documento."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_orden_serv">Orden</label>
                                <input type="number" class="form-control" id="edit_orden_serv" min="1" max="99" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="edit_obligatorio_serv">Tipo</label>
                                <select class="form-select" id="edit_obligatorio_serv">
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
                <button type="button" class="btn btn-primary" onclick="guardarEditarTipoServicio()">
                    <i class="fas fa-save me-1"></i>Guardar cambios
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
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Revisión">En Revisión</option>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Requiere Corrección">Requiere Corrección</option>
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
    const tiposDocumentosCatalogoServicio = <?= json_encode($tiposDocumentos ?? [], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

    let documentosServicio = [];
    let documentoActualId = null;
    let filtroExtraProyecto = '';
    let filtroExtraDesde = '';
    let filtroExtraHasta = '';

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cargarDocumentosGrid() {
        const urlEstudiante = <?= json_encode(!empty($estudiante_filtro) ? (int)$estudiante_filtro : null) ?>;
        const url = '<?= base_url('coord/documentos/servicio/obtenerDocumentos') ?>' + (urlEstudiante ? '?estudiante=' + urlEstudiante : '');
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    documentosServicio = data.documentos || data.data || [];
                    mostrarDocumentosPorTipo();
                } else {
                    showNotification('Error al cargar documentos: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Error al cargar documentos: ' + error.message, 'error');
            });
    }

    function mostrarDocumentosPorTipo() {
        const tiposDocumentos = tiposDocumentosCatalogoServicio;

        tiposDocumentos.forEach(tipo => {
            const contenedor = document.getElementById(`documentos-${tipo.ID_TIPO_DOCUMENTO_SERVICIO}`);
            if (contenedor) {
                contenedor.innerHTML = '';

                const documentosTipo = documentosServicio.filter(doc =>
                    doc.ID_TIPO_DOCUMENTO == tipo.ID_TIPO_DOCUMENTO_SERVICIO
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

        const estadoInfo = obtenerEstadoInfo(doc.ESTADO_REVISION != null && doc.ESTADO_REVISION !== '' ? doc.ESTADO_REVISION : doc.ID_ESTADO_REVISION);
        const fecha = new Date(doc.FECHA_SUBIDA).toLocaleDateString('es-ES');

        fila.innerHTML = `
            <td class="text-center">${numero}</td>
            <td>
                <div class="fw-bold">${doc.NOMBRE_ESTUDIANTE} ${doc.APELLIDO_ESTUDIANTE}</div>
            </td>
            <td>
                <span class="text-muted">${doc.CEDULA_ESTUDIANTE || doc.CEDULA || ''}</span>
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
        const estadosMap = {
            '1': { texto: 'Pendiente', clase: 'bg-secondary text-white' },
            '2': { texto: 'En Revisión', clase: 'bg-info text-white' },
            '3': { texto: 'Aprobado', clase: 'bg-success text-white' },
            '4': { texto: 'Rechazado', clase: 'bg-danger text-white' },
            '5': { texto: 'Requiere Corrección', clase: 'bg-warning text-dark' },
            'Pendiente': { texto: 'Pendiente', clase: 'bg-secondary text-white' },
            'En Revisión': { texto: 'En Revisión', clase: 'bg-info text-white' },
            'Aprobado': { texto: 'Aprobado', clase: 'bg-success text-white' },
            'Rechazado': { texto: 'Rechazado', clase: 'bg-danger text-white' },
            'Requiere Corrección': { texto: 'Requiere Corrección', clase: 'bg-warning text-dark' }
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
            documentosFiltrados = documentosFiltrados.filter(doc => {
                const nombre = doc.ESTADO_REVISION || obtenerEstadoInfo(doc.ID_ESTADO_REVISION).texto;
                return nombre === filtroEstado;
            });
        }

        if (filtroTipo) {
            documentosFiltrados = documentosFiltrados.filter(doc => doc.ID_TIPO_DOCUMENTO == filtroTipo);
        }

        if (buscarEstudiante) {
            documentosFiltrados = documentosFiltrados.filter(doc =>
                doc.NOMBRE_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                doc.APELLIDO_ESTUDIANTE.toLowerCase().includes(buscarEstudiante) ||
                String(doc.CEDULA_ESTUDIANTE || doc.CEDULA || '').includes(buscarEstudiante)
            );
        }

        if (filtroExtraProyecto) {
            documentosFiltrados = documentosFiltrados.filter(doc =>
                (doc.PROYECTO_SOCIAL || '').toLowerCase().includes(filtroExtraProyecto)
            );
        }
        if (filtroExtraDesde) {
            const desde = new Date(filtroExtraDesde);
            desde.setHours(0, 0, 0, 0);
            documentosFiltrados = documentosFiltrados.filter(doc => {
                const d = new Date(doc.FECHA_SUBIDA);
                return !isNaN(d) && d >= desde;
            });
        }
        if (filtroExtraHasta) {
            const hasta = new Date(filtroExtraHasta);
            hasta.setHours(23, 59, 59, 999);
            documentosFiltrados = documentosFiltrados.filter(doc => {
                const d = new Date(doc.FECHA_SUBIDA);
                return !isNaN(d) && d <= hasta;
            });
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
        filtroExtraProyecto = '';
        filtroExtraDesde = '';
        filtroExtraHasta = '';
        const formModal = document.getElementById('formFiltrosServicio');
        if (formModal) formModal.reset();

        cargarDocumentosGrid();
    }

    function limpiarFiltrosServicioModal() {
        document.getElementById('formFiltrosServicio').reset();
        filtroExtraProyecto = '';
        filtroExtraDesde = '';
        filtroExtraHasta = '';
        showNotification('Formulario de filtros limpiado', 'info');
    }

    function aplicarFiltrosServicioModal() {
        const form = document.getElementById('formFiltrosServicio');
        const tipo = form.querySelector('[name="filtro_tipo_documento"]').value;
        document.getElementById('filtroTipo').value = tipo || '';

        const estadoSel = form.querySelector('[name="filtro_estado"]');
        if (estadoSel.value) {
            const opt = estadoSel.selectedOptions[0];
            document.getElementById('filtroEstado').value = opt ? opt.textContent.trim() : '';
        } else {
            document.getElementById('filtroEstado').value = '';
        }

        filtroExtraProyecto = (form.querySelector('[name="filtro_proyecto"]').value || '').trim().toLowerCase();
        filtroExtraDesde = form.querySelector('[name="fecha_desde"]').value || '';
        filtroExtraHasta = form.querySelector('[name="fecha_hasta"]').value || '';

        const modalEl = document.getElementById('modalFiltrosServicio');
        const inst = bootstrap.Modal.getInstance(modalEl);
        if (inst) inst.hide();

        aplicarFiltros();
        showNotification('Filtros aplicados', 'success');
    }

    function verDocumento(id) {
        // Almacenar el ID del documento actual
        documentoActualId = id;

        // Mostrar el documento en un modal
        const modal = document.getElementById('modalVerDocumento');
        const iframe = document.getElementById('iframeDocumento');

        if (iframe) {
            iframe.src = `<?= base_url('coord/documentos/servicio/ver') ?>/${id}`;
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
        window.location.href = `<?= base_url('coord/documentos/servicio/download') ?>/${id}`;
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
        formData.append('observaciones', comentarios);

        fetch(`<?= base_url('coord/documentos/servicio/cambiar-estado') ?>/${documentoId}`, {
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
        window.location.href = '<?= base_url('coord/documentos/servicio/reportes') ?>';
    }

    function abrirEditarTipoServicio(id) {
        const tipo = tiposDocumentosCatalogoServicio.find(t => String(t.ID_TIPO_DOCUMENTO_SERVICIO) === String(id));
        if (!tipo) {
            showNotification('No se encontró el tipo de documento', 'error');
            return;
        }
        document.getElementById('edit_tipo_id_serv').value = tipo.ID_TIPO_DOCUMENTO_SERVICIO;
        document.getElementById('edit_codigo_serv').value = tipo.CODIGO || '';
        document.getElementById('edit_nombre_serv').value = tipo.NOMBRE || '';
        document.getElementById('edit_descripcion_serv').value = tipo.DESCRIPCION || '';
        document.getElementById('edit_orden_serv').value = tipo.ORDEN != null ? tipo.ORDEN : '';
        const obl = tipo.OBLIGATORIO === 1 || tipo.OBLIGATORIO === true || tipo.OBLIGATORIO === '1';
        document.getElementById('edit_obligatorio_serv').value = obl ? '1' : '0';
        showModal('modalEditarTipoServicio');
    }

    function guardarEditarTipoServicio() {
        const id = document.getElementById('edit_tipo_id_serv').value;
        const codigo = document.getElementById('edit_codigo_serv').value.trim();
        const nombre = document.getElementById('edit_nombre_serv').value.trim();
        const descripcion = document.getElementById('edit_descripcion_serv').value.trim();
        const orden = document.getElementById('edit_orden_serv').value;
        const obligatorio = document.getElementById('edit_obligatorio_serv').value;

        if (!codigo || !/^PSC-\d{3}$/.test(codigo)) {
            showNotification('El código debe tener el formato PSC-XXX', 'error');
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

        fetch('<?= base_url('coord/documentos/servicio/actualizar-tipo/') ?>' + encodeURIComponent(id), {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    const modalEl = document.getElementById('modalEditarTipoServicio');
                    const inst = bootstrap.Modal.getInstance(modalEl);
                    if (inst) inst.hide();
                    setTimeout(() => location.reload(), 600);
                } else {
                    showNotification(data.message || 'No se pudo guardar', 'error');
                }
            })
            .catch(() => showNotification('Error de conexión al guardar', 'error'));
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

        fetch('<?= base_url('coord/documentos/servicio/crear-tipo') ?>', {
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
        fetch('<?= base_url('coord/documentos/servicio/subir-formato') ?>', {
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
            const archivo = item.archivo || '';
            const nombre = item.nombre || '';
            return `<tr>
                <td>${i + 1}</td>
                <td>${escapeHtmlServ(nombre)}</td>
                <td class="formatos-archivo-celda"><span class="text-muted formatos-archivo-texto" title="${escapeAttrServ(archivo)}">${escapeHtmlServ(archivo)}</span></td>
                <td>
                    <button type="button" class="btn btn-outline-secondary btn-sm me-1 btn-editar-nombre-formato-servicio" data-archivo="${escapeAttrServ(archivo)}" data-nombre="${escapeAttrServ(nombre)}" title="Editar nombre">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="eliminarDocFormatoServicio(${JSON.stringify(archivo)})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;
        }).join('');
    }

    function escapeAttrServ(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;');
    }

    function abrirModalEditarNombreFormatoServicio(archivo, nombre) {
        const modalEl = document.getElementById('modalEditarNombreFormatoServicio');
        if (!modalEl) return;
        const hid = document.getElementById('editFormatoServicioArchivo');
        const inp = document.getElementById('editFormatoServicioNombre');
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
        const btn = e.target.closest('.btn-editar-nombre-formato-servicio');
        if (!btn || !document.getElementById('tbodyFormatosServicio')?.contains(btn)) return;
        e.preventDefault();
        abrirModalEditarNombreFormatoServicio(btn.getAttribute('data-archivo') || '', btn.getAttribute('data-nombre') || '');
    });

    document.getElementById('formEditarNombreFormatoServicio')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const archivo = document.getElementById('editFormatoServicioArchivo').value;
        const nombre = document.getElementById('editFormatoServicioNombre').value.trim();
        if (!archivo || !nombre) {
            showNotification('Complete el nombre del documento.', 'error');
            return;
        }
        const btn = document.getElementById('btnGuardarNombreFormatoServicio');
        const fd = new FormData();
        fd.append('archivo', archivo);
        fd.append('nombre', nombre);
        if (btn) btn.disabled = true;
        fetch('<?= base_url('coord/documentos/servicio/actualizar-nombre-formato') ?>', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (data.success) {
                    showNotification(data.message, 'success');
                    actualizarTablaFormatosServicio(data.lista || []);
                    const modalEl = document.getElementById('modalEditarNombreFormatoServicio');
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

    function eliminarDocFormatoServicio(archivo) {
        if (!archivo || !confirm('¿Eliminar este documento de formato?')) return;
        fetch('<?= base_url('coord/documentos/servicio/eliminar-formato/') ?>' + encodeURIComponent(archivo), {
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
        if (window.location.hash === '#formatos-documentos-servicio') {
            const tabFormatos = document.getElementById('formatos-docs-tab-servicio');
            if (tabFormatos && typeof bootstrap !== 'undefined') {
                new bootstrap.Tab(tabFormatos).show();
            }
        }

        if (!tiposDocumentosCatalogoServicio || tiposDocumentosCatalogoServicio.length === 0) {
            showNotification('No hay tipos de documentos configurados. Contacte al coordinador.', 'warning');
        }

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
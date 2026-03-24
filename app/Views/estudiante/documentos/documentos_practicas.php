<?= $this->extend('estudiante/layouts/mainEstudiante') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para documentos de prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<style>
    .document-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
        border-radius: 15px;
        overflow: hidden;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #007bff;
    }

    .document-card.subido {
        border-color: #28a745;
    }

    .document-card.pendiente {
        border-color: #ffc107;
    }

    .document-card.aprobado {
        border-color: #28a745;
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    }

    .document-card.rechazado {
        border-color: #dc3545;
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    }

    .status-badge {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .upload-area:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }

    .upload-area.dragover {
        border-color: #007bff;
        background-color: #e3f2fd;
    }

    .view-mode-toggle {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 1rem;
    }

    .view-mode-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin: 0 0.5rem;
        font-weight: 600;
    }

    .document-view {
        display: none;
    }

    .document-view.active {
        display: block;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$practicasDocJs = array_map(static function (array $p): array {
    return [
        'id' => (int) ($p['ID_PRACTICA_PREPROFESIONAL'] ?? 0),
        'entidad' => (string) ($p['INSTITUCION_NOMBRE'] ?? ''),
        'tutor' => trim((string) ($p['SUPERVISOR_NOMBRE'] ?? '')),
    ];
}, $practicas_documentacion ?? []);
?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-file-alt me-2"></i>
                    Documentos de Prácticas Preprofesionales
                </h3>
                <p class="text-center text-muted">Sube los documentos requeridos conforme avances en tus prácticas</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card documentos-resumen-panel border shadow-sm">
                    <div class="card-body p-0">
                        <div class="documentos-resumen-seccion documentos-resumen-progreso px-3 px-md-4 py-3">
                            <h6 class="documentos-resumen-seccion-title mb-3">
                                <i class="fas fa-chart-line text-primary" aria-hidden="true"></i>
                                Progreso de documentos
                            </h6>
                            <div class="row align-items-center g-3">
                                <div class="col-lg-7">
                                    <div class="progress documentos-resumen-progress mb-2">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: <?= (int) ($estadisticas['porcentaje_completado'] ?? 0) ?>%"
                                            aria-valuenow="<?= (int) ($estadisticas['porcentaje_completado'] ?? 0) ?>"
                                            aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <p class="small text-muted mb-0"><?= (int) ($estadisticas['aprobados'] ?? 0) ?> de <?= (int) ($total_tipos_documentos ?? 0) ?> tipos aprobados · <span class="fw-medium text-body"><?= (int) ($estadisticas['porcentaje_completado'] ?? 0) ?>%</span></p>
                                </div>
                                <div class="col-lg-5">
                                    <div class="row g-2 text-center">
                                        <div class="col-3 documentos-resumen-stat">
                                            <div class="documentos-resumen-stat-num"><?= (int) ($estadisticas['total'] ?? 0) ?></div>
                                            <small>Total</small>
                                        </div>
                                        <div class="col-3 documentos-resumen-stat">
                                            <div class="documentos-resumen-stat-num text-success"><?= (int) ($estadisticas['aprobados'] ?? 0) ?></div>
                                            <small>Aprobados</small>
                                        </div>
                                        <div class="col-3 documentos-resumen-stat">
                                            <div class="documentos-resumen-stat-num text-warning"><?= (int) ($estadisticas['pendientes'] ?? 0) ?></div>
                                            <small>Pendientes</small>
                                        </div>
                                        <div class="col-3 documentos-resumen-stat">
                                            <div class="documentos-resumen-stat-num text-danger"><?= (int) ($estadisticas['rechazados'] ?? 0) ?></div>
                                            <small>Rechazados</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($practicas_documentacion) && count($practicas_documentacion) > 1): ?>
                            <hr class="documentos-resumen-hr">
                            <div class="documentos-resumen-seccion px-3 px-md-4 py-3">
                                <label class="form-label fw-semibold small mb-2" for="selectorPracticaDocumentacion">Práctica (archivos se asocian a esta vinculación)</label>
                                <select class="form-select form-select-sm" id="selectorPracticaDocumentacion">
                                    <?php foreach ($practicas_documentacion as $pp): ?>
                                        <option value="<?= (int) ($pp['ID_PRACTICA_PREPROFESIONAL'] ?? 0) ?>"
                                            data-entidad="<?= esc($pp['INSTITUCION_NOMBRE'] ?? '', 'attr') ?>"
                                            data-tutor="<?= esc(trim($pp['SUPERVISOR_NOMBRE'] ?? ''), 'attr') ?>">
                                            <?= esc($pp['INSTITUCION_NOMBRE'] ?: ('Práctica #' . (int) ($pp['ID_PRACTICA_PREPROFESIONAL'] ?? 0))) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <hr class="documentos-resumen-hr">

                        <div class="documentos-resumen-seccion px-3 px-md-4 py-3">
                            <h6 class="documentos-resumen-seccion-title mb-2">
                                <i class="fas fa-clipboard-list text-primary" aria-hidden="true"></i>
                                Datos de documentación de la práctica
                            </h6>
                            <p class="small text-muted mb-3">Entidad receptora y docente tutor; se envían al subir cada PDF si hay práctica registrada.</p>
                            <?php if (empty($practicas_documentacion)): ?>
                                <div class="alert alert-light border small mb-0 py-2">
                                    <i class="fas fa-info-circle text-primary me-1"></i>
                                    Aún no hay práctica vinculada o faltan datos de institución/instructor. Cuando el departamento registre tu asignación, verás la <strong>entidad</strong> y el <strong>tutor</strong>.
                                    <a href="<?= site_url('estudiante/practicas') ?>" class="alert-link">Ver prácticas</a>.
                                </div>
                            <?php else: ?>
                                <div class="row g-2 g-md-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Entidad receptora</label>
                                        <div class="form-control form-control-sm bg-light border rounded px-3 py-2" id="doc_vis_entidad" style="min-height: 38px;">—</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Docente tutor</label>
                                        <div class="form-control form-control-sm bg-light border rounded px-3 py-2" id="doc_vis_tutor" style="min-height: 38px;">—</div>
                                    </div>
                                </div>
                                <input type="hidden" form="formSubirDocumento" name="id_practica" id="doc_meta_id_practica" value="">
                                <input type="hidden" form="formSubirDocumento" name="entidad_receptora" id="doc_meta_entidad" value="">
                                <input type="hidden" form="formSubirDocumento" name="docente_tutor" id="doc_meta_tutor" value="">
                            <?php endif; ?>
                        </div>

                        <hr class="documentos-resumen-hr">

                        <?php
                            $documentos_aviso_tipo = 'preprofesional';
                            $documentos_aviso_integrado = true;
                            echo $this->include('estudiante/partials/documentos_aviso_importante');
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->include('estudiante/partials/asistencia_registro_estudiante') ?>

        <!-- Documentos Requeridos -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-list-check me-2"></i>
                            Documentos Requeridos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="view-mode-toggle">
                            <span class="view-mode-label">Cards</span>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" id="switchVistaDocumentos">
                            </div>
                            <span class="view-mode-label">Tabla</span>
                        </div>

                        <div id="vistaCards" class="document-view active">
                            <div class="row g-3">
                                <?php foreach ($tipos_documentos as $index => $tipo): ?>
                                    <?php
                                    $idTipoFila = (int) ($tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $tipo['ID_TIPO_DOCUMENTO'] ?? 0);
                                    $documentoEstudiante = null;
                                    foreach ($progreso as $doc) {
                                        $tid = (int) ($doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? 0);
                                        $idDocP = $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_DOCUMENTO_PRACTICA'] ?? null;
                                        if ($tid === $idTipoFila && $idDocP) {
                                            $documentoEstudiante = $doc;
                                            break;
                                        }
                                    }

                                    $estado = $documentoEstudiante ? $documentoEstudiante['ESTADO_REVISION'] : 'No subido';
                                    $claseCard = '';
                                    $iconoEstado = '';
                                    $colorEstado = '';

                                    switch ($estado) {
                                        case 'Aprobado':
                                            $claseCard = 'aprobado';
                                            $iconoEstado = 'fas fa-check-circle';
                                            $colorEstado = 'success';
                                            break;
                                        case 'Rechazado':
                                            $claseCard = 'rechazado';
                                            $iconoEstado = 'fas fa-times-circle';
                                            $colorEstado = 'danger';
                                            break;
                                        case 'En Revisión':
                                            $claseCard = 'pendiente';
                                            $iconoEstado = 'fas fa-eye';
                                            $colorEstado = 'info';
                                            break;
                                        case 'Requiere Corrección':
                                            $claseCard = 'pendiente';
                                            $iconoEstado = 'fas fa-exclamation-circle';
                                            $colorEstado = 'warning';
                                            break;
                                        case 'Pendiente':
                                            $claseCard = 'pendiente';
                                            $iconoEstado = 'fas fa-clock';
                                            $colorEstado = 'warning';
                                            break;
                                        default:
                                            $claseCard = '';
                                            $iconoEstado = 'fas fa-upload';
                                            $colorEstado = 'secondary';
                                    }
                                    $idDocCard = $documentoEstudiante
                                        ? (int) ($documentoEstudiante['ID_DOCUMENTO_PREPROFESIONAL'] ?? $documentoEstudiante['ID_DOCUMENTO_PRACTICA'] ?? 0)
                                        : 0;
                                    ?>

                                    <div class="col-md-6 col-lg-4">
                                        <div class="card document-card <?= $claseCard ?> h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="file-icon bg-primary me-3" style="width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fas fa-file-alt text-white"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></h6>
                                                        <small class="text-muted"><?= $tipo['DESCRIPCION'] ?></small>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <span class="status-badge bg-<?= $colorEstado ?> text-white">
                                                        <i class="<?= $iconoEstado ?> me-1"></i>
                                                        <?= $estado ?>
                                                    </span>
                                                    <?php if (!empty($tipo['REQUERIDO'] ?? $tipo['OBLIGATORIO'] ?? null)): ?>
                                                        <span class="badge bg-danger ms-2">Requerido</span>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if ($documentoEstudiante): ?>
                                                    <div class="mb-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            Subido: <?= date('d/m/Y', strtotime($documentoEstudiante['FECHA_SUBIDA'])) ?>
                                                        </small>
                                                        <?php if ($documentoEstudiante['OBSERVACIONES_REVISOR']): ?>
                                                            <div class="mt-2">
                                                                <small class="text-muted">
                                                                    <strong>Observaciones:</strong><br>
                                                                    <?= $documentoEstudiante['OBSERVACIONES_REVISOR'] ?>
                                                                </small>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="d-flex gap-2">
                                                    <?php if ($documentoEstudiante): ?>
                                                        <button class="btn btn-outline-primary btn-sm"
                                                            onclick="verDocumento(<?= $idDocCard ?>)"
                                                            title="Ver Documento">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm"
                                                            onclick="descargarDocumento(<?= $idDocCard ?>)"
                                                            title="Descargar">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <?php if ($estado != 'Aprobado'): ?>
                                                            <button class="btn btn-outline-danger btn-sm"
                                                                onclick="eliminarDocumento(<?= $idDocCard ?>)"
                                                                title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="mostrarModalSubir(<?= $idTipoFila ?>, '<?= esc($tipo['NOMBRE'], 'js') ?>')"
                                                            title="Subir Documento">
                                                            <i class="fas fa-upload me-1"></i>
                                                            Subir
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div id="vistaTabla" class="document-view">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center" style="width: 70px;">#</th>
                                            <th>Documento</th>
                                            <th class="text-center" style="width: 180px;">Estado</th>
                                            <th class="text-center" style="width: 160px;">Fecha</th>
                                            <th class="text-center" style="width: 220px;">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tipos_documentos as $index => $tipo): ?>
                                            <?php
                                            $idTipoFilaT = (int) ($tipo['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $tipo['ID_TIPO_DOCUMENTO'] ?? 0);
                                            $documentoEstudiante = null;
                                            foreach ($progreso as $doc) {
                                                $tid = (int) ($doc['ID_TIPO_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_TIPO_DOCUMENTO'] ?? 0);
                                                $idDocP = $doc['ID_DOCUMENTO_PREPROFESIONAL'] ?? $doc['ID_DOCUMENTO_PRACTICA'] ?? null;
                                                if ($tid === $idTipoFilaT && $idDocP) {
                                                    $documentoEstudiante = $doc;
                                                    break;
                                                }
                                            }

                                            $estado = $documentoEstudiante ? $documentoEstudiante['ESTADO_REVISION'] : 'No subido';
                                            $badgeEstado = 'secondary';
                                            if ($estado === 'Aprobado') {
                                                $badgeEstado = 'success';
                                            } elseif ($estado === 'Rechazado') {
                                                $badgeEstado = 'danger';
                                            } elseif ($estado === 'En Revisión') {
                                                $badgeEstado = 'info';
                                            } elseif ($estado === 'Pendiente' || $estado === 'Requiere Corrección') {
                                                $badgeEstado = 'warning text-dark';
                                            }
                                            $idDocTabla = $documentoEstudiante
                                                ? (int) ($documentoEstudiante['ID_DOCUMENTO_PREPROFESIONAL'] ?? $documentoEstudiante['ID_DOCUMENTO_PRACTICA'] ?? 0)
                                                : 0;
                                            ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $index + 1 ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?></div>
                                                    <small class="text-muted"><?= $tipo['DESCRIPCION'] ?></small>
                                                    <?php if (!empty($tipo['REQUERIDO'] ?? $tipo['OBLIGATORIO'] ?? null)): ?>
                                                        <span class="badge bg-danger ms-2">Requerido</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-<?= $badgeEstado ?>"><?= $estado ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($documentoEstudiante): ?>
                                                        <small><?= date('d/m/Y', strtotime($documentoEstudiante['FECHA_SUBIDA'])) ?></small>
                                                    <?php else: ?>
                                                        <small class="text-muted">-</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($documentoEstudiante): ?>
                                                        <button class="btn btn-outline-primary btn-sm me-1"
                                                            onclick="verDocumento(<?= $idDocTabla ?>)"
                                                            title="Ver Documento">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm me-1"
                                                            onclick="descargarDocumento(<?= $idDocTabla ?>)"
                                                            title="Descargar">
                                                            <i class="fas fa-download"></i>
                                                        </button>
                                                        <?php if ($estado != 'Aprobado'): ?>
                                                            <button class="btn btn-outline-danger btn-sm"
                                                                onclick="eliminarDocumento(<?= $idDocTabla ?>)"
                                                                title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="mostrarModalSubir(<?= $idTipoFilaT ?>, '<?= esc($tipo['NOMBRE'], 'js') ?>')"
                                                            title="Subir Documento">
                                                            <i class="fas fa-upload me-1"></i>Subir
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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

<!-- Modal Subir Documento -->
<div class="modal fade" id="modalSubirDocumento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Subir Documento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumento" enctype="multipart/form-data">
                    <input type="hidden" name="tipo_documento" id="tipo_documento_id">

                    <div class="mb-3">
                        <label class="form-label">Tipo de Documento</label>
                        <input type="text" class="form-control" id="tipo_documento_nombre" readonly>
                    </div>

                    <p class="small text-muted mb-3">
                        <?php if (!empty($practicas_documentacion)): ?>
                            La entidad receptora y el docente tutor se envían según la información mostrada arriba («Datos de documentación de la práctica»).
                        <?php else: ?>
                            Aún no hay entidad ni tutor asociados a tu práctica en el sistema; revisa con vinculación si corresponde.
                        <?php endif; ?>
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Arrastra y suelta tu archivo aquí</h5>
                            <p class="text-muted mb-3">o</p>
                            <input type="file" class="form-control" name="archivo" id="archivoInput"
                                accept=".pdf,application/pdf" required>
                            <small class="text-muted">Solo PDF. Máximo 10 MB.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Observaciones (Opcional)</label>
                        <textarea class="form-control" name="observaciones" rows="3"
                            placeholder="Observaciones adicionales sobre el documento..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="subirDocumento()">
                    <i class="fas fa-upload me-1"></i>Subir Documento
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var practicasDocMetaList = <?= json_encode($practicasDocJs ?? [], JSON_UNESCAPED_UNICODE) ?>;

    function syncDocPracticaMetaDesdeDom() {
        var idIn = document.getElementById('doc_meta_id_practica');
        var entIn = document.getElementById('doc_meta_entidad');
        var tutIn = document.getElementById('doc_meta_tutor');
        var ve = document.getElementById('doc_vis_entidad');
        var vt = document.getElementById('doc_vis_tutor');
        if (!idIn || !entIn || !tutIn) {
            return;
        }
        var sel = document.getElementById('selectorPracticaDocumentacion');
        var id = '';
        var ent = '';
        var tut = '';
        if (sel) {
            var opt = sel.options[sel.selectedIndex];
            if (opt) {
                id = opt.value || '';
                ent = opt.getAttribute('data-entidad') || '';
                tut = opt.getAttribute('data-tutor') || '';
            }
        } else if (practicasDocMetaList && practicasDocMetaList.length === 1) {
            id = String(practicasDocMetaList[0].id || '');
            ent = practicasDocMetaList[0].entidad || '';
            tut = practicasDocMetaList[0].tutor || '';
        }
        idIn.value = id;
        entIn.value = ent;
        tutIn.value = tut;
        if (ve) {
            ve.textContent = ent.trim() ? ent : '—';
        }
        if (vt) {
            vt.textContent = tut.trim() ? tut : '—';
        }
    }

    function mostrarModalSubir(tipoId, tipoNombre) {
        document.getElementById('tipo_documento_id').value = tipoId;
        document.getElementById('tipo_documento_nombre').value = tipoNombre;
        syncDocPracticaMetaDesdeDom();

        const modal = new bootstrap.Modal(document.getElementById('modalSubirDocumento'));
        modal.show();
    }

    function subirDocumento() {
        syncDocPracticaMetaDesdeDom();
        const form = document.getElementById('formSubirDocumento');
        const formData = new FormData(form);

        const archivo = document.getElementById('archivoInput').files[0];
        if (!archivo) {
            showNotification('Debes seleccionar un archivo', 'error');
            return;
        }

        // Mostrar loading
        const btnSubir = document.querySelector('#modalSubirDocumento .btn-primary');
        const textoOriginal = btnSubir.innerHTML;
        btnSubir.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Subiendo...';
        btnSubir.disabled = true;

        fetch('<?= base_url('estudiante/documentos-practicas/subir') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumento')).hide();
                    form.reset();
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
                showNotification('Error al subir el documento', 'error');
            })
            .finally(() => {
                btnSubir.innerHTML = textoOriginal;
                btnSubir.disabled = false;
            });
    }

    function verDocumento(id) {
        window.open('<?= base_url('estudiante/documentos-practicas/descargar') ?>/' + id, '_blank');
    }

    function descargarDocumento(id) {
        window.location.href = '<?= base_url('estudiante/documentos-practicas/descargar') ?>/' + id;
    }

    function eliminarDocumento(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
            fetch('<?= base_url('estudiante/documentos-practicas/eliminar') ?>/' + id, {
                    method: 'POST'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
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
            <div class="alert alert-${type} alert-dismissible fade show" role="alert" 
                 style="background: ${colors[type]}; color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2);">
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

    // Drag and Drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        syncDocPracticaMetaDesdeDom();
        var selPr = document.getElementById('selectorPracticaDocumentacion');
        if (selPr) {
            selPr.addEventListener('change', syncDocPracticaMetaDesdeDom);
        }

        const switchVista = document.getElementById('switchVistaDocumentos');
        const vistaCards = document.getElementById('vistaCards');
        const vistaTabla = document.getElementById('vistaTabla');

        function aplicarVista(esTabla) {
            if (!vistaCards || !vistaTabla || !switchVista) return;
            vistaCards.classList.toggle('active', !esTabla);
            vistaTabla.classList.toggle('active', esTabla);
            switchVista.checked = esTabla;
            localStorage.setItem('vistaDocumentosPracticas', esTabla ? 'tabla' : 'cards');
        }

        if (switchVista && vistaCards && vistaTabla) {
            const vistaGuardada = localStorage.getItem('vistaDocumentosPracticas');
            aplicarVista(vistaGuardada === 'tabla');
            switchVista.addEventListener('change', function() {
                aplicarVista(this.checked);
            });
        }

        const uploadArea = document.getElementById('uploadArea');
        const archivoInput = document.getElementById('archivoInput');

        // Drag and drop events
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
                // Trigger change event
                const event = new Event('change', {
                    bubbles: true
                });
                archivoInput.dispatchEvent(event);
            }
        });

        // File input change event
        archivoInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const file = this.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);

                // Update upload area with file info
                uploadArea.innerHTML = `
                    <i class="fas fa-file fa-3x text-primary mb-3"></i>
                    <h5 class="text-primary">${file.name}</h5>
                    <p class="text-muted mb-2">Tamaño: ${fileSize} MB</p>
                    <small class="text-muted">Archivo seleccionado correctamente</small>
                `;
            }
        });
    });
</script>
<?= $this->include('estudiante/partials/asistencia_registro_estudiante_script') ?>
<?= $this->endSection() ?>


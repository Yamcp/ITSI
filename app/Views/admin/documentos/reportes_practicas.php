<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<style>
    .filtros-container {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .estadisticas-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .export-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .badge-tipo {
        font-size: 0.8rem;
        padding: 0.5rem 0.8rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Reportes de Documentos de Prácticas
                    </h3>
                    <a href="<?= base_url('admin/documentos/practicas') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="estadisticas-card">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['total'] ?? 0 ?></h2>
                            <p class="mb-0">Total Documentos</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['aprobados'] ?? 0 ?></h2>
                            <p class="mb-0">Aprobados</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['pendientes'] ?? 0 ?></h2>
                            <p class="mb-0">Pendientes</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['rechazados'] ?? 0 ?></h2>
                            <p class="mb-0">Rechazados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="filtros-container">
                    <h5 class="mb-3">
                        <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
                    </h5>
                    <form method="GET" action="<?= base_url('admin/documentos/practicas/reportes') ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo de Documento</label>
                                <select class="form-select" name="tipo_documento">
                                    <option value="">Todos los tipos</option>
                                    <?php if (isset($tipos_documentos)): ?>
                                        <?php foreach ($tipos_documentos as $tipo): ?>
                                            <option value="<?= $tipo['ID_TIPO_DOCUMENTO'] ?>"
                                                <?= (isset($filtros['tipo_documento']) && $filtros['tipo_documento'] == $tipo['ID_TIPO_DOCUMENTO']) ? 'selected' : '' ?>>
                                                <?= $tipo['CODIGO'] ?>. <?= $tipo['NOMBRE'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado de Revisión</label>
                                <select class="form-select" name="estado_revision">
                                    <option value="">Todos los estados</option>
                                    <?php if (isset($estados_revision)): ?>
                                        <?php foreach ($estados_revision as $estado): ?>
                                            <option value="<?= $estado['ID_ESTADO_REVISION'] ?>"
                                                <?= (isset($filtros['estado_revision']) && $filtros['estado_revision'] == $estado['ID_ESTADO_REVISION']) ? 'selected' : '' ?>>
                                                <?= $estado['ESTADO'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Docente Tutor</label>
                                <select class="form-select" name="docente_tutor">
                                    <option value="">Todos los docentes</option>
                                    <option value="1" <?= (isset($filtros['docente_tutor']) && $filtros['docente_tutor'] == '1') ? 'selected' : '' ?>>Dr. tenegro - Rector</option>
                                    <option value="2" <?= (isset($filtros['docente_tutor']) && $filtros['docente_tutor'] == '2') ? 'selected' : '' ?>>Ing. Juan Pérez - Coordinador</option>
                                    <option value="3" <?= (isset($filtros['docente_tutor']) && $filtros['docente_tutor'] == '3') ? 'selected' : '' ?>>Mg. María González - Tutora</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio"
                                    value="<?= $filtros['fecha_inicio'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin"
                                    value="<?= $filtros['fecha_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Entidad Receptora</label>
                                <input type="text" class="form-control" name="entidad_receptora"
                                    placeholder="Buscar por entidad..." value="<?= $filtros['entidad_receptora'] ?? '' ?>">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Filtrar
                                </button>
                                <a href="<?= base_url('admin/documentos/practicas/reportes') ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Opciones de Exportación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-download me-2"></i>Opciones de Exportación
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="export-buttons">
                            <a href="<?= base_url('admin/documentos/practicas/exportar/pdf') . '?' . http_build_query($filtros ?? []) ?>"
                                class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                            <a href="<?= base_url('admin/documentos/practicas/exportar/excel') . '?' . http_build_query($filtros ?? []) ?>"
                                class="btn btn-outline-success">
                                <i class="fas fa-file-excel me-1"></i>Exportar Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de Resultados -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-table me-2"></i>Resultados del Reporte
                        </h6>
                        <span class="badge bg-light text-dark">
                            <?= count($documentos ?? []) ?> registros
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($documentos)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Documento</th>
                                            <th>Tipo</th>
                                            <th>Estudiante</th>
                                            <th>Entidad Receptora</th>
                                            <th>Docente Tutor</th>
                                            <th>Estado</th>
                                            <th>Fecha Subida</th>
                                            <th>Prioridad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($documentos as $documento): ?>
                                            <tr>
                                                <td><?= $documento['ID_DOCUMENTO_PRACTICA'] ?? $documento['id'] ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?= $documento['NOMBRE_ARCHIVO'] ?? $documento['NOMBRE_DOCUMENTO'] ?? $documento['nombre'] ?? 'Sin nombre' ?></div>
                                                    <small class="text-muted"><?= substr($documento['DESCRIPCION'] ?? $documento['descripcion'] ?? '', 0, 50) ?>...</small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $tipo = $documento['TIPO_DOCUMENTO'] ?? $documento['tipo'] ?? 'General';
                                                    $color = 'bg-primary';
                                                    if (strpos($tipo, 'Oficio') !== false) $color = 'bg-success';
                                                    elseif (strpos($tipo, 'Carta') !== false) $color = 'bg-warning';
                                                    elseif (strpos($tipo, 'Certificado') !== false) $color = 'bg-info';
                                                    elseif (strpos($tipo, 'Rúbrica') !== false) $color = 'bg-danger';
                                                    ?>
                                                    <span class="badge <?= $color ?> badge-tipo"><?= $tipo ?></span>
                                                </td>
                                                <td>
                                                    <div><?= ($documento['NOMBRE_ESTUDIANTE'] ?? '') . ' ' . ($documento['APELLIDO_ESTUDIANTE'] ?? '') ?: ($documento['estudiante'] ?? 'N/A') ?></div>
                                                    <small class="text-muted"><?= $documento['CEDULA_ESTUDIANTE'] ?? $documento['cedula'] ?? '' ?></small>
                                                </td>
                                                <td><?= $documento['ENTIDAD_RECEPTORA'] ?? $documento['entidad'] ?? 'N/A' ?></td>
                                                <td><?= $documento['DOCENTE_TUTOR'] ?? $documento['docente'] ?? 'N/A' ?></td>
                                                <td>
                                                    <?php
                                                    $estado = $documento['ESTADO_REVISION'] ?? $documento['estado'] ?? 'Pendiente';
                                                    $estadoColor = 'bg-secondary';
                                                    if ($estado === 'Aprobado') $estadoColor = 'bg-success';
                                                    elseif ($estado === 'En Revisión') $estadoColor = 'bg-warning text-dark';
                                                    elseif ($estado === 'Rechazado') $estadoColor = 'bg-danger';
                                                    elseif ($estado === 'Requiere Corrección') $estadoColor = 'bg-info';
                                                    ?>
                                                    <span class="badge <?= $estadoColor ?>"><?= $estado ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $fecha = $documento['FECHA_SUBIDA'] ?? $documento['fecha_subida'] ?? null;
                                                    if ($fecha) {
                                                        echo date('d/m/Y H:i', strtotime($fecha));
                                                    } else {
                                                        echo '<span class="text-muted">No subido</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $prioridad = $documento['PRIORIDAD'] ?? $documento['prioridad'] ?? 'media';
                                                    $prioridadColor = 'bg-secondary';
                                                    if ($prioridad === 'alta') $prioridadColor = 'bg-danger';
                                                    elseif ($prioridad === 'media') $prioridadColor = 'bg-warning text-dark';
                                                    elseif ($prioridad === 'baja') $prioridadColor = 'bg-success';
                                                    elseif ($prioridad === 'urgente') $prioridadColor = 'bg-dark';
                                                    ?>
                                                    <span class="badge <?= $prioridadColor ?>"><?= ucfirst($prioridad) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron documentos</h5>
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
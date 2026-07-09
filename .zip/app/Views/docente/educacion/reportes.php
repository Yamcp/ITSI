<?= $this->extend('docente/layouts/mainDocente') ?>

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
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>
                        Mis Reportes de Actividades
                    </h3>
                    <a href="<?= base_url('docente/actividades-educacion') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="estadisticas-card">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= count($actividades) ?></h2>
                            <p class="mb-0">Total Actividades</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= array_sum(array_column($actividades, 'DURACION_HORAS')) ?></h2>
                            <p class="mb-0">Horas Totales</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= count(array_unique(array_column($actividades, 'ID_INSTRUCTOR'))) ?></h2>
                            <p class="mb-0">Instructores</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= count(array_unique(array_column($actividades, 'MODALIDAD'))) ?></h2>
                            <p class="mb-0">Modalidades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row">
            <div class="col-12">
                <div class="filtros-container">
                    <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filtros</h5>
                    <form method="GET" action="<?= base_url('docente/actividades-educacion/reportes') ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo de Actividad</label>
                                <select class="form-select" name="tipo_actividad">
                                    <option value="">Todos</option>
                                    <?php foreach ($tipos_actividades as $tipo): ?>
                                        <option value="<?= $tipo['ID_TIPO_ACTIVIDAD'] ?>" <?= (isset($filtros['tipo_actividad']) && $filtros['tipo_actividad'] == $tipo['ID_TIPO_ACTIVIDAD']) ? 'selected' : '' ?>><?= esc($tipo['ACTIVIDAD']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Modalidad</label>
                                <select class="form-select" name="modalidad">
                                    <option value="">Todas</option>
                                    <?php foreach ($modalidades as $modalidad): ?>
                                        <option value="<?= $modalidad['ID_TIPO_MODALIDAD'] ?>" <?= (isset($filtros['modalidad']) && $filtros['modalidad'] == $modalidad['ID_TIPO_MODALIDAD']) ? 'selected' : '' ?>><?= esc($modalidad['MODALIDAD']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Instructor</label>
                                <select class="form-select" name="instructor">
                                    <option value="">Todos</option>
                                    <?php foreach ($instructores as $instructor): ?>
                                        <option value="<?= $instructor['ID_INSTRUCTOR'] ?>" <?= (isset($filtros['instructor']) && $filtros['instructor'] == $instructor['ID_INSTRUCTOR']) ? 'selected' : '' ?>><?= esc($instructor['NOMBRE'] ?? '') ?> <?= esc($instructor['APELLIDO'] ?? '') ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" value="<?= $filtros['fecha_inicio'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" value="<?= $filtros['fecha_fin'] ?? '' ?>">
                            </div>
                            <div class="col-md-9 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-search me-1"></i>Filtrar</button>
                                <a href="<?= base_url('docente/actividades-educacion/reportes') ?>" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Exportación -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-download me-2"></i>Exportar mis reportes</h6>
                    </div>
                    <div class="card-body">
                        <div class="export-buttons">
                            <a href="<?= base_url('docente/actividades-educacion/exportar/pdf') . '?' . http_build_query(array_filter($filtros)) ?>" class="btn btn-outline-danger" target="_blank">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                            <a href="<?= base_url('docente/actividades-educacion/exportar/excel') . '?' . http_build_query(array_filter($filtros)) ?>" class="btn btn-outline-success" target="_blank">
                                <i class="fas fa-file-excel me-1"></i>Exportar Excel
                            </a>
                            <a href="<?= base_url('docente/actividades-educacion/exportar/csv') . '?' . http_build_query(array_filter($filtros)) ?>" class="btn btn-outline-info" target="_blank">
                                <i class="fas fa-file-csv me-1"></i>Exportar CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-table me-2"></i>Resultados</h6>
                        <span class="badge bg-light text-dark"><?= count($actividades) ?> registros</span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($actividades)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Actividad</th>
                                            <th>Tipo</th>
                                            <th>Instructor</th>
                                            <th>Modalidad</th>
                                            <th>Período</th>
                                            <th>Duración</th>
                                            <th>Lugar</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($actividades as $actividad): ?>
                                            <tr>
                                                <td><?= $actividad['ID_ACTIVIDAD_EDUCACION'] ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?= esc($actividad['NOMBRE_ACTIVIDAD']) ?></div>
                                                    <small class="text-muted"><?= esc(substr($actividad['DESCRIPCION'] ?? '', 0, 50)) ?>...</small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $color = 'bg-primary';
                                                    if (($actividad['ACTIVIDAD'] ?? '') === 'Taller') $color = 'bg-success';
                                                    elseif (($actividad['ACTIVIDAD'] ?? '') === 'Seminario') $color = 'bg-info';
                                                    ?>
                                                    <span class="badge <?= $color ?> badge-tipo"><?= esc($actividad['ACTIVIDAD'] ?? '-') ?></span>
                                                </td>
                                                <td>
                                                    <div><?= esc($actividad['NOMBRE'] ?? '') ?> <?= esc($actividad['APELLIDO'] ?? '') ?></div>
                                                    <small class="text-muted"><?= esc($actividad['ESPECIALIDAD'] ?? '') ?></small>
                                                </td>
                                                <td><span class="badge bg-secondary"><?= esc($actividad['MODALIDAD'] ?? '') ?></span></td>
                                                <td>
                                                    <div><?= date('d/m/Y', strtotime($actividad['FECHA_INICIO'])) ?></div>
                                                    <small class="text-muted">hasta <?= date('d/m/Y', strtotime($actividad['FECHA_FIN'])) ?></small>
                                                </td>
                                                <td><span class="badge bg-warning text-dark"><?= (int)($actividad['DURACION_HORAS']) ?>h</span></td>
                                                <td><?= esc($actividad['LUGAR'] ?? '') ?></td>
                                                <td>
                                                    <?php
                                                    $fechaFin = new DateTime($actividad['FECHA_FIN']);
                                                    $hoy = new DateTime();
                                                    echo $fechaFin >= $hoy ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">Finalizado</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron actividades</h5>
                                <p class="text-muted">Ajusta los filtros o crea actividades desde Mis Actividades.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
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
    
    .estado-vigente {
        background-color: #d4edda;
        color: #155724;
    }
    
    .estado-por-vencer {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .estado-vencido {
        background-color: #f8d7da;
        color: #721c24;
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
                        Reportes de Convenios
                    </h3>
                    <a href="<?= base_url('admin/convenios') ?>" class="btn btn-outline-secondary">
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
                            <h2 class="mb-1"><?= $estadisticas['total'] ?></h2>
                            <p class="mb-0">Total Convenios</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['vigentes'] ?></h2>
                            <p class="mb-0">Vigentes</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['por_vencer'] ?></h2>
                            <p class="mb-0">Por Vencer</p>
                        </div>
                        <div class="col-md-3">
                            <h2 class="mb-1"><?= $estadisticas['vencidos'] ?></h2>
                            <p class="mb-0">Vencidos</p>
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
                    <form method="GET" action="<?= base_url('admin/convenios/reportes') ?>">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo de Convenio</label>
                                <select class="form-select" name="tipo_convenio">
                                    <option value="">Todos los tipos</option>
                                    <?php foreach ($tipos_convenios as $tipo): ?>
                                        <option value="<?= $tipo['ID_TIPO_CONVENIO'] ?>" 
                                                <?= (isset($filtros['tipo_convenio']) && $filtros['tipo_convenio'] == $tipo['ID_TIPO_CONVENIO']) ? 'selected' : '' ?>>
                                            <?= $tipo['CONVENIO'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Tipo de Institución</label>
                                <select class="form-select" name="tipo_institucion">
                                    <option value="">Todos los tipos</option>
                                    <option value="1" <?= (isset($filtros['tipo_institucion']) && $filtros['tipo_institucion'] == '1') ? 'selected' : '' ?>>Pública</option>
                                    <option value="2" <?= (isset($filtros['tipo_institucion']) && $filtros['tipo_institucion'] == '2') ? 'selected' : '' ?>>Privada</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Estado</label>
                                <select class="form-select" name="estado">
                                    <option value="">Todos los estados</option>
                                    <option value="vigente" <?= (isset($filtros['estado']) && $filtros['estado'] == 'vigente') ? 'selected' : '' ?>>Vigente</option>
                                    <option value="por_vencer" <?= (isset($filtros['estado']) && $filtros['estado'] == 'por_vencer') ? 'selected' : '' ?>>Por Vencer</option>
                                    <option value="vencido" <?= (isset($filtros['estado']) && $filtros['estado'] == 'vencido') ? 'selected' : '' ?>>Vencido</option>
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
                                <label class="form-label">Renovable</label>
                                <select class="form-select" name="renovable">
                                    <option value="">Todos</option>
                                    <option value="1" <?= (isset($filtros['renovable']) && $filtros['renovable'] == '1') ? 'selected' : '' ?>>Sí</option>
                                    <option value="0" <?= (isset($filtros['renovable']) && $filtros['renovable'] == '0') ? 'selected' : '' ?>>No</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Filtrar
                                </button>
                                <a href="<?= base_url('admin/convenios/reportes') ?>" class="btn btn-outline-secondary">
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
                            <a href="<?= base_url('admin/convenios/generarReporte') . '?formato=pdf&' . http_build_query($filtros) ?>" 
                               class="btn btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i>Exportar PDF
                            </a>
                            <a href="<?= base_url('admin/convenios/generarReporte') . '?formato=excel&' . http_build_query($filtros) ?>" 
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
                            <?= count($convenios) ?> registros
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <?php if (!empty($convenios)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Institución</th>
                                            <th>Tipo</th>
                                            <th>Período</th>
                                            <th>Duración</th>
                                            <th>Estado</th>
                                            <th>Renovable</th>
                                            <th>Objetivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($convenios as $convenio): ?>
                                            <tr>
                                                <td><?= $convenio['ID_DETALLE_CONVENIO'] ?></td>
                                                <td>
                                                    <div class="fw-semibold"><?= $convenio['NOMBRE'] ?></div>
                                                    <small class="text-muted"><?= $convenio['RUC'] ?> - <?= $convenio['TIPO_INSTITUCION'] ?></small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $color = 'bg-primary';
                                                    if ($convenio['TIPO_CONVENIO'] === 'Preprofesional') $color = 'bg-primary';
                                                    elseif ($convenio['TIPO_CONVENIO'] === 'Servicio Comunitario') $color = 'bg-success';
                                                    elseif ($convenio['TIPO_CONVENIO'] === 'Mixta') $color = 'bg-info';
                                                    ?>
                                                    <span class="badge <?= $color ?> badge-tipo"><?= $convenio['TIPO_CONVENIO'] ?></span>
                                                </td>
                                                <td>
                                                    <div><?= date('d/m/Y', strtotime($convenio['FECHA_INICIO'])) ?></div>
                                                    <small class="text-muted">hasta <?= date('d/m/Y', strtotime($convenio['FECHA_FIN'])) ?></small>
                                                </td>
                                                <td><span class="badge bg-warning text-dark"><?= $convenio['DURACION'] ?> meses</span></td>
                                                <td>
                                                    <?php 
                                                    $fechaActual = date('Y-m-d');
                                                    $fechaLimite = date('Y-m-d', strtotime('+30 days'));
                                                    if ($convenio['FECHA_FIN'] < $fechaActual) {
                                                        $estado = 'Vencido';
                                                        $clase = 'estado-vencido';
                                                    } elseif ($convenio['FECHA_FIN'] <= $fechaLimite) {
                                                        $estado = 'Por Vencer';
                                                        $clase = 'estado-por-vencer';
                                                    } else {
                                                        $estado = 'Vigente';
                                                        $clase = 'estado-vigente';
                                                    }
                                                    ?>
                                                    <span class="badge <?= $clase ?>"><?= $estado ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge <?= $convenio['RENOVABLE'] ? 'bg-success' : 'bg-secondary' ?>">
                                                        <?= $convenio['RENOVABLE'] ? 'Sí' : 'No' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" title="<?= $convenio['OBJETIVO'] ?>">
                                                        <?= substr($convenio['OBJETIVO'], 0, 50) ?>...
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron convenios</h5>
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

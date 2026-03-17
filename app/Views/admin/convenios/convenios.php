<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para convenios -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/convenios.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header de Convenios -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-handshake me-2"></i>
                    Gestión de Convenios
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas en Cuadros -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalConvenios" style="font-size:2.5rem;"><?= $estadisticas['total'] ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Convenios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="conveniosVigentes" style="font-size:2.5rem;"><?= $estadisticas['vigentes'] ?></h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Vigentes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="conveniosPorVencer" style="font-size:2.5rem;"><?= $estadisticas['por_vencer'] ?></h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Por Vencer</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 80%, #a71e2a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="conveniosVencidos" style="font-size:2.5rem;"><?= $estadisticas['vencidos'] ?></h2>
                        <p class="card-text fw-bold" style="color: #ffe6e6;">Vencidos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas en Tarjetas Separadas -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevoConvenio')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-plus-circle fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nuevo Convenio</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevaInstitucion')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-building fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Nueva Institución</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="<?= base_url('admin/convenios/reportes') ?>" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Ver Reportes</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportData()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body pb-0">
                    <ul class="nav nav-tabs nav-justified rounded-pill bg-light px-2 py-1" id="conveniosTabs" role="tablist" style="gap: 0.5rem;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-semibold text-primary" id="preprofesionales-tab" data-bs-toggle="tab" data-bs-target="#preprofesionales" type="button" role="tab" aria-selected="true" style="transition: background 0.2s;">
                                <i class="fas fa-building me-2"></i>
                                Preprofesionales
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-semibold text-success" id="servicio-tab" data-bs-toggle="tab" data-bs-target="#servicio" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                <i class="fas fa-heart me-2"></i>
                                Servicio Comunitario
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-semibold text-info" id="mixta-tab" data-bs-toggle="tab" data-bs-target="#mixta" type="button" role="tab" aria-selected="false" style="transition: background 0.2s;">
                                <i class="fas fa-link me-2"></i>
                                Mixta
                            </button>
                        </li>
                    </ul>
                    <hr class="mt-0 mb-2" style="border-top: 2px solid #e3e6f0;">

                    <!-- Contenido de las pestañas -->
                    <div class="tab-content mt-3" id="conveniosTabContent">
                        <!-- Convenios Preprofesionales -->
                        <div class="tab-pane fade show active" id="preprofesionales" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-building me-2"></i>
                                        Convenios Preprofesionales
                                    </span>
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
                                                    <th>Institución</th>
                                                    <th>RUC</th>
                                                    <th>Período</th>
                                                    <th>Duración</th>
                                                    <th>Estado</th>
                                                    <th>Renovable</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaPreprofesionales">
                                                <?php 
                                                $preprofesionales = array_filter($convenios, function($c) { return $c['ID_TIPO_CONVENIO'] == 1; });
                                                if (empty($preprofesionales)): ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted py-4">
                                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                            No hay convenios preprofesionales registrados
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach($preprofesionales as $convenio): ?>
                                                        <tr>
                                                            <td><?= $convenio['ID_DETALLE_CONVENIO'] ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-building fa-2x me-2 text-primary"></i>
                                                                    <div>
                                                                        <div class="fw-semibold"><?= $convenio['NOMBRE'] ?></div>
                                                                        <small class="text-muted"><?= $convenio['TIPO_INSTITUCION'] ?></small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><?= $convenio['RUC'] ?></td>
                                                            <td>
                                                                <div><?= date('M Y', strtotime($convenio['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($convenio['FECHA_FIN'])) ?></div>
                                                                <small class="text-muted"><?= $convenio['DURACION'] ?> meses</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info"><?= $convenio['DURACION'] ?> meses</span>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                $fechaActual = date('Y-m-d');
                                                                $fechaLimite = date('Y-m-d', strtotime('+30 days'));
                                                                if ($convenio['FECHA_FIN'] < $fechaActual) {
                                                                    $estado = 'Vencido';
                                                                    $clase = 'bg-danger';
                                                                } elseif ($convenio['FECHA_FIN'] <= $fechaLimite) {
                                                                    $estado = 'Por Vencer';
                                                                    $clase = 'bg-warning text-dark';
                                                                } else {
                                                                    $estado = 'Vigente';
                                                                    $clase = 'bg-success';
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
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary" onclick="verDetalle(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Ver Detalle">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-warning" onclick="editarConvenio(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-success" onclick="descargarConvenio(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Descargar">
                                                                        <i class="fas fa-download"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Servicio Comunitario -->
                        <div class="tab-pane fade" id="servicio" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-heart me-2"></i>
                                        Convenios de Servicio Comunitario
                                    </span>
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
                                                    <th>Institución</th>
                                                    <th>RUC</th>
                                                    <th>Período</th>
                                                    <th>Duración</th>
                                                    <th>Estado</th>
                                                    <th>Renovable</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaServicio">
                                                <?php 
                                                $servicio = array_filter($convenios, function($c) { return $c['ID_TIPO_CONVENIO'] == 2; });
                                                if (empty($servicio)): ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted py-4">
                                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                            No hay convenios de servicio comunitario registrados
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach($servicio as $convenio): ?>
                                                        <tr>
                                                            <td><?= $convenio['ID_DETALLE_CONVENIO'] ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-hands-helping fa-2x me-2 text-success"></i>
                                                                    <div>
                                                                        <div class="fw-semibold"><?= $convenio['NOMBRE'] ?></div>
                                                                        <small class="text-muted"><?= $convenio['TIPO_INSTITUCION'] ?></small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><?= $convenio['RUC'] ?></td>
                                                            <td>
                                                                <div><?= date('M Y', strtotime($convenio['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($convenio['FECHA_FIN'])) ?></div>
                                                                <small class="text-muted"><?= $convenio['DURACION'] ?> meses</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info"><?= $convenio['DURACION'] ?> meses</span>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                $fechaActual = date('Y-m-d');
                                                                $fechaLimite = date('Y-m-d', strtotime('+30 days'));
                                                                if ($convenio['FECHA_FIN'] < $fechaActual) {
                                                                    $estado = 'Vencido';
                                                                    $clase = 'bg-danger';
                                                                } elseif ($convenio['FECHA_FIN'] <= $fechaLimite) {
                                                                    $estado = 'Por Vencer';
                                                                    $clase = 'bg-warning text-dark';
                                                                } else {
                                                                    $estado = 'Vigente';
                                                                    $clase = 'bg-success';
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
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary" onclick="verDetalle(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Ver Detalle">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-warning" onclick="editarConvenio(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-info" onclick="renovarConvenio(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Renovar">
                                                                        <i class="fas fa-sync-alt"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Convenios Mixtos -->
                        <div class="tab-pane fade" id="mixta" role="tabpanel">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-link me-2"></i>
                                        Convenios Mixtos
                                    </span>
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
                                                    <th>Institución</th>
                                                    <th>RUC</th>
                                                    <th>Período</th>
                                                    <th>Duración</th>
                                                    <th>Estado</th>
                                                    <th>Renovable</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tablaMixta">
                                                <?php 
                                                $mixta = array_filter($convenios, function($c) { return $c['ID_TIPO_CONVENIO'] == 3; });
                                                if (empty($mixta)): ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted py-4">
                                                            <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                            No hay convenios mixtos registrados
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach($mixta as $convenio): ?>
                                                        <tr>
                                                            <td><?= $convenio['ID_DETALLE_CONVENIO'] ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-industry fa-2x me-2 text-info"></i>
                                                                    <div>
                                                                        <div class="fw-semibold"><?= $convenio['NOMBRE'] ?></div>
                                                                        <small class="text-muted"><?= $convenio['TIPO_INSTITUCION'] ?></small>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><?= $convenio['RUC'] ?></td>
                                                            <td>
                                                                <div><?= date('M Y', strtotime($convenio['FECHA_INICIO'])) ?> - <?= date('M Y', strtotime($convenio['FECHA_FIN'])) ?></div>
                                                                <small class="text-muted"><?= $convenio['DURACION'] ?> meses</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-info"><?= $convenio['DURACION'] ?> meses</span>
                                                            </td>
                                                            <td>
                                                                <?php 
                                                                $fechaActual = date('Y-m-d');
                                                                $fechaLimite = date('Y-m-d', strtotime('+30 days'));
                                                                if ($convenio['FECHA_FIN'] < $fechaActual) {
                                                                    $estado = 'Vencido';
                                                                    $clase = 'bg-danger';
                                                                } elseif ($convenio['FECHA_FIN'] <= $fechaLimite) {
                                                                    $estado = 'Por Vencer';
                                                                    $clase = 'bg-warning text-dark';
                                                                } else {
                                                                    $estado = 'Vigente';
                                                                    $clase = 'bg-success';
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
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-outline-primary" onclick="verDetalle(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Ver Detalle">
                                                                        <i class="fas fa-eye"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-warning" onclick="editarConvenio(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                    <button class="btn btn-outline-success" onclick="descargarConvenio(<?= $convenio['ID_DETALLE_CONVENIO'] ?>)" title="Descargar">
                                                                        <i class="fas fa-download"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
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

<!-- Modal Nuevo Convenio -->
<div class="modal fade" id="modalNuevoConvenio" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nuevo Convenio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                </div>
                <form id="formNuevoConvenio" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Convenio<span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_convenio" id="tipo_convenio" required>
                                    <option value="">Seleccionar...</option>
                                    <?php foreach($tipos_convenios as $tipo): ?>
                                        <option value="<?= $tipo['ID_TIPO_CONVENIO'] ?>"><?= $tipo['CONVENIO'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" id="error_tipo_convenio"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Institución<span class="text-danger">*</span></label>
                                <div id="institucionContainer">
                                    <div class="input-group">
                                        <select class="form-select" name="institucion" id="selectInstitucion" required>
                                            <option value="">Seleccionar institución...</option>
                                            <?php foreach($instituciones as $institucion): ?>
                                                <option value="<?= $institucion['ID_INSTITUCION_CONVENIO'] ?>">
                                                    <?= $institucion['NOMBRE'] ?> (<?= $institucion['TIPO_INSTITUCION'] ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button class="btn btn-outline-primary" type="button" onclick="agregarInstitucionDesdeConvenio()" title="Agregar nueva institución">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Selecciona una institución existente o agrega una nueva</small>
                                    <div class="invalid-feedback" id="error_institucion"></div>
                                </div>
                                <div id="noInstitucionesContainer" class="d-none">
                                    <div class="alert alert-info mb-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No hay instituciones registradas
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="irANuevaInstitucion()">
                                        <i class="fas fa-plus me-1"></i>
                                        Agregar Primera Institución
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                                <div class="invalid-feedback" id="error_fecha_inicio"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" required>
                                <div class="invalid-feedback" id="error_fecha_fin"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Duración (meses)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duracion" id="duracion" min="1" max="60" required readonly>
                                <small class="text-muted">Se calcula automáticamente basado en las fechas</small>
                                <div class="invalid-feedback" id="error_duracion"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Renovable</label>
                                <select class="form-select" name="renovable" id="renovable">
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                                <div class="invalid-feedback" id="error_renovable"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Objetivo del Convenio<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="objetivo" id="objetivo" rows="4" placeholder="Describe el objetivo principal del convenio..." required></textarea>
                        <div class="invalid-feedback" id="error_objetivo"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo del Convenio</label>
                        <input type="file" class="form-control" name="archivo_convenio" id="archivo_convenio" accept=".pdf" required>
                        <small class="text-muted">Formatos permitidos: PDF únicamente</small>
                        <div class="invalid-feedback" id="error_archivo_convenio"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarConvenio()">
                    <i class="fas fa-save me-1"></i>Guardar Convenio
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Institución -->
<div class="modal fade" id="modalNuevaInstitucion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-building me-2"></i>
                    Nueva Institución
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3" id="mensajeOrigen">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Creando institución desde Nuevo Convenio</strong><br>
                    Al guardar esta institución, regresarás automáticamente al formulario de convenio donde podrás seleccionarla.
                </div>
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                </div>
                <form id="formNuevaInstitucion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Institución<span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_institucion" id="tipo_institucion" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Pública</option>
                                    <option value="2">Privada</option>
                                </select>
                                <div class="invalid-feedback" id="error_tipo_institucion"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Institución<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre" id="nombre_institucion" required>
                                <div class="invalid-feedback" id="error_nombre_institucion"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">RUC<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ruc" id="ruc" maxlength="13" required>
                                <div class="invalid-feedback" id="error_ruc"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ciudad<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ciudad" id="ciudad" required>
                                <div class="invalid-feedback" id="error_ciudad"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="direccion" id="direccion" rows="2" required></textarea>
                        <div class="invalid-feedback" id="error_direccion"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teléfono<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="telefono" id="telefono" required>
                                <div class="invalid-feedback" id="error_telefono"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" id="email" required>
                                <div class="invalid-feedback" id="error_email"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Logo de la empresa</label>
                                <input type="file" class="form-control" name="logo_empresa" id="logo_empresa" accept="image/jpeg,image/png,image/gif,image/webp">
                                <small class="text-muted">Opcional. Formatos: JPG, PNG, GIF, WebP. Tamaño recomendado: 200x200 px.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vista previa</label>
                                <div class="border rounded p-2 text-center bg-light" style="min-height: 80px;">
                                    <img id="previewLogoEmpresa" src="" alt="Vista previa logo" class="img-fluid" style="max-height: 70px; display: none;">
                                    <span id="previewLogoPlaceholder" class="text-muted small">Sin imagen</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Datos del Representante Legal -->
                    <hr class="my-3">
                    <h6 class="mb-3"><i class="fas fa-id-card me-1"></i>Datos del Representante Legal</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Representante Legal<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="representante_legal" id="representante_legal" required>
                                <div class="invalid-feedback" id="error_representante_legal"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos de Contacto del Convenio -->
                    <h6 class="mb-3 mt-2"><i class="fas fa-phone-alt me-1"></i>Datos de Contacto del Convenio</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Nombre del Contacto<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="contacto" id="contacto" required>
                                <div class="invalid-feedback" id="error_contacto"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Teléfono de Contacto<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="telefono_contacto" id="telefono_contacto" required>
                                <div class="invalid-feedback" id="error_telefono_contacto"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Email de Contacto<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email_contacto" id="email_contacto" required>
                                <div class="invalid-feedback" id="error_email_contacto"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="volverANuevoConvenio()">
                    <i class="fas fa-arrow-left me-1"></i>Volver
                </button>
                <button type="button" class="btn btn-primary" onclick="guardarInstitucion()">
                    <i class="fas fa-save me-1"></i>Guardar Institución
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Convenio -->
<div class="modal fade" id="modalDetalleConvenio" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle del Convenio
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Información General</h6>
                            </div>
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="col-auto mb-2 mb-md-0">
                                        <div class="border rounded p-2 bg-light text-center" style="width: 120px; height: 100px;">
                                            <img id="detalleLogoEmpresa" src="" alt="Logo institución" class="img-fluid mw-100 mh-100" style="max-width: 110px; max-height: 90px; object-fit: contain; display: none;">
                                            <span id="detalleLogoPlaceholder" class="text-muted small d-block" style="line-height: 90px;">Sin logo</span>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <p><strong>Institución:</strong> <span id="detalleInstitucion">-</span></p>
                                        <p><strong>Tipo de Convenio:</strong> <span id="detalleTipo">-</span></p>
                                        <p><strong>RUC:</strong> <span id="detalleRUC">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Período:</strong> <span id="detallePeriodo">-</span></p>
                                        <p><strong>Duración:</strong> <span id="detalleDuracion">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Objetivo:</strong></p>
                                        <p class="text-muted" id="detalleObjetivo">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Información de Contacto</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Representante Legal:</strong> <span id="detalleRepresentante">-</span></p>
                                        <p><strong>Teléfono:</strong> <span id="detalleTelefono">-</span></p>
                                        <p><strong>Email:</strong> <span id="detalleEmail">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Persona de Contacto:</strong> <span id="detalleContacto">-</span></p>
                                        <p><strong>Teléfono Contacto:</strong> <span id="detalleTelefonoContacto">-</span></p>
                                        <p><strong>Email Contacto:</strong> <span id="detalleEmailContacto">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Estado del Convenio</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="progress-circle mb-3">
                                    <canvas id="estadoChart" width="150" height="150"></canvas>
                                </div>
                                <h4 id="estadoPercent">75%</h4>
                                <p class="text-muted" id="estadoDias">45 días restantes</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Documentos</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>Convenio Original
                                    </button>
                                    <button class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-file-word me-1"></i>Acta de Renovación
                                    </button>
                                    <button class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-file-excel me-1"></i>Reporte de Cumplimiento
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
                    <i class="fas fa-edit me-1"></i>Editar Convenio
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
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
                <form id="formFiltros">
                    <div class="mb-3">
                        <label class="form-label">Tipo de Convenio</label>
                        <select class="form-select" name="filtro_tipo">
                            <option value="">Todos los tipos</option>
                            <option value="1">Preprofesional</option>
                            <option value="2">Servicio Comunitario</option>
                            <option value="3">Mixta</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="vigente">Vigente</option>
                            <option value="por_vencer">Por Vencer</option>
                            <option value="vencido">Vencido</option>
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
                    <div class="mb-3">
                        <label class="form-label">Tipo de Institución</label>
                        <select class="form-select" name="filtro_tipo_institucion">
                            <option value="">Todos los tipos</option>
                            <option value="1">Pública</option>
                            <option value="2">Privada</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
                <button type="button" class="btn btn-primary" onclick="aplicarFiltros()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let conveniosData = <?= json_encode($convenios) ?>;
    let instituciones = <?= json_encode($instituciones) ?>;
    let tiposConvenios = <?= json_encode($tipos_convenios) ?>;
    const baseUrlLogos = <?= json_encode(base_url('uploads/logos_instituciones/')) ?>;

    // Funciones principales
    function showModal(modalId) {
        if (modalId === 'modalNuevoConvenio') {
            cargarInstituciones(); // Cargar instituciones cuando se abre el modal
        }
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verDetalle(id) {
        // conveniosData es el array que envía PHP (claves en mayúsculas)
        const lista = Array.isArray(conveniosData) ? conveniosData : [];
        const convenio = lista.find(c => Number(c.ID_DETALLE_CONVENIO) === Number(id));

        if (!convenio) {
            showNotification('No se encontró el convenio.', 'warning');
            return;
        }

        const imgLogo = document.getElementById('detalleLogoEmpresa');
        const placeholderLogo = document.getElementById('detalleLogoPlaceholder');
        if (convenio.LOGO && baseUrlLogos) {
            imgLogo.src = baseUrlLogos + convenio.LOGO;
            imgLogo.style.display = 'block';
            if (placeholderLogo) placeholderLogo.style.display = 'none';
        } else {
            imgLogo.src = '';
            imgLogo.style.display = 'none';
            if (placeholderLogo) placeholderLogo.style.display = 'block';
        }

        document.getElementById('detalleInstitucion').textContent = convenio.NOMBRE || '-';
        document.getElementById('detalleTipo').textContent = convenio.TIPO_CONVENIO || '-';
        document.getElementById('detalleRUC').textContent = convenio.RUC || '-';
        document.getElementById('detallePeriodo').textContent = `${convenio.FECHA_INICIO || '-'} - ${convenio.FECHA_FIN || '-'}`;
        document.getElementById('detalleDuracion').textContent = (convenio.DURACION != null ? convenio.DURACION + ' meses' : '-');
        document.getElementById('detalleObjetivo').textContent = convenio.OBJETIVO || '-';
        document.getElementById('detalleRepresentante').textContent = convenio.REPRESENTANTE_LEGAL || '-';
        document.getElementById('detalleTelefono').textContent = convenio.TELEFONO || '-';
        document.getElementById('detalleEmail').textContent = convenio.EMAIL || '-';
        document.getElementById('detalleContacto').textContent = convenio.CONTACTO || '-';
        document.getElementById('detalleTelefonoContacto').textContent = convenio.TELEFONO_CONTACTO || '-';
        document.getElementById('detalleEmailContacto').textContent = convenio.EMAIL_CONTACTO || '-';

        const hoy = new Date();
        const fechaFin = new Date(convenio.FECHA_FIN);
        const fechaInicio = new Date(convenio.FECHA_INICIO);
        const diasRestantes = Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));
        let estado = 'Vigente';
        if (convenio.FECHA_FIN < hoy.toISOString().slice(0, 10)) estado = 'Vencido';
        else if (diasRestantes <= 30) estado = 'Por Vencer';
        document.getElementById('detalleEstado').textContent = estado;

        const totalDias = fechaFin - fechaInicio;
        const transcurridos = hoy - fechaInicio;
        const porcentaje = totalDias > 0 ? Math.max(0, Math.min(100, (transcurridos / totalDias) * 100)) : 0;

        document.getElementById('estadoPercent').textContent = `${Math.round(porcentaje)}%`;
        document.getElementById('estadoDias').textContent = diasRestantes > 0 ? `${diasRestantes} días restantes` : (diasRestantes === 0 ? 'Vence hoy' : `${Math.abs(diasRestantes)} días vencido`);

        drawEstadoChart(porcentaje);
        showModal('modalDetalleConvenio');
    }

    function editarConvenio(id) {
        showNotification('Edición de convenio en desarrollo. Por ahora use la opción de descarga.', 'info');
    }

    function renovarConvenio(id) {
        showNotification('Función de renovación en desarrollo', 'info');
    }

    function descargarConvenio(id) {
        showNotification('Descargando convenio...', 'success');
    }

    function guardarConvenio() {
        const form = document.getElementById('formNuevoConvenio');
        const formData = new FormData(form);

        // Limpiar errores previos
        limpiarErrores();

        // Validar campos obligatorios
        const camposObligatorios = ['tipo_convenio', 'institucion', 'fecha_inicio', 'fecha_fin', 'duracion', 'objetivo', 'archivo_convenio'];
        let hayErrores = false;

        camposObligatorios.forEach(campo => {
            const valor = formData.get(campo);
            if (!valor || valor.trim() === '') {
                mostrarError(campo, 'Este campo es obligatorio');
                hayErrores = true;
            }
        });

        // Validar fechas
        const fechaInicio = new Date(formData.get('fecha_inicio'));
        const fechaFin = new Date(formData.get('fecha_fin'));
        
        if (fechaFin < fechaInicio) {
            mostrarError('fecha_fin', 'La fecha fin debe ser posterior a la fecha inicio');
            hayErrores = true;
        }

        // Validar duración (se calcula automáticamente, solo verificar que esté presente)
        const duracion = parseInt(formData.get('duracion'));
        if (!duracion || duracion < 1) {
            mostrarError('duracion', 'La duración debe ser calculada automáticamente. Verifica las fechas.');
            hayErrores = true;
        }

        // Validar archivo PDF
        const archivo = formData.get('archivo_convenio');
        if (archivo && archivo.size > 0) {
            const extension = archivo.name.toLowerCase().split('.').pop();
            if (extension !== 'pdf') {
                mostrarError('archivo_convenio', 'Solo se permiten archivos PDF');
                hayErrores = true;
            }
            
            // Validar tamaño del archivo (máximo 10MB)
            const maxSize = 10 * 1024 * 1024; // 10MB en bytes
            if (archivo.size > maxSize) {
                mostrarError('archivo_convenio', 'El archivo no puede superar los 10MB');
                hayErrores = true;
            }
        }

        if (hayErrores) {
            showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }

        // Enviar datos al servidor
        fetch('<?= base_url('admin/convenios/store') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoConvenio')).hide();
                form.reset();
                // Recargar la página para mostrar los nuevos datos
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(campo => {
                        mostrarError(campo, data.errors[campo]);
                    });
                }
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al guardar el convenio', 'error');
        });
    }

    function guardarInstitucion() {
        const form = document.getElementById('formNuevaInstitucion');
        const formData = new FormData(form);

        // Limpiar errores previos
        limpiarErroresInstitucion();

        // Validar campos obligatorios
        const camposObligatorios = ['tipo_institucion', 'nombre', 'ruc', 'ciudad', 'direccion', 'telefono', 'email', 'representante_legal', 'contacto', 'telefono_contacto', 'email_contacto'];
        let hayErrores = false;

        camposObligatorios.forEach(campo => {
            const valor = formData.get(campo);
            if (!valor || valor.trim() === '') {
                mostrarErrorInstitucion(campo, 'Este campo es obligatorio');
                hayErrores = true;
            }
        });

        // Validar email
        const email = formData.get('email');
        const emailContacto = formData.get('email_contacto');
        
        if (email && !validarEmail(email)) {
            mostrarErrorInstitucion('email', 'Email inválido');
            hayErrores = true;
        }
        
        if (emailContacto && !validarEmail(emailContacto)) {
            mostrarErrorInstitucion('email_contacto', 'Email de contacto inválido');
            hayErrores = true;
        }

        // Validar RUC
        const ruc = formData.get('ruc');
        if (ruc && (ruc.length < 10 || ruc.length > 13)) {
            mostrarErrorInstitucion('ruc', 'El RUC debe tener entre 10 y 13 caracteres');
            hayErrores = true;
        }

        if (hayErrores) {
            showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }

        // Enviar datos al servidor
        fetch('<?= base_url('admin/convenios/storeInstitucion') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                
                // Cerrar modal de nueva institución
                bootstrap.Modal.getInstance(document.getElementById('modalNuevaInstitucion')).hide();
                
                // Limpiar formulario y vista previa del logo
                form.reset();
                const previewLogo = document.getElementById('previewLogoEmpresa');
                const placeholderLogo = document.getElementById('previewLogoPlaceholder');
                if (previewLogo) { previewLogo.src = ''; previewLogo.style.display = 'none'; }
                if (placeholderLogo) placeholderLogo.style.display = 'inline';
                
                // Volver al modal de nuevo convenio y recargar instituciones
                setTimeout(() => {
                    showModal('modalNuevoConvenio');
                    // Seleccionar automáticamente la nueva institución
                    setTimeout(() => {
                        const selectInstitucion = document.getElementById('selectInstitucion');
                        if (selectInstitucion && data.institucion_id) {
                            selectInstitucion.value = data.institucion_id;
                            // Disparar evento change para validación
                            selectInstitucion.dispatchEvent(new Event('change'));
                        }
                    }, 100);
                }, 500);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(campo => {
                        mostrarErrorInstitucion(campo, data.errors[campo]);
                    });
                }
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al guardar la institución', 'error');
        });
    }

    function aplicarFiltros() {
        showNotification('Filtros aplicados', 'info');
        bootstrap.Modal.getInstance(document.getElementById('modalFiltros')).hide();
    }

    function limpiarFiltros() {
        document.getElementById('formFiltros').reset();
        showNotification('Filtros limpiados', 'info');
    }

    function generateReport() {
        const tipo = document.querySelector('input[name="filtro_tipo"]:checked')?.value || '';
        const url = `<?= base_url('admin/convenios/generarReporte') ?>?tipo=${tipo}&formato=pdf`;
        window.open(url, '_blank');
        showNotification('Generando reporte PDF...', 'info');
    }

    function exportData() {
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
        const tipo = document.querySelector('input[name="filtro_tipo"]:checked')?.value || '';
        const url = `<?= base_url('admin/convenios/generarReporte') ?>?tipo=${tipo}&formato=${formato}`;
        
        // Cerrar el modal
        const modal = document.getElementById('modalOpcionesExportacion');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
        }
        
        // Abrir la exportación
        window.open(url, '_blank');
        
        // Mostrar notificación según el formato
        let mensaje = '';
        switch(formato) {
            case 'pdf':
                mensaje = 'Generando reporte PDF...';
                break;
            case 'excel':
                mensaje = 'Exportando datos a Excel...';
                break;            
            default:
                mensaje = 'Exportando datos...';
        }
        showNotification(mensaje, 'info');
    }

    function cargarInstituciones() {
        const selectInstitucion = document.getElementById('selectInstitucion');
        const institucionContainer = document.getElementById('institucionContainer');
        const noInstitucionesContainer = document.getElementById('noInstitucionesContainer');

        if (instituciones.length === 0) {
            // No hay instituciones
            institucionContainer.classList.add('d-none');
            noInstitucionesContainer.classList.remove('d-none');
        } else {
            // Hay instituciones
            institucionContainer.classList.remove('d-none');
            noInstitucionesContainer.classList.add('d-none');

            // Limpiar y llenar select
            selectInstitucion.innerHTML = '<option value="">Seleccionar institución...</option>';

            instituciones.forEach(inst => {
                const option = document.createElement('option');
                option.value = inst.id;
                option.textContent = `${inst.nombre} (${inst.tipo})`;
                selectInstitucion.appendChild(option);
            });
        }
    }

    function irANuevaInstitucion() {
        // Cerrar modal de nuevo convenio
        bootstrap.Modal.getInstance(document.getElementById('modalNuevoConvenio')).hide();

        // Mostrar modal de nueva institución
        setTimeout(() => {
            showModal('modalNuevaInstitucion');
        }, 300);
    }

    function volverANuevoConvenio() {
        // Cerrar modal de nueva institución
        bootstrap.Modal.getInstance(document.getElementById('modalNuevaInstitucion')).hide();

        // Mostrar modal de nuevo convenio
        setTimeout(() => {
            showModal('modalNuevoConvenio');
        }, 300);
    }

    function agregarInstitucionDesdeConvenio() {
        // Cerrar modal de nuevo convenio
        bootstrap.Modal.getInstance(document.getElementById('modalNuevoConvenio')).hide();

        // Mostrar modal de nueva institución
        setTimeout(() => {
            showModal('modalNuevaInstitucion');
            // Actualizar badge para mostrar origen
            const badge = document.getElementById('badgeOrigen');
            if (badge) {
                badge.innerHTML = '<i class="fas fa-link me-1"></i>Desde Nuevo Convenio';
                badge.className = 'badge bg-info';
            }
        }, 300);
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

    function drawEstadoChart(percentage) {
        const canvas = document.getElementById('estadoChart');
        const ctx = canvas.getContext('2d');
        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = 60;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Background circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = '#e9ecef';
        ctx.lineWidth = 10;
        ctx.stroke();

        // Progress circle
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius, -Math.PI / 2, (-Math.PI / 2) + (2 * Math.PI * percentage / 100));
        ctx.strokeStyle = '#667eea';
        ctx.lineWidth = 10;
        ctx.lineCap = 'round';
        ctx.stroke();
    }

    // Funciones de validación
    function validarEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function mostrarError(campo, mensaje) {
        const elemento = document.getElementById(campo);
        const errorElement = document.getElementById(`error_${campo}`);
        
        if (elemento) {
            elemento.classList.add('is-invalid');
        }
        
        if (errorElement) {
            errorElement.textContent = mensaje;
            errorElement.style.display = 'block';
        }
    }

    function limpiarErrores() {
        const campos = ['tipo_convenio', 'institucion', 'fecha_inicio', 'fecha_fin', 'duracion', 'renovable', 'objetivo', 'archivo_convenio'];
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            const errorElement = document.getElementById(`error_${campo}`);
            
            if (elemento) {
                elemento.classList.remove('is-invalid');
            }
            
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        });
    }

    function mostrarErrorInstitucion(campo, mensaje) {
        const elemento = document.getElementById(campo);
        const errorElement = document.getElementById(`error_${campo}`);
        
        if (elemento) {
            elemento.classList.add('is-invalid');
        }
        
        if (errorElement) {
            errorElement.textContent = mensaje;
            errorElement.style.display = 'block';
        }
    }

    function limpiarErroresInstitucion() {
        const campos = ['tipo_institucion', 'nombre_institucion', 'ruc', 'ciudad', 'direccion', 'telefono', 'email', 'representante_legal', 'contacto', 'telefono_contacto', 'email_contacto'];
        campos.forEach(campo => {
            const elemento = document.getElementById(campo);
            const errorElement = document.getElementById(`error_${campo}`);
            
            if (elemento) {
                elemento.classList.remove('is-invalid');
            }
            
            if (errorElement) {
                errorElement.style.display = 'none';
            }
        });
    }

    // Función para calcular duración automáticamente
    function calcularDuracion() {
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        const campoDuracion = document.getElementById('duracion');

        if (fechaInicio && fechaFin) {
            const inicio = new Date(fechaInicio);
            const fin = new Date(fechaFin);

            if (fin >= inicio) {
                // Calcular diferencia en meses
                const diffTime = Math.abs(fin - inicio);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const diffMonths = Math.round(diffDays / 30.44); // Promedio de días por mes

                campoDuracion.value = diffMonths;
                
                // Limpiar error si existe
                campoDuracion.classList.remove('is-invalid');
                const errorElement = document.getElementById('error_duracion');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            } else {
                campoDuracion.value = '';
                mostrarError('fecha_fin', 'La fecha fin debe ser posterior a la fecha inicio');
            }
        } else {
            campoDuracion.value = '';
        }
    }

    // Vista previa del logo al seleccionar archivo (Nueva Institución)
    function initPreviewLogoEmpresa() {
        const input = document.getElementById('logo_empresa');
        const preview = document.getElementById('previewLogoEmpresa');
        const placeholder = document.getElementById('previewLogoPlaceholder');
        if (!input || !preview) return;
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                if (placeholder) placeholder.style.display = 'inline';
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initPreviewLogoEmpresa();

        // Set default date for new convention
        const today = new Date().toISOString().split('T')[0];
        const fechaInicioInput = document.querySelector('input[name="fecha_inicio"]');
        if (fechaInicioInput) fechaInicioInput.value = today;

        // Set default end date (12 months later)
        const endDate = new Date();
        endDate.setMonth(endDate.getMonth() + 12);
        document.querySelector('input[name="fecha_fin"]').value = endDate.toISOString().split('T')[0];

        // Calcular duración inicial
        calcularDuracion();

        // Agregar event listeners para calcular duración automáticamente
        document.getElementById('fecha_inicio').addEventListener('change', calcularDuracion);
        document.getElementById('fecha_fin').addEventListener('change', calcularDuracion);

        // Initialize estado chart
        drawEstadoChart(75);
    });

    // Tab change handler
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target');
            if (target === '#mixta') {
                setTimeout(() => drawEstadoChart(75), 100);
            }
        });
    });
</script>
<?= $this->endSection() ?>
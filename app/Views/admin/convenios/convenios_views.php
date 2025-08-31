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
                        <h2 class="card-title mb-2" id="totalConvenios" style="font-size:2.5rem;">18</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Convenios</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="conveniosVigentes" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Vigentes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="conveniosPorVencer" style="font-size:2.5rem;">4</h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Por Vencer</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 80%, #a71e2a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="conveniosVencidos" style="font-size:2.5rem;">2</h2>
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
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <div class="fw-bold">Nuevo Convenio</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <a href="#" onclick="showModal('modalNuevaInstitucion')" style="text-decoration: none; color: inherit;">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-building fa-2x mb-2"></i>
                            <div class="fw-bold">Nueva Institución</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generateReport()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportData()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2"></i>
                            <div class="fw-bold">Exportar Datos</div>
                        </a>
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
                                                    <tr>
                                                        <td>001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-hospital fa-2x me-2 text-primary"></i>
                                                                <div>
                                                                    <div class="fw-semibold">Hospital San Vicente de Paúl</div>
                                                                    <small class="text-muted">Sector Público</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>1768123456001</td>
                                                        <td>
                                                            <div>Jun 2025 - Jun 2026</div>
                                                            <small class="text-muted">12 meses</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">12 meses</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success text-white">Vigente</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Sí</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(1)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarConvenio(1)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success" onclick="descargarConvenio(1)" title="Descargar">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
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
                                                    <tr>
                                                        <td>SC001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-hands-helping fa-2x me-2 text-success"></i>
                                                                <div>
                                                                    <div class="fw-semibold">Fundación Niños del Ecuador</div>
                                                                    <small class="text-muted">ONG</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>1798123456001</td>
                                                        <td>
                                                            <div>Ago 2025 - Dic 2025</div>
                                                            <small class="text-muted">5 meses</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">5 meses</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning text-dark">Por Vencer</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary">No</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(2)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarConvenio(2)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-info" onclick="renovarConvenio(2)" title="Renovar">
                                                                    <i class="fas fa-sync-alt"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
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
                                                    <tr>
                                                        <td>M001</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-industry fa-2x me-2 text-info"></i>
                                                                <div>
                                                                    <div class="fw-semibold">Empresa Tecnológica XYZ</div>
                                                                    <small class="text-muted">Sector Privado</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>1798123456002</td>
                                                        <td>
                                                            <div>Jul 2025 - Oct 2025</div>
                                                            <small class="text-muted">4 meses</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">4 meses</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success text-white">Vigente</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Sí</span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <button class="btn btn-outline-primary" onclick="verDetalle(3)" title="Ver Detalle">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                                <button class="btn btn-outline-warning" onclick="editarConvenio(3)" title="Editar">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-outline-success" onclick="descargarConvenio(3)" title="Descargar">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
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
                <form id="formNuevoConvenio">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Convenio</label>
                                <select class="form-select" name="tipo_convenio" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Preprofesional</option>
                                    <option value="2">Servicio Comunitario</option>
                                    <option value="3">Mixta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Institución</label>
                                <select class="form-select" name="institucion" required>
                                    <option value="">Seleccionar institución...</option>
                                    <option value="1">Hospital San Vicente de Paúl</option>
                                    <option value="2">Fundación Niños del Ecuador</option>
                                    <option value="3">Empresa Tecnológica XYZ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Duración (meses)</label>
                                <input type="number" class="form-control" name="duracion" min="1" max="60" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Renovable</label>
                                <select class="form-select" name="renovable" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Objetivo del Convenio</label>
                        <textarea class="form-control" name="objetivo" rows="4" placeholder="Describe el objetivo principal del convenio..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Archivo del Convenio</label>
                        <input type="file" class="form-control" name="archivo_convenio" accept=".pdf,.doc,.docx">
                        <small class="text-muted">Formatos permitidos: PDF, DOC, DOCX</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales..."></textarea>
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
                <form id="formNuevaInstitucion">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Institución</label>
                                <select class="form-select" name="tipo_institucion" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="1">Pública</option>
                                    <option value="2">Privada</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre de la Institución</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">RUC</label>
                                <input type="text" class="form-control" name="ruc" maxlength="13" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ciudad</label>
                                <input type="text" class="form-control" name="ciudad" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección</label>
                        <textarea class="form-control" name="direccion" rows="2" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" name="telefono" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Representante Legal</label>
                                <input type="text" class="form-control" name="representante_legal" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Persona de Contacto</label>
                                <input type="text" class="form-control" name="contacto" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teléfono de Contacto</label>
                                <input type="text" class="form-control" name="telefono_contacto" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email de Contacto</label>
                                <input type="email" class="form-control" name="email_contacto" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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
                                <div class="row">
                                    <div class="col-md-6">
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Datos simulados de convenios
    let conveniosData = {
        preprofesionales: [
            {
                id: 1,
                institucion: 'Hospital San Vicente de Paúl',
                tipo: 'Público',
                ruc: '1768123456001',
                fechaInicio: '2025-06-01',
                fechaFin: '2026-06-01',
                duracion: '12 meses',
                estado: 'Vigente',
                renovable: true,
                objetivo: 'Desarrollo de prácticas preprofesionales en el área de sistemas de información hospitalaria',
                representante: 'Dr. Juan Pérez',
                telefono: '062-123-456',
                email: 'contacto@hospital.ec',
                contacto: 'Ing. María González',
                telefonoContacto: '062-123-457',
                emailContacto: 'practicas@hospital.ec'
            }
        ],
        servicio: [
            {
                id: 2,
                institucion: 'Fundación Niños del Ecuador',
                tipo: 'ONG',
                ruc: '1798123456001',
                fechaInicio: '2025-08-01',
                fechaFin: '2025-12-31',
                duracion: '5 meses',
                estado: 'Por Vencer',
                renovable: false,
                objetivo: 'Desarrollo de plataforma educativa para niños en situación vulnerable',
                representante: 'Lic. Ana López',
                telefono: '062-456-789',
                email: 'info@fundacion.ec',
                contacto: 'Lic. Carlos Ruiz',
                telefonoContacto: '062-456-790',
                emailContacto: 'practicas@fundacion.ec'
            }
        ],
        mixta: [
            {
                id: 3,
                institucion: 'Empresa Tecnológica XYZ',
                tipo: 'Privada',
                ruc: '1798123456002',
                fechaInicio: '2025-07-01',
                fechaFin: '2025-10-01',
                duracion: '4 meses',
                estado: 'Vigente',
                renovable: true,
                objetivo: 'Desarrollo de aplicaciones móviles y web para servicios empresariales',
                representante: 'Ing. Roberto Silva',
                telefono: '062-789-123',
                email: 'contacto@xyz.ec',
                contacto: 'Ing. Patricia Vega',
                telefonoContacto: '062-789-124',
                emailContacto: 'practicas@xyz.ec'
            }
        ]
    };

    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function verDetalle(id) {
        // Buscar el convenio en todos los arrays
        let convenio = [...conveniosData.preprofesionales, ...conveniosData.servicio, ...conveniosData.mixta].find(c => c.id === id);
        
        if (convenio) {
            document.getElementById('detalleInstitucion').textContent = convenio.institucion;
            document.getElementById('detalleTipo').textContent = convenio.tipo;
            document.getElementById('detalleRUC').textContent = convenio.ruc;
            document.getElementById('detallePeriodo').textContent = `${convenio.fechaInicio} - ${convenio.fechaFin}`;
            document.getElementById('detalleDuracion').textContent = convenio.duracion;
            document.getElementById('detalleEstado').textContent = convenio.estado;
            document.getElementById('detalleObjetivo').textContent = convenio.objetivo;
            document.getElementById('detalleRepresentante').textContent = convenio.representante;
            document.getElementById('detalleTelefono').textContent = convenio.telefono;
            document.getElementById('detalleEmail').textContent = convenio.email;
            document.getElementById('detalleContacto').textContent = convenio.contacto;
            document.getElementById('detalleTelefonoContacto').textContent = convenio.telefonoContacto;
            document.getElementById('detalleEmailContacto').textContent = convenio.emailContacto;
            
            // Calcular días restantes
            const hoy = new Date();
            const fechaFin = new Date(convenio.fechaFin);
            const diasRestantes = Math.ceil((fechaFin - hoy) / (1000 * 60 * 60 * 24));
            const porcentaje = Math.max(0, Math.min(100, ((fechaFin - hoy) / (fechaFin - new Date(convenio.fechaInicio))) * 100));
            
            document.getElementById('estadoPercent').textContent = `${Math.round(porcentaje)}%`;
            document.getElementById('estadoDias').textContent = `${diasRestantes} días restantes`;
            
            drawEstadoChart(porcentaje);
            showModal('modalDetalleConvenio');
        }
    }

    function editarConvenio(id) {
        showNotification('Función de edición en desarrollo', 'info');
    }

    function renovarConvenio(id) {
        showNotification('Función de renovación en desarrollo', 'info');
    }

    function descargarConvenio(id) {
        showNotification('Descargando convenio...', 'success');
    }

    function guardarConvenio() {
        showNotification('Convenio guardado exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalNuevoConvenio')).hide();
    }

    function guardarInstitucion() {
        showNotification('Institución guardada exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalNuevaInstitucion')).hide();
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
        showNotification('Generando reporte...', 'info');
    }

    function exportData() {
        showNotification('Exportando datos...', 'info');
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Set default date for new convention
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="fecha_inicio"]').value = today;
        
        // Set default end date (12 months later)
        const endDate = new Date();
        endDate.setMonth(endDate.getMonth() + 12);
        document.querySelector('input[name="fecha_fin"]').value = endDate.toISOString().split('T')[0];

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

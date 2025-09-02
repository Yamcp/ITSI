<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-hands-helping me-2"></i>
                    Documentos de Servicio Comunitario
                </h3>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalDocumentosServicio" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Documentos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosAprobadosServicio" style="font-size:2.5rem;">3</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosPendientesServicio" style="font-size:2.5rem;">9</h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 80%, #a71e2a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosRechazadosServicio" style="font-size:2.5rem;">0</h2>
                        <p class="card-text fw-bold" style="color: #ffe0e0;">Rechazados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row mb-4 justify-content-center">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalNuevoDocumentoPractica')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"
                            ></i>
                            <div class="fw-bold">Nuevo Documento</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="showModal('modalFiltrosPracticas')" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-filter fa-2x mb-2" style="color: #007bff; text-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);"></i>
                            <div class="fw-bold">Filtros</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReportePracticas()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-hands-helping me-2"></i>
                            Documentos de Servicio Comunitario
                        </span>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" onclick="cambiarVista('grid')">
                                <i class="fas fa-th-large me-1"></i>Grid
                            </button>
                            <button class="btn btn-light btn-sm" onclick="cambiarVista('list')">
                                <i class="fas fa-list me-1"></i>Lista
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="vistaGrid" class="row g-3">
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-1">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-primary me-3">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.1. Plan de Trabajo de Servicio Comunitario</h6>
                                            <small class="text-muted" id="estado-1">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Plan</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-1">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-1">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(1)" title="Ver" id="btn-ver-1" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(1)" title="Descargar" id="btn-descargar-1" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(1)" title="Cambiar Estado" id="btn-estado-1" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(1)" title="Eliminar" id="btn-eliminar-1" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(1)" title="Subir Documento" id="btn-subir-1">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="vistaLista" class="d-none">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Documento</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                            <th>Fecha Subida</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaDocumentosLista">
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

<script>
function cambiarVista(tipo) {
    if (tipo === 'grid') {
        document.getElementById('vistaGrid').classList.remove('d-none');
        document.getElementById('vistaLista').classList.add('d-none');
    } else {
        document.getElementById('vistaGrid').classList.add('d-none');
        document.getElementById('vistaLista').classList.remove('d-none');
        generarVistaLista();
    }
}

function generarVistaLista() {
    const tbody = document.getElementById('tablaDocumentosLista');
    tbody.innerHTML = '';
    
    const documentos = [
        { id: 1, nombre: '1.1. Plan de Trabajo de Servicio Comunitario', tipo: 'Plan', icono: 'fas fa-clipboard-list', color: 'bg-primary' }
    ];

    documentos.forEach(doc => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="file-icon ${doc.color} me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                        <i class="${doc.icono}"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">${doc.nombre}</div>
                        <small class="text-muted">Tipo: ${doc.tipo}</small>
                    </div>
                </div>
            </td>
            <td><span class="category-badge ${doc.color} text-white">${doc.tipo}</span></td>
            <td><span class="category-badge bg-success text-white">Pendiente</span></td>
            <td>No subido</td>
            <td>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-info" onclick="subirDocumento(${doc.id})" title="Subir Documento">
                        <i class="fas fa-upload"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function showModal(modalId) {
    alert('Modal: ' + modalId);
}

function verDocumento(id) {
    alert('Ver documento: ' + id);
}

function descargarDocumento(id) {
    alert('Descargar documento: ' + id);
}

function eliminarDocumento(id) {
    alert('Eliminar documento: ' + id);
}

function subirDocumento(id) {
    alert('Subir documento: ' + id);
}

function generarReporteServicio() {
    alert('Generar reporte');
}

function revisionMasiva() {
    alert('Revisión masiva');
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Vista de servicio comunitario cargada');
});
</script>
<?= $this->endSection() ?>

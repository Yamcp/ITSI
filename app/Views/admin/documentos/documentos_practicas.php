<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<!-- CSS personalizado para documentos de prácticas -->
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/documentos.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-file-alt me-2"></i>
                    Documentos de Prácticas Preprofesionales
                </h3>
            </div>
        </div>

        <!-- Estadísticas Rápidas en Cuadros -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="totalDocumentosPracticas" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Total Documentos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #28a745 80%, #155724 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosAprobadosPracticas" style="font-size:2.5rem;">3</h2>
                        <p class="card-text fw-bold" style="color: #e0e0e0;">Aprobados</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #ffc107 80%, #b38600 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosPendientesPracticas" style="font-size:2.5rem;">9</h2>
                        <p class="card-text fw-bold" style="color: #fffbe6;">Pendientes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card text-center shadow-sm" style="background: linear-gradient(135deg, #dc3545 80%, #a71e2a 100%); color: #fff; border: none;">
                    <div class="card-body">
                        <h2 class="card-title mb-2" id="documentosRechazadosPracticas" style="font-size:2.5rem;">0</h2>
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

        <!-- Tabla de Documentos de Prácticas -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-briefcase me-2"></i>
                            Documentos de Prácticas
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
                        <!-- Vista Grid -->
                        <div id="vistaGrid" class="row g-3">
                            <!-- 1.1. Oficio de Asignación de Tutor Docente -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-1">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-primary me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.1. Oficio de Asignación de Tutor Docente</h6>
                                            <small class="text-muted" id="estado-1">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Oficio</span>
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

                            <!-- 1.2. Oficio Personal a Entidad Receptora -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-2">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-success me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.2. Oficio Personal a Entidad Receptora</h6>
                                            <small class="text-muted" id="estado-2">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-success text-white">Oficio</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-2">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-2">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(2)" title="Ver" id="btn-ver-2" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(2)" title="Descargar" id="btn-descargar-2" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(2)" title="Cambiar Estado" id="btn-estado-2" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(2)" title="Eliminar" id="btn-eliminar-2" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(2)" title="Subir Documento" id="btn-subir-2">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.3. Carta de Aceptación de Entidad Receptora -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-warning me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.3. Carta de Aceptación de Entidad Receptora</h6>
                                            <small class="text-muted" id="estado-3">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-warning text-white">Carta</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-3">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-3">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(3)" title="Ver" id="btn-ver-3" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(3)" title="Descargar" id="btn-descargar-3" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(3)" title="Cambiar Estado" id="btn-estado-3" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(3)" title="Eliminar" id="btn-eliminar-3" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(3)" title="Subir Documento" id="btn-subir-3">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.4. Solicitud Institucional Valorada -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-info me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.4. Solicitud Institucional Valorada</h6>
                                            <small class="text-muted" id="estado-4">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-info text-white">Solicitud</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-4">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-4">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(4)" title="Ver" id="btn-ver-4" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(4)" title="Descargar" id="btn-descargar-4" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(4)" title="Cambiar Estado" id="btn-estado-4" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(4)" title="Eliminar" id="btn-eliminar-4" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(4)" title="Subir Documento" id="btn-subir-4">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.5. Certificado de Culminación (60 horas) -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-5">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-primary me-3">
                                            <i class="fas fa-certificate"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.5. Certificado de Culminación (60 horas)</h6>
                                            <small class="text-muted" id="estado-5">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Certificado</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-5">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-5">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(5)" title="Ver" id="btn-ver-5" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(5)" title="Descargar" id="btn-descargar-5" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(5)" title="Cambiar Estado" id="btn-estado-5" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(5)" title="Eliminar" id="btn-eliminar-5" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(5)" title="Subir Documento" id="btn-subir-5">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.6. Rúbrica de Evaluación Entidad Receptora -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-success me-3">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.6. Rúbrica de Evaluación Entidad Receptora</h6>
                                            <small class="text-muted" id="estado-6">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-success text-white">Rúbrica</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-6">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-6">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(6)" title="Ver" id="btn-ver-6" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(6)" title="Descargar" id="btn-descargar-6" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(6)" title="Cambiar Estado" id="btn-estado-6" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(6)" title="Eliminar" id="btn-eliminar-6" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(6)" title="Subir Documento" id="btn-subir-6">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.7. Hojas de Asistencia de Estudiantes -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-7">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-warning me-3">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.7. Hojas de Asistencia de Estudiantes</h6>
                                            <small class="text-muted" id="estado-7">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-warning text-white">Asistencia</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-7">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-7">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(7)" title="Ver" id="btn-ver-7" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(7)" title="Descargar" id="btn-descargar-7" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(7)" title="Cambiar Estado" id="btn-estado-7" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(7)" title="Eliminar" id="btn-eliminar-7" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(7)" title="Subir Documento" id="btn-subir-7">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.8. Ficha de Registro de Actividades Realizadas -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-8">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-info me-3">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.8. Ficha de Registro de Actividades Realizadas</h6>
                                            <small class="text-muted" id="estado-8">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-info text-white">Ficha</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-8">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-8">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(8)" title="Ver" id="btn-ver-8" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(8)" title="Descargar" id="btn-descargar-8" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(8)" title="Cambiar Estado" id="btn-estado-8" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(8)" title="Eliminar" id="btn-eliminar-8" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(8)" title="Subir Documento" id="btn-subir-8">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.9. Ficha de Control y Seguimiento Docente -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-9">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-primary me-3">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.9. Ficha de Control y Seguimiento Docente</h6>
                                            <small class="text-muted" id="estado-9">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-primary text-white">Ficha</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-9">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-9">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(9)" title="Ver" id="btn-ver-9" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(9)" title="Descargar" id="btn-descargar-9" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(9)" title="Cambiar Estado" id="btn-estado-9" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(9)" title="Eliminar" id="btn-eliminar-9" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(9)" title="Subir Documento" id="btn-subir-9">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.10. Rúbrica de Evaluación de Control y Seguimiento Docente -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-10">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-success me-3">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.10. Rúbrica de Evaluación de Control y Seguimiento Docente</h6>
                                            <small class="text-muted" id="estado-10">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-success text-white">Rúbrica</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-10">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-10">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(10)" title="Ver" id="btn-ver-10" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(10)" title="Descargar" id="btn-descargar-10" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(10)" title="Cambiar Estado" id="btn-estado-10" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(10)" title="Eliminar" id="btn-eliminar-10" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(10)" title="Subir Documento" id="btn-subir-10">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.11. Rúbrica de Evaluación de Resultados -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-11">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-warning me-3">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.11. Rúbrica de Evaluación de Resultados</h6>
                                            <small class="text-muted" id="estado-11">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-warning text-white">Rúbrica</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-11">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-11">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(11)" title="Ver" id="btn-ver-11" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(11)" title="Descargar" id="btn-descargar-11" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(11)" title="Cambiar Estado" id="btn-estado-11" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(11)" title="Eliminar" id="btn-eliminar-11" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(11)" title="Subir Documento" id="btn-subir-11">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 1.12. Respaldo en Fotos, Videos y Evidencias -->
                            <div class="col-md-4 col-lg-3">
                                <div class="file-item p-3 h-100" id="doc-12">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="file-icon bg-info me-3">
                                            <i class="fas fa-images"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">1.12. Respaldo en Fotos, Videos y Evidencias</h6>
                                            <small class="text-muted" id="estado-12">Estado: Pendiente</small>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <span class="category-badge bg-info text-white">Evidencias</span>
                                        <span class="category-badge bg-success text-white ms-2" id="status-12">Pendiente</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted" id="fecha-12">No subido</small>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="verDocumento(12)" title="Ver" id="btn-ver-12" style="display: none;">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="descargarDocumento(12)" title="Descargar" id="btn-descargar-12" style="display: none;">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(12)" title="Cambiar Estado" id="btn-estado-12" style="display: none;">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(12)" title="Eliminar" id="btn-eliminar-12" style="display: none;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-info" onclick="subirDocumento(12)" title="Subir Documento" id="btn-subir-12">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista Lista (oculta por defecto) -->
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
                                        <!-- Los documentos se cargarán dinámicamente aquí -->
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

<!-- Modal Subir Documento de Práctica -->
<div class="modal fade" id="modalSubirDocumentoPractica" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-cloud-upload-alt me-2"></i>
                    Subir Documento de Práctica
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formSubirDocumentoPractica">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de Documento</label>
                                <select class="form-select" name="tipo_documento" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="oficio_asignacion_tutor">1.1. Oficio de Asignación de Tutor Docente</option>
                                    <option value="oficio_personal_entidad">1.2. Oficio Personal a Entidad Receptora</option>
                                    <option value="carta_aceptacion">1.3. Carta de Aceptación de Entidad Receptora</option>
                                    <option value="solicitud_institucional">1.4. Solicitud Institucional Valorada</option>
                                    <option value="certificado_culminacion">1.5. Certificado de Culminación (60 horas)</option>
                                    <option value="rubrica_evaluacion_entidad">1.6. Rúbrica de Evaluación Entidad Receptora</option>
                                    <option value="hojas_asistencia">1.7. Hojas de Asistencia de Estudiantes</option>
                                    <option value="ficha_registro_actividades">1.8. Ficha de Registro de Actividades Realizadas</option>
                                    <option value="ficha_control_seguimiento">1.9. Ficha de Control y Seguimiento Docente</option>
                                    <option value="rubrica_evaluacion_docente">1.10. Rúbrica de Evaluación de Control y Seguimiento Docente</option>
                                    <option value="rubrica_evaluacion_resultados">1.11. Rúbrica de Evaluación de Resultados</option>
                                    <option value="respaldo_fotos">1.12. Respaldo en Fotos, Videos y Evidencias</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estudiante</label>
                                <select class="form-select" name="estudiante" required>
                                    <option value="">Seleccionar estudiante...</option>
                                    <option value="1">Yamilex Campues - Sistemas</option>
                                    <option value="2">Ana Yandun - Desarrollo</option>
                                    <option value="3">Pedro Aguirre - Desarrollo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Entidad Receptora</label>
                                <input type="text" class="form-control" name="entidad_receptora" placeholder="Ej: Instituto Tecnológico Superior Ibarra" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Docente Tutor</label>
                                <select class="form-select" name="docente_tutor" required>
                                    <option value="">Seleccionar docente tutor...</option>
                                    <option value="1">Dr. Mario Montenegro - Rector</option>
                                    <option value="2">Ing. Juan Pérez - Coordinador</option>
                                    <option value="3">Mg. María González - Tutora</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Archivo</label>
                        <div class="upload-card p-4 text-center" id="uploadAreaPractica">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Arrastra y suelta archivos aquí</h5>
                            <p class="text-muted mb-3">o</p>
                            <input type="file" class="form-control" name="archivo" id="archivoInputPractica" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" required>
                            <small class="text-muted">Máximo 50 MB. Formatos: PDF, DOC, XLS, JPG, ZIP</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Estado de Revisión</label>
                                <select class="form-select" name="estado_revision" required>
                                    <option value="">Seleccionar estado...</option>
                                    <option value="pendiente">Pendiente de Revisión</option>
                                    <option value="en_revision">En Revisión</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="rechazado">Rechazado</option>
                                    <option value="requiere_correccion">Requiere Corrección</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prioridad</label>
                                <select class="form-select" name="prioridad" required>
                                    <option value="">Seleccionar prioridad...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones del Administrador</label>
                        <textarea class="form-control" name="observaciones" rows="3" placeholder="Observaciones adicionales sobre el documento, estado de revisión, correcciones necesarias..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="subirDocumentoPractica()">
                    <i class="fas fa-upload me-1"></i>Subir Documento
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
                            <option value="oficio_asignacion_tutor">1.1. Oficio de Asignación de Tutor Docente</option>
                            <option value="oficio_personal_entidad">1.2. Oficio Personal a Entidad Receptora</option>
                            <option value="carta_aceptacion">1.3. Carta de Aceptación de Entidad Receptora</option>
                            <option value="solicitud_institucional">1.4. Solicitud Institucional Valorada</option>
                            <option value="certificado_culminacion">1.5. Certificado de Culminación (60 horas)</option>
                            <option value="rubrica_evaluacion_entidad">1.6. Rúbrica de Evaluación Entidad Receptora</option>
                            <option value="hojas_asistencia">1.7. Hojas de Asistencia de Estudiantes</option>
                            <option value="ficha_registro_actividades">1.8. Ficha de Registro de Actividades Realizadas</option>
                            <option value="ficha_control_seguimiento">1.9. Ficha de Control y Seguimiento Docente</option>
                            <option value="rubrica_evaluacion_docente">1.10. Rúbrica de Evaluación de Control y Seguimiento Docente</option>
                            <option value="rubrica_evaluacion_resultados">1.11. Rúbrica de Evaluación de Resultados</option>
                            <option value="respaldo_fotos">1.12. Respaldo en Fotos, Videos y Evidencias</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estado de Revisión</label>
                        <select class="form-select" name="filtro_estado">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente de Revisión</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="requiere_correccion">Requiere Corrección</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Docente Tutor</label>
                        <select class="form-select" name="filtro_docente">
                            <option value="">Todos los docentes</option>
                            <option value="1">Dr. Mario Montenegro - Rector</option>
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
                        <select class="form-select" name="nuevo_estado" required>
                            <option value="">Seleccionar nuevo estado...</option>
                            <option value="pendiente">Pendiente de Revisión</option>
                            <option value="en_revision">En Revisión</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="requiere_correccion">Requiere Corrección</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentarios del Administrador</label>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Funciones principales
    function showModal(modalId) {
        const modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    }

    function cambiarVista(tipo) {
        if (tipo === 'grid') {
            document.getElementById('vistaGrid').classList.remove('d-none');
            document.getElementById('vistaLista').classList.add('d-none');
        } else {
            document.getElementById('vistaGrid').classList.add('d-none');
            document.getElementById('vistaLista').classList.remove('d-none');
            // Generar la vista de lista cuando se active
            generarVistaLista();
        }
    }

    function generarVistaLista() {
        const tbody = document.getElementById('tablaDocumentosLista');
        tbody.innerHTML = '';

        // Array con la información de los 12 documentos
        const documentos = [
            { id: 1, nombre: '1.1. Oficio de Asignación de Tutor Docente', tipo: 'Oficio', icono: 'fas fa-file-alt', color: 'bg-primary' },
            { id: 2, nombre: '1.2. Oficio Personal a Entidad Receptora', tipo: 'Oficio', icono: 'fas fa-file-alt', color: 'bg-success' },
            { id: 3, nombre: '1.3. Carta de Aceptación de Entidad Receptora', tipo: 'Carta', icono: 'fas fa-file-alt', color: 'bg-warning' },
            { id: 4, nombre: '1.4. Solicitud Institucional Valorada', tipo: 'Solicitud', icono: 'fas fa-file-alt', color: 'bg-info' },
            { id: 5, nombre: '1.5. Certificado de Culminación (60 horas)', tipo: 'Certificado', icono: 'fas fa-certificate', color: 'bg-primary' },
            { id: 6, nombre: '1.6. Rúbrica de Evaluación Entidad Receptora', tipo: 'Rúbrica', icono: 'fas fa-clipboard-check', color: 'bg-success' },
            { id: 7, nombre: '1.7. Hojas de Asistencia de Estudiantes', tipo: 'Asistencia', icono: 'fas fa-calendar-check', color: 'bg-warning' },
            { id: 8, nombre: '1.8. Ficha de Registro de Actividades Realizadas', tipo: 'Ficha', icono: 'fas fa-clipboard-list', color: 'bg-info' },
            { id: 9, nombre: '1.9. Ficha de Control y Seguimiento Docente', tipo: 'Ficha', icono: 'fas fa-user-tie', color: 'bg-primary' },
            { id: 10, nombre: '1.10. Rúbrica de Evaluación de Control y Seguimiento Docente', tipo: 'Rúbrica', icono: 'fas fa-clipboard-check', color: 'bg-success' },
            { id: 11, nombre: '1.11. Rúbrica de Evaluación de Resultados', tipo: 'Rúbrica', icono: 'fas fa-chart-line', color: 'bg-warning' },
            { id: 12, nombre: '1.12. Respaldo en Fotos, Videos y Evidencias', tipo: 'Evidencias', icono: 'fas fa-images', color: 'bg-info' }
        ];

        documentos.forEach(doc => {
            const estadoElement = document.getElementById(`estado-${doc.id}`);
            const statusElement = document.getElementById(`status-${doc.id}`);
            const fechaElement = document.getElementById(`fecha-${doc.id}`);
            
            // Obtener el estado actual del documento
            let estado = 'Pendiente';
            let estadoClass = 'bg-success text-white';
            let fecha = 'No subido';
            let botonesVisibles = false;
            
            if (estadoElement && statusElement && fechaElement) {
                estado = statusElement.textContent;
                fecha = fechaElement.textContent;
                
                // Determinar si los botones de acción están visibles
                const btnVer = document.getElementById(`btn-ver-${doc.id}`);
                botonesVisibles = btnVer && btnVer.style.display !== 'none';
                
                // Ajustar clase del estado para la vista de lista
                if (estado === 'Aprobado') {
                    estadoClass = 'bg-success text-white';
                } else if (estado === 'En Revisión') {
                    estadoClass = 'bg-warning text-dark';
                } else if (estado === 'Rechazado') {
                    estadoClass = 'bg-danger text-white';
                } else if (estado === 'Requiere Corrección') {
                    estadoClass = 'bg-info text-white';
                } else {
                    estadoClass = 'bg-secondary text-white';
                }
            }

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
                <td><span class="category-badge ${estadoClass}">${estado}</span></td>
                <td>${fecha}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        ${botonesVisibles ? `
                            <button class="btn btn-outline-primary" onclick="verDocumento(${doc.id})" title="Ver">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-outline-success" onclick="descargarDocumento(${doc.id})" title="Descargar">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="btn btn-outline-warning" onclick="cambiarEstadoDocumento(${doc.id})" title="Cambiar Estado">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-outline-danger" onclick="eliminarDocumento(${doc.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : `
                            <button class="btn btn-outline-info" onclick="subirDocumento(${doc.id})" title="Subir Documento">
                                <i class="fas fa-upload"></i>
                            </button>
                        `}
                    </div>
                </td>
            `;
            
            tbody.appendChild(row);
        });
    }

    function verDocumento(id) {
        showNotification('Visualizando documento...', 'info');
    }

    function descargarDocumento(id) {
        showNotification('Descargando documento...', 'success');
    }

    function eliminarDocumento(id) {
        if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
            showNotification('Documento eliminado exitosamente', 'success');
        }
    }

    function subirDocumentoPractica() {
        showNotification('Documento subido exitosamente', 'success');
        bootstrap.Modal.getInstance(document.getElementById('modalSubirDocumentoPractica')).hide();
    }

    function aplicarFiltrosPracticas() {
        showNotification('Filtros aplicados', 'info');
        bootstrap.Modal.getInstance(document.getElementById('modalFiltrosPracticas')).hide();
    }

    function limpiarFiltrosPracticas() {
        document.getElementById('formFiltrosPracticas').reset();
        showNotification('Filtros limpiados', 'info');
    }

    function exportarDocumentosPracticas() {
        showNotification('Exportando documentos...', 'info');
    }

    function generarReportePracticas() {
        showNotification('Generando reporte...', 'info');
    }

    function cambiarEstadoDocumento(id) {
        // Simular obtención de datos del documento
        document.getElementById('documento_id_estado').value = id;
        document.getElementById('nombre_documento_estado').value = 'Informe_Final_Practica.pdf';
        
        // Mostrar modal
        showModal('modalCambiarEstado');
    }

    function guardarCambioEstado() {
        const nuevoEstado = document.querySelector('select[name="nuevo_estado"]').value;
        const comentarios = document.querySelector('textarea[name="comentarios_estado"]').value;
        
        if (!nuevoEstado) {
            showNotification('Debe seleccionar un nuevo estado', 'error');
            return;
        }
        
        // Aquí se enviaría la petición al servidor
        showNotification(`Estado cambiado a: ${nuevoEstado}`, 'success');
        
        // Cerrar modal
        bootstrap.Modal.getInstance(document.getElementById('modalCambiarEstado')).hide();
        
        // Limpiar formulario
        document.getElementById('formCambiarEstado').reset();
    }

    function revisionMasiva() {
        showNotification('Función de revisión masiva en desarrollo. Permite cambiar el estado de múltiples documentos a la vez.', 'info');
    }

    function subirDocumento(id) {
        // Simular subida de documento
        const docElement = document.getElementById(`doc-${id}`);
        const estadoElement = document.getElementById(`estado-${id}`);
        const statusElement = document.getElementById(`status-${id}`);
        const fechaElement = document.getElementById(`fecha-${id}`);
        
        // Cambiar estado a "En Revisión"
        estadoElement.textContent = 'Estado: En Revisión';
        statusElement.textContent = 'En Revisión';
        statusElement.className = 'category-badge bg-warning text-dark ms-2';
        
        // Mostrar fecha de subida
        const fecha = new Date().toLocaleDateString('es-ES');
        fechaElement.textContent = `Subido: ${fecha}`;
        
        // Mostrar botones de acción
        document.getElementById(`btn-ver-${id}`).style.display = 'inline-block';
        document.getElementById(`btn-descargar-${id}`).style.display = 'inline-block';
        document.getElementById(`btn-estado-${id}`).style.display = 'inline-block';
        document.getElementById(`btn-eliminar-${id}`).style.display = 'inline-block';
        
        // Ocultar botón de subir
        document.getElementById(`btn-subir-${id}`).style.display = 'none';
        
        // Si la vista de lista está activa, actualizarla
        if (!document.getElementById('vistaLista').classList.contains('d-none')) {
            generarVistaLista();
        }
        
        showNotification(`Documento ${id} subido exitosamente`, 'success');
    }

    // Función para simular documentos ya subidos (ejemplo)
    function simularDocumentosExistentes() {
        // Simular que algunos documentos ya están subidos
        const documentosExistentes = [1, 3, 6]; // IDs de documentos que ya están subidos
        
        documentosExistentes.forEach(id => {
            const estadoElement = document.getElementById(`estado-${id}`);
            const statusElement = document.getElementById(`status-${id}`);
            const fechaElement = document.getElementById(`fecha-${id}`);
            
            // Cambiar estado a "Aprobado"
            estadoElement.textContent = 'Estado: Aprobado';
            statusElement.textContent = 'Aprobado';
            statusElement.className = 'category-badge bg-success text-white ms-2';
            
            // Mostrar fecha de subida
            const fecha = new Date(Date.now() - Math.random() * 30 * 24 * 60 * 60 * 1000).toLocaleDateString('es-ES');
            fechaElement.textContent = `Subido: ${fecha}`;
            
            // Mostrar botones de acción
            document.getElementById(`btn-ver-${id}`).style.display = 'inline-block';
            document.getElementById(`btn-descargar-${id}`).style.display = 'inline-block';
            document.getElementById(`btn-estado-${id}`).style.display = 'inline-block';
            document.getElementById(`btn-eliminar-${id}`).style.display = 'inline-block';
            
            // Ocultar botón de subir
            document.getElementById(`btn-subir-${id}`).style.display = 'none';
        });
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

    // Drag and Drop functionality
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadAreaPractica');
        const archivoInput = document.getElementById('archivoInputPractica');

        // Simular algunos documentos ya subidos
        simularDocumentosExistentes();
        
        // Si la vista de lista está activa por defecto, generarla
        if (!document.getElementById('vistaLista').classList.contains('d-none')) {
            generarVistaLista();
        }

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
                const event = new Event('change', { bubbles: true });
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
<?= $this->endSection() ?>

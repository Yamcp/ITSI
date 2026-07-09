<?= $this->extend('coord/layouts/mainCoord') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('sistema/assets/css/instructores.css') ?>" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <h3 class="text-center my-3">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    Instructores externos
                </h3>
            </div>
        </div>

        <!-- Estadísticas y acciones rápidas (una fila en pantallas grandes) -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4 align-items-stretch">
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="background: linear-gradient(135deg, #007bff 80%, #0056b3 100%); color: #fff; border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center py-3">
                        <h2 class="card-title mb-2" id="totalInstructores" style="font-size:2.5rem;">12</h2>
                        <p class="card-text fw-bold mb-0" style="color: #e0e0e0;">Instructores externos</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="abrirModalNuevoInstructor(); return false;" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-user-plus fa-2x mb-2" style="color: #28a745; text-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);"></i>
                            <div class="fw-bold">Nuevo Instructor</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="generarReporte()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-chart-bar fa-2x mb-2" style="color: #ffc107; text-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);"></i>
                            <div class="fw-bold">Generar Reporte</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center shadow-sm h-100" style="border: none;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <a href="#" onclick="exportarDatos()" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download fa-2x mb-2" style="color: #dc3545; text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);"></i>
                            <div class="fw-bold">Exportar Datos</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apartados de instructores -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body pb-0">
                        <div class="mt-3" id="instructoresApartados">
                            <!-- Instructores externos -->
                            <div class="mb-4" id="apartado-instructores">
                                <h5 class="mb-3 text-info fw-semibold">
                                    <i class="fas fa-user-tie me-2"></i>Listado
                                </h5>
                                <div class="card shadow-sm border-0">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Instructor</th>
                                                        <th>Tipo</th>
                                                        <th>Especialidad</th>
                                                        <th>Actividades</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaExternos">
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

<!-- Modal Filtros (listados por pestaña) -->
<div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosInstructoresLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFiltrosInstructoresLabel">
                    <i class="fas fa-filter me-2"></i>Filtros
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Los filtros se aplican al listado de instructores externos.</p>
                <div class="mb-3">
                    <label class="form-label" for="filtroBusquedaInstructor">Buscar</label>
                    <input type="search" class="form-control" id="filtroBusquedaInstructor" placeholder="Nombre, correo o texto en la fila" autocomplete="off">
                </div>
                <div class="mb-0">
                    <label class="form-label" for="filtroEstadoInstructor">Estado</label>
                    <select class="form-select" id="filtroEstadoInstructor">
                        <option value="">Todos</option>
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="btnLimpiarFiltrosInstructores">Limpiar</button>
                <button type="button" class="btn btn-primary" id="btnAplicarFiltrosInstructores">Aplicar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Instructor -->
<div class="modal fade" id="modalNuevoInstructor" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2" id="iconoModalInstructor"></i><span id="textoTituloModalInstructor">Nuevo Instructor Externo</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                </div>
                <form id="formNuevoInstructor">
                    <input type="hidden" id="id_instructor_edicion" value="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Título Profesional<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="titulo_profesional" required>
                                <div class="invalid-feedback">
                                    Por favor ingresa el título profesional.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Apellido<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="apellido" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cédula<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="cedula" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Celular<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="celular" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Género<span class="text-danger">*</span></label>
                                <select class="form-select" name="genero" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dirección<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="direccion" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Especialidad<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="especialidad" rows="3" placeholder="Describe las áreas de especialización del instructor..." required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nacionalidad<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nacionalidad" value="Ecuatoriana" required>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarInstructor()">
                    <i class="fas fa-save me-1"></i><span id="textoBtnGuardarInstructor">Guardar Instructor</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Instructor -->
<div class="modal fade" id="modalDetalleInstructor" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detalle del Instructor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="detalleIdInstructor" value="">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Información Personal</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Nombre:</strong> <span id="detalleNombre">-</span></p>
                                        <p><strong>Título:</strong> <span id="detalleTitulo">-</span></p>
                                        <p><strong>Tipo:</strong> <span id="detalleTipo">-</span></p>
                                        <p><strong>Email:</strong> <span id="detalleEmail">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Cédula:</strong> <span id="detalleCedula">-</span></p>
                                        <p><strong>Celular:</strong> <span id="detalleCelular">-</span></p>
                                        <p><strong>Género:</strong> <span id="detalleGenero">-</span></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p><strong>Dirección:</strong></p>
                                        <p class="text-muted" id="detalleDireccion">-</p>
                                        <p><strong>Especialidad:</strong></p>
                                        <p class="text-muted" id="detalleEspecialidad">-</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Actividades Recientes</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Actividad</th>
                                                <th>Tipo</th>
                                                <th>Modalidad</th>
                                                <th>Período</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaActividadesInstructor">
                                            <tr>
                                                <td>Desarrollo Web Full Stack</td>
                                                <td><span class="badge bg-primary">Curso</span></td>
                                                <td><span class="badge bg-info">Presencial</span></td>
                                                <td>Sep 2025 - Nov 2025</td>
                                                <td><span class="badge bg-success">Activo</span></td>
                                            </tr>
                                            <tr>
                                                <td>JavaScript Avanzado</td>
                                                <td><span class="badge bg-success">Taller</span></td>
                                                <td><span class="badge bg-warning text-dark">Virtual</span></td>
                                                <td>Jul 2025 - Ago 2025</td>
                                                <td><span class="badge bg-secondary">Completado</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Estadísticas</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <h4 id="totalActividadesInstructor">11</h4>
                                    <p class="text-muted">Total Actividades</p>
                                </div>
                                <div>
                                    <h4 id="participantesTotal">156</h4>
                                    <p class="text-muted">Participantes Totales</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Acciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="asignarActividad(parseInt(document.getElementById('detalleIdInstructor').value || '0', 10))">
                                        <i class="fas fa-plus me-1"></i>Asignar Nueva Actividad
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="editarDesdeDetalleInstructor()">
                    <i class="fas fa-edit me-1"></i>Editar Instructor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Opciones de Reporte -->
<div class="modal fade" id="modalOpcionesReporte" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar me-2" style="color: #ffc107;"></i>Opciones de Reporte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-file-pdf fa-3x mb-3" style="color: #dc3545;"></i>
                                <h5>Reporte PDF</h5>
                                <p class="text-muted">Genera un reporte completo de instructores en formato PDF</p>
                                <button class="btn btn-danger" onclick="generarReportePDF()">
                                    <i class="fas fa-file-pdf me-2"></i>Generar PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Opciones de Exportación -->
<div class="modal fade" id="modalOpcionesExportacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-download me-2" style="color: #dc3545;"></i>Opciones de Exportación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-excel fa-3x mb-3" style="color: #28a745;"></i>
                                <h6>Excel</h6>
                                <p class="text-muted small">Exportar a formato Excel (.xlsx)</p>
                                <button class="btn btn-success btn-sm" onclick="exportarExcel()">
                                    <i class="fas fa-file-excel me-1"></i>Excel
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-file-csv fa-3x mb-3" style="color: #007bff;"></i>
                                <h6>CSV</h6>
                                <p class="text-muted small">Exportar a formato CSV</p>
                                <button class="btn btn-primary btn-sm" onclick="exportarCSV()">
                                    <i class="fas fa-file-csv me-1"></i>CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales
    let instructoresData = [];
    let currentInstructorId = null;

    // Cargar datos desde la base de datos
    async function cargarInstructores() {
        try {
            const response = await fetch('<?= base_url('coord/instructores/getInstructores') ?>');
            const result = await response.json();

            if (result.success) {
                instructoresData = result.data;
                actualizarTablaInstructores();
                cargarEstadisticas();
            } else {
                showNotification('Error al cargar instructores: ' + result.message, 'error');
            }
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
        }
    }

    // Cargar estadísticas
    async function cargarEstadisticas() {
        try {
            const response = await fetch('<?= base_url('coord/instructores/getEstadisticas') ?>');
            const result = await response.json();

            if (result.success) {
                const stats = result.data;
                const totalEl = document.getElementById('totalInstructores');
                if (totalEl) {
                    totalEl.textContent = stats.total_instructores;
                }
            }
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
        }
    }

    // Actualizar tabla (solo instructores externos; el API ya no devuelve internos)
    function actualizarTablaInstructores() {
        const tbodyExternos = document.getElementById('tablaExternos');
        if (!tbodyExternos) {
            return;
        }
        tbodyExternos.innerHTML = '';

        let contadorExternos = 1;
        instructoresData.forEach((instructor) => {
            tbodyExternos.appendChild(crearFilaInstructor(instructor, contadorExternos++));
        });
    }

    // Crear fila de instructor
    function crearFilaInstructor(instructor, numero) {
        const tr = document.createElement('tr');
        const idInstructor = parseInt(instructor.ID_INSTRUCTOR || 0, 10);

        const tipoBadge = '<span class="badge bg-info">Externo</span>';

        const actividadesActivas = instructor.actividades_activas || 0;
        const actividadesCompletadas = instructor.actividades_completadas || 0;
        const accionesHtml = idInstructor > 0
            ? `
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" onclick="verDetalle(${idInstructor})" title="Ver Detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-outline-warning" onclick="editarInstructor(${idInstructor})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            `
            : '<small class="text-muted">Solo lectura</small>';

        tr.innerHTML = `
            <td>${numero.toString().padStart(3, '0')}</td>
            <td>
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(instructor.NOMBRE + '+' + instructor.APELLIDO)}&background=198754&color=fff&size=32" class="rounded-circle me-2" alt="${instructor.NOMBRE.charAt(0)}${instructor.APELLIDO.charAt(0)}">
                    <div>
                        <div class="fw-semibold">${instructor.TITULO_PROFESIONAL} ${instructor.NOMBRE} ${instructor.APELLIDO}</div>
                        <small class="text-muted">${instructor.EMAIL}</small>
                    </div>
                </div>
            </td>
            <td>${tipoBadge}</td>
            <td>
                <div>${instructor.ESPECIALIDAD}</div>
                <small class="text-muted">${instructor.TITULO_PROFESIONAL}</small>
            </td>
            <td>
                <span class="badge bg-info">${actividadesActivas} Activas</span>
                <small class="text-muted d-block">${actividadesCompletadas} Completadas</small>
            </td>
            <td><span class="badge bg-success">Activo</span></td>
            <td>
                ${accionesHtml}
            </td>
        `;

        return tr;
    }

    function showModal(modalId) {
        const el = document.getElementById(modalId);
        if (!el) {
            console.warn('Modal no encontrado:', modalId);
            return;
        }
        const modal = new bootstrap.Modal(el);
        modal.show();
    }

    function aplicarFiltrosInstructoresTablas() {
        const q = (document.getElementById('filtroBusquedaInstructor')?.value || '').trim().toLowerCase();
        const est = (document.getElementById('filtroEstadoInstructor')?.value || '').trim();
        const hasFilters = Boolean(q || est);

        const tbody = document.getElementById('tablaExternos');
        if (!tbody) {
            return;
        }
        tbody.querySelectorAll('tr').forEach((tr) => {
            const emptyCell = tr.querySelector('td[colspan]');
            if (emptyCell) {
                tr.style.display = hasFilters ? 'none' : '';
                return;
            }
            const cells = tr.querySelectorAll('td');
            if (cells.length < 7) {
                return;
            }
            const instructorText = (cells[1]?.innerText || '').toLowerCase();
            const estadoText = (cells[5]?.innerText || '').toLowerCase();

            let show = true;
            if (q && !instructorText.includes(q) && !(tr.innerText || '').toLowerCase().includes(q)) {
                show = false;
            }
            if (est === 'activo' && !estadoText.includes('activo')) {
                show = false;
            }
            if (est === 'inactivo' && !estadoText.includes('inactiv')) {
                show = false;
            }
            tr.style.display = show ? '' : 'none';
        });

        const modalEl = document.getElementById('modalFiltros');
        if (modalEl) {
            const instance = bootstrap.Modal.getInstance(modalEl);
            if (instance) {
                instance.hide();
            }
        }
    }

    function limpiarFiltrosInstructoresTablas() {
        const b = document.getElementById('filtroBusquedaInstructor');
        const e = document.getElementById('filtroEstadoInstructor');
        if (b) {
            b.value = '';
        }
        if (e) {
            e.value = '';
        }
        const tbody = document.getElementById('tablaExternos');
        if (tbody) {
            tbody.querySelectorAll('tr').forEach((tr) => {
                tr.style.display = '';
            });
        }
    }

    document.getElementById('btnAplicarFiltrosInstructores')?.addEventListener('click', aplicarFiltrosInstructoresTablas);
    document.getElementById('btnLimpiarFiltrosInstructores')?.addEventListener('click', () => {
        limpiarFiltrosInstructoresTablas();
    });

    async function verDetalle(id) {
        try {
            const response = await fetch(`<?= base_url('coord/instructores/getInstructor') ?>/${id}`);
            const result = await response.json();

            if (result.success) {
                const instructor = result.data;

                const hidDet = document.getElementById('detalleIdInstructor');
                if (hidDet) {
                    hidDet.value = String(id);
                }

                document.getElementById('detalleNombre').textContent = `${instructor.TITULO_PROFESIONAL} ${instructor.NOMBRE} ${instructor.APELLIDO}`;
                document.getElementById('detalleTitulo').textContent = instructor.TITULO_PROFESIONAL;
                document.getElementById('detalleTipo').textContent = instructor.TIPO_INSTRUCTOR;
                document.getElementById('detalleEmail').textContent = instructor.EMAIL;
                document.getElementById('detalleCedula').textContent = instructor.CEDULA;
                document.getElementById('detalleCelular').textContent = instructor.CELULAR;
                document.getElementById('detalleGenero').textContent = instructor.GENERO;
                document.getElementById('detalleDireccion').textContent = instructor.DIRECCION;
                document.getElementById('detalleEspecialidad').textContent = instructor.ESPECIALIDAD;
                document.getElementById('totalActividadesInstructor').textContent = instructor.actividades ? instructor.actividades.length : 0;

                // Actualizar tabla de actividades
                const tbodyActividades = document.getElementById('tablaActividadesInstructor');
                tbodyActividades.innerHTML = '';

                if (instructor.actividades && instructor.actividades.length > 0) {
                    instructor.actividades.forEach(actividad => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${actividad.NOMBRE_ACTIVIDAD}</td>
                            <td><span class="badge bg-primary">Curso</span></td>
                            <td><span class="badge bg-info">Presencial</span></td>
                            <td>${new Date(actividad.FECHA_INICIO).toLocaleDateString()} - ${new Date(actividad.FECHA_FIN).toLocaleDateString()}</td>
                            <td><span class="badge ${new Date(actividad.FECHA_FIN) >= new Date() ? 'bg-success' : 'bg-secondary'}">${new Date(actividad.FECHA_FIN) >= new Date() ? 'Activo' : 'Completado'}</span></td>
                        `;
                        tbodyActividades.appendChild(tr);
                    });
                }

                showModal('modalDetalleInstructor');
            } else {
                showNotification('Error al obtener detalles del instructor: ' + result.message, 'error');
            }
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
        }
    }

    function resetearModalFormularioInstructor() {
        const form = document.getElementById('formNuevoInstructor');
        if (!form) {
            return;
        }
        const hid = document.getElementById('id_instructor_edicion');
        if (hid) {
            hid.value = '';
        }
        form.reset();
        form.classList.remove('was-validated');
        form.querySelectorAll('.is-invalid, .is-valid').forEach((el) => {
            el.classList.remove('is-invalid', 'is-valid');
        });
        const nac = form.querySelector('[name="nacionalidad"]');
        if (nac && !nac.value) {
            nac.value = 'Ecuatoriana';
        }
        const titulo = document.getElementById('textoTituloModalInstructor');
        const icono = document.getElementById('iconoModalInstructor');
        const btnTxt = document.getElementById('textoBtnGuardarInstructor');
        if (titulo) {
            titulo.textContent = 'Nuevo Instructor Externo';
        }
        if (icono) {
            icono.className = 'fas fa-user-plus me-2';
        }
        if (btnTxt) {
            btnTxt.textContent = 'Guardar Instructor';
        }
    }

    function abrirModalNuevoInstructor() {
        resetearModalFormularioInstructor();
        showModal('modalNuevoInstructor');
    }

    function editarDesdeDetalleInstructor() {
        const detId = (document.getElementById('detalleIdInstructor')?.value || '').trim();
        if (!detId) {
            return;
        }
        const modalDet = document.getElementById('modalDetalleInstructor');
        const instDet = modalDet ? bootstrap.Modal.getInstance(modalDet) : null;
        if (instDet) {
            instDet.hide();
        }
        setTimeout(() => editarInstructor(detId), 350);
    }

    async function editarInstructor(id) {
        try {
            const response = await fetch(`<?= base_url('coord/instructores/getInstructor') ?>/${id}`);
            const result = await response.json();

            if (!result.success || !result.data) {
                showNotification('No se pudo cargar el instructor: ' + (result.message || ''), 'error');
                return;
            }

            const ins = result.data;
            const form = document.getElementById('formNuevoInstructor');
            const hid = document.getElementById('id_instructor_edicion');
            if (hid) {
                hid.value = String(id);
            }

            const setVal = (name, value) => {
                const el = form.querySelector(`[name="${name}"]`);
                if (el) {
                    el.value = value != null ? String(value) : '';
                }
            };

            setVal('titulo_profesional', ins.TITULO_PROFESIONAL);
            setVal('nombre', ins.NOMBRE);
            setVal('apellido', ins.APELLIDO);
            setVal('cedula', ins.CEDULA);
            setVal('email', ins.EMAIL);
            setVal('celular', ins.CELULAR);
            setVal('genero', ins.GENERO);
            setVal('direccion', ins.DIRECCION);
            setVal('especialidad', ins.ESPECIALIDAD);
            setVal('nacionalidad', ins.NACIONALIDAD || 'Ecuatoriana');

            form.classList.remove('was-validated');
            form.querySelectorAll('.is-invalid, .is-valid').forEach((el) => {
                el.classList.remove('is-invalid', 'is-valid');
            });

            const titulo = document.getElementById('textoTituloModalInstructor');
            const icono = document.getElementById('iconoModalInstructor');
            const btnTxt = document.getElementById('textoBtnGuardarInstructor');
            if (titulo) {
                titulo.textContent = 'Editar Instructor';
            }
            if (icono) {
                icono.className = 'fas fa-user-edit me-2';
            }
            if (btnTxt) {
                btnTxt.textContent = 'Actualizar Instructor';
            }

            showModal('modalNuevoInstructor');
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
        }
    }

    function asignarActividad(id) {
        showModal('modalAsignarActividad');
    }

    async function guardarInstructor() {
        const form = document.getElementById('formNuevoInstructor');
        const formData = new FormData(form);

        // Limpiar validaciones anteriores
        form.classList.remove('was-validated');
        const inputs = form.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });

        // Validar campos obligatorios
        const camposObligatorios = [
            'titulo_profesional',
            'nombre',
            'apellido',
            'cedula',
            'email',
            'celular',
            'genero',
            'direccion',
            'especialidad',
            'nacionalidad'
        ];

        let hayErrores = false;

        camposObligatorios.forEach(campo => {
            const input = form.querySelector(`[name="${campo}"]`);
            const valor = formData.get(campo);

            if (!valor || valor.trim() === '') {
                input.classList.add('is-invalid');
                hayErrores = true;
            } else {
                input.classList.add('is-valid');
            }
        });

        // Validar formato de email
        const email = formData.get('email');
        const emailInput = form.querySelector('[name="email"]');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            emailInput.classList.add('is-invalid');
            hayErrores = true;
        }

        // Validar formato de cédula (10 dígitos para Ecuador)
        const cedula = formData.get('cedula');
        const cedulaInput = form.querySelector('[name="cedula"]');
        if (cedula && (cedula.length !== 10 || !/^\d+$/.test(cedula))) {
            cedulaInput.classList.add('is-invalid');
            hayErrores = true;
        }

        // Validar formato de celular (10 dígitos para Ecuador)
        const celular = formData.get('celular');
        const celularInput = form.querySelector('[name="celular"]');
        if (celular && (celular.length !== 10 || !/^\d+$/.test(celular))) {
            celularInput.classList.add('is-invalid');
            hayErrores = true;
        }

        if (hayErrores) {
            form.classList.add('was-validated');
            showNotification('Por favor corrige los errores en el formulario', 'error');
            return;
        }

        // Enviar datos al servidor
        const editId = (document.getElementById('id_instructor_edicion')?.value || '').trim();
        const urlGuardar = editId ?
            `<?= base_url('coord/instructores/actualizar') ?>/${encodeURIComponent(editId)}` :
            '<?= base_url('coord/instructores/crear') ?>';

        try {
            const response = await fetch(urlGuardar, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                showNotification(result.message || (editId ? 'Instructor actualizado' : 'Instructor guardado exitosamente'), 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalNuevoInstructor')).hide();
                cargarInstructores(); // Recargar la lista
                cargarEstadisticas(); // Actualizar estadísticas
            } else {
                if (result.errors) {
                    // Mostrar errores específicos de validación
                    let errorMessage = 'Errores de validación:\n';
                    Object.keys(result.errors).forEach(campo => {
                        errorMessage += `• ${result.errors[campo]}\n`;
                    });
                    showNotification(errorMessage, 'error');
                } else {
                    showNotification('Error al guardar instructor: ' + result.message, 'error');
                }
            }
        } catch (error) {
            showNotification('Error de conexión: ' + error.message, 'error');
        }
    }

    function generarReporte() {
        // Mostrar modal de opciones de reporte
        showModal('modalOpcionesReporte');
    }

    function exportarDatos() {
        // Mostrar modal dinámico de opciones de exportación
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
        // Cerrar el modal primero
        const modal = document.getElementById('modalOpcionesExportacion');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }

        // Ejecutar la exportación según el formato seleccionado
        switch (formato) {
            case 'pdf':
                generarReportePDF();
                break;
            case 'excel':
                exportarExcel();
                break;
            default:
                showNotification('Formato de exportación no válido', 'error');
        }
    }

    function generarReportePDF() {
        // Abrir reporte PDF en nueva ventana
        window.open('<?= base_url('coord/instructores/generarReporte') ?>', '_blank');
        showNotification('Generando reporte PDF...', 'info');
    }

    function exportarExcel() {
        // Descargar archivo Excel
        window.location.href = '<?= base_url('coord/instructores/exportarExcel') ?>';
        showNotification('Exportando datos a Excel...', 'info');
    }

    function exportarCSV() {
        // Descargar archivo CSV
        window.location.href = '<?= base_url('coord/instructores/exportarCSV') ?>';
        showNotification('Exportando datos a CSV...', 'info');
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Cargar datos iniciales
        cargarInstructores();
        cargarEstadisticas();

        // Set default values
        const today = new Date().toISOString().split('T')[0];

        // Agregar validación en tiempo real
        agregarValidacionTiempoReal();

        // Si se llegó desde "agregar instructor" (ej. desde actividades), abrir modal de nuevo instructor
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('crear') === '1') {
            setTimeout(function() {
                abrirModalNuevoInstructor();
            }, 400);
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        document.getElementById('modalNuevoInstructor')?.addEventListener('hidden.bs.modal', resetearModalFormularioInstructor);
    });

    // Agregar validación en tiempo real a los campos
    function agregarValidacionTiempoReal() {
        const form = document.getElementById('formNuevoInstructor');
        const inputs = form.querySelectorAll('.form-control, .form-select');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validarCampo(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validarCampo(this);
                }
            });
        });
    }

    // Validar campo individual
    function validarCampo(input) {
        const valor = input.value.trim();
        const nombre = input.name;

        // Limpiar clases anteriores
        input.classList.remove('is-valid', 'is-invalid');

        // Validar campo vacío
        if (!valor) {
            input.classList.add('is-invalid');
            return false;
        }

        // Validaciones específicas
        let esValido = true;

        switch (nombre) {
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                esValido = emailRegex.test(valor);
                break;
            case 'cedula':
                esValido = valor.length === 10 && /^\d+$/.test(valor);
                break;
            case 'celular':
                esValido = valor.length === 10 && /^\d+$/.test(valor);
                break;
            case 'nombre':
            case 'apellido':
                esValido = valor.length >= 2;
                break;
            case 'titulo_profesional':
            case 'especialidad':
                esValido = valor.length >= 3;
                break;
        }

        if (esValido) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }

        return esValido;
    }

</script>
<?= $this->endSection() ?>
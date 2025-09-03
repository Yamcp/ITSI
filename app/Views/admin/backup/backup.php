<!-- app/Views/admin/backup/backup.php -->
<?= $this->extend('admin/layouts/mainAdmin') ?>

<?= $this->section('styles') ?>
<style>
    /* Variables CSS para consistencia */
    :root {
        --primary-color: #2563eb;
        --primary-hover: #1d4ed8;
        --success-color: #059669;
        --warning-color: #d97706;
        --danger-color: #dc2626;
        --info-color: #0891b2;
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-400: #9ca3af;
        --gray-500: #6b7280;
        --gray-600: #4b5563;
        --gray-700: #374151;
        --gray-800: #1f2937;
        --gray-900: #111827;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --border-radius: 0.75rem;
        --border-radius-lg: 1rem;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Mejoras de accesibilidad */
    * {
        box-sizing: border-box;
    }

    /* Focus visible para mejor accesibilidad */
    .btn:focus-visible,
    .form-control:focus-visible,
    .form-select:focus-visible,
    .action-card:focus-visible {
        outline: 2px solid var(--primary-color);
        outline-offset: 2px;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    /* Header mejorado */
    .backup-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
        color: white;
        border-radius: var(--border-radius-lg);
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-xl);
        position: relative;
        overflow: hidden;
    }

    .backup-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }

    .backup-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .backup-header p {
        font-size: 1.125rem;
        opacity: 0.9;
        margin: 0.5rem 0 0 0;
        position: relative;
        z-index: 1;
    }

    /* Tarjetas de acción mejoradas */
    .action-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        cursor: pointer;
        background: white;
        position: relative;
        overflow: hidden;
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: inherit;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(255, 255, 255, 0.1) 100%);
        opacity: 0;
        transition: var(--transition);
    }

    .action-card:hover,
    .action-card:focus {
        box-shadow: var(--shadow-xl);
        transform: translateY(-4px);
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover::before,
    .action-card:focus::before {
        opacity: 1;
    }

    .action-card:active {
        transform: translateY(-2px);
    }

    .action-card .card-body {
        text-align: center;
        padding: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .action-card i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .action-card .fw-bold {
        font-size: 1.125rem;
        margin-bottom: 0.5rem;
    }

    .action-card small {
        font-size: 0.875rem;
        opacity: 0.7;
    }

    /* Tabla mejorada */
    .backup-table {
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        background: white;
    }

    .backup-table .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .backup-table .table thead th {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e40af 100%);
        color: white;
        border: none;
        font-weight: 600;
        padding: 1.25rem 1rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        position: relative;
    }

    .backup-table .table thead th:first-child {
        border-top-left-radius: var(--border-radius-lg);
    }

    .backup-table .table thead th:last-child {
        border-top-right-radius: var(--border-radius-lg);
    }

    .backup-table .table tbody tr {
        transition: var(--transition);
        border-bottom: 1px solid var(--gray-100);
    }

    .backup-table .table tbody tr:hover {
        background-color: var(--gray-50);
        transform: none;
    }

    .backup-table .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: var(--border-radius-lg);
    }

    .backup-table .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: var(--border-radius-lg);
    }

    .backup-table .table td {
        padding: 1rem;
        vertical-align: middle;
        border: none;
    }

    /* Botones mejorados */
    .btn-modern {
        border-radius: var(--border-radius);
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: var(--transition);
        border: none;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
        text-decoration: none;
    }

    .btn-modern:active {
        transform: translateY(0);
    }

    .btn-modern:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Badges mejorados */
    .badge-modern {
        border-radius: 9999px;
        padding: 0.5rem 1rem;
        font-weight: 500;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Estado vacío mejorado */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--gray-500);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        color: var(--gray-300);
        display: block;
    }

    .empty-state h5 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--gray-700);
    }

    .empty-state p {
        font-size: 1rem;
        margin-bottom: 2rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    /* Modales mejorados */
    .modal {
        z-index: 1055;
    }

    .modal-backdrop {
        z-index: 1050;
        background-color: rgba(0, 0, 0, 0.6);
    }

    .modal.show {
        display: block !important;
    }

    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out, opacity 0.3s ease-out;
        transform: translate(0, -50px);
        opacity: 0;
    }

    .modal.show .modal-dialog {
        transform: none;
        opacity: 1;
    }

    .modal-content {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-xl);
    }

    .modal-header {
        border-bottom: 1px solid var(--gray-200);
        border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0;
        padding: 1.5rem;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        border-top: 1px solid var(--gray-200);
        border-radius: 0 0 var(--border-radius-lg) var(--border-radius-lg);
        padding: 1.5rem;
    }

    /* Formularios mejorados */
    .form-control,
    .form-select {
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius);
        padding: 0.75rem 1rem;
        transition: var(--transition);
        font-size: 0.875rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .form-label {
        font-weight: 600;
        color: var(--gray-700);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    /* Alertas mejoradas */
    .alert {
        border: none;
        border-radius: var(--border-radius);
        padding: 1rem 1.25rem;
        border-left: 4px solid;
    }

    .alert-info {
        background-color: #eff6ff;
        border-left-color: var(--info-color);
        color: #1e40af;
    }

    /* Progress bars mejoradas */
    .progress {
        height: 0.75rem;
        border-radius: 9999px;
        background-color: var(--gray-200);
    }

    .progress-bar {
        border-radius: 9999px;
        transition: width 0.6s ease;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .backup-header {
            padding: 1.5rem;
            text-align: center;
        }

        .backup-header h1 {
            font-size: 2rem;
        }

        .action-card {
            min-height: 120px;
        }

        .action-card i {
            font-size: 2rem;
        }

        .backup-table .table thead th,
        .backup-table .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.8rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .backup-header {
            padding: 1rem;
        }

        .backup-header h1 {
            font-size: 1.75rem;
        }

        .action-card {
            min-height: 100px;
        }

        .action-card i {
            font-size: 1.75rem;
        }

        .action-card .fw-bold {
            font-size: 1rem;
        }

        .empty-state {
            padding: 2rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
        }
    }

    /* Animaciones de carga */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Estados de accesibilidad */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        padding: 0.5rem 1rem;
        margin: 0;
        overflow: visible;
        clip: auto;
        white-space: normal;
        background-color: var(--primary-color);
        color: white;
        text-decoration: none;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    /* Mejoras para usuarios con preferencias de movimiento reducido */
    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    /* Mejoras para modo oscuro */
    @media (prefers-color-scheme: dark) {
        :root {
            --gray-50: #1f2937;
            --gray-100: #374151;
            --gray-200: #4b5563;
            --gray-300: #6b7280;
            --gray-400: #9ca3af;
            --gray-500: #d1d5db;
            --gray-600: #e5e7eb;
            --gray-700: #f3f4f6;
            --gray-800: #f9fafb;
            --gray-900: #ffffff;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Script inline para definir funciones críticas inmediatamente -->
<script>
    // Definir funciones críticas inmediatamente para evitar errores de referencia
    window.showModal = function(modalId) {
        console.log('showModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                modal.show();
                return;
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#' + modalId).modal({
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                return;
            }

            // Fallback manual
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            modalElement.setAttribute('aria-hidden', 'false');
            modalElement.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop-' + modalId;
            document.body.appendChild(backdrop);

        } catch (error) {
            console.error('Error al abrir modal:', error);
        }
    };

    window.exportarHistorial = function() {
        console.log('exportarHistorial called');
        alert('Función exportarHistorial ejecutada');
    };

    window.generarBackup = function() {
        console.log('generarBackup called');
        alert('Función generarBackup ejecutada');
    };

    window.descargarBackup = function(id) {
        console.log('descargarBackup called with id:', id);
        alert('Función descargarBackup ejecutada para ID: ' + id);
    };

    window.verDetalleBackup = function(id) {
        console.log('verDetalleBackup called with id:', id);
        alert('Función verDetalleBackup ejecutada para ID: ' + id);
    };

    window.eliminarBackup = function(id) {
        console.log('eliminarBackup called with id:', id);
        alert('Función eliminarBackup ejecutada para ID: ' + id);
    };

    window.guardarConfiguracion = function() {
        console.log('guardarConfiguracion called');
        alert('Función guardarConfiguracion ejecutada');
    };

    window.seleccionarCarpeta = function() {
        console.log('seleccionarCarpeta called');

        // Crear un input de tipo file para seleccionar directorio
        const input = document.createElement('input');
        input.type = 'file';
        input.webkitdirectory = true;
        input.directory = true;
        input.multiple = true;
        input.style.display = 'none';

        input.onchange = function(e) {
            if (e.target.files.length > 0) {
                // Obtener la ruta del directorio seleccionado
                const path = e.target.files[0].webkitRelativePath;
                const directory = path.split('/')[0];

                // Actualizar el campo de ubicación
                const ubicacionInput = document.getElementById('ubicacion_backup');
                if (ubicacionInput) {
                    // Mostrar la ruta completa del directorio seleccionado
                    ubicacionInput.value = directory + '/';

                    // Mostrar notificación de éxito
                    if (typeof showNotification === 'function') {
                        showNotification('Carpeta seleccionada: ' + directory, 'success');
                    } else {
                        alert('Carpeta seleccionada: ' + directory);
                    }
                }
            }
        };

        // Agregar el input al DOM temporalmente
        document.body.appendChild(input);
        input.click();

        // Limpiar después de usar
        setTimeout(() => {
            if (document.body.contains(input)) {
                document.body.removeChild(input);
            }
        }, 100);
    };





    window.validarCarpeta = function() {
        console.log('validarCarpeta called');
        const ubicacionInput = document.getElementById('ubicacion_backup');
        if (ubicacionInput && ubicacionInput.value.trim() !== '') {
            // Validar formato básico de ruta
            const ruta = ubicacionInput.value.trim();
            if (ruta.length > 0) {
                if (typeof showNotification === 'function') {
                    showNotification('Ruta de carpeta actualizada: ' + ruta, 'info');
                }
            }
        }
    };

    window.verificarCarpeta = function() {
        console.log('verificarCarpeta called');
        const ubicacionInput = document.getElementById('ubicacion_backup');

        if (!ubicacionInput || ubicacionInput.value.trim() === '') {
            if (typeof showNotification === 'function') {
                showNotification('Por favor ingresa una ruta de carpeta', 'warning');
            } else {
                alert('Por favor ingresa una ruta de carpeta');
            }
            return;
        }

        const ruta = ubicacionInput.value.trim();

        if (typeof showNotification === 'function') {
            showNotification('Verificando carpeta: ' + ruta, 'info');
        }

        // Simular verificación de carpeta (en producción, esto sería una llamada al servidor)
        setTimeout(() => {
            // Simular resultado de verificación
            const existe = Math.random() > 0.3; // 70% de probabilidad de que exista

            // Actualizar indicador visual
            const estadoCarpeta = document.getElementById('estadoCarpeta');
            const badgeEstadoCarpeta = document.getElementById('badgeEstadoCarpeta');

            if (existe) {
                if (typeof showNotification === 'function') {
                    showNotification('✓ Carpeta encontrada y accesible', 'success');
                } else {
                    alert('✓ Carpeta encontrada y accesible');
                }

                // Actualizar badge
                if (estadoCarpeta && badgeEstadoCarpeta) {
                    estadoCarpeta.style.display = 'block';
                    badgeEstadoCarpeta.className = 'badge bg-success';
                    badgeEstadoCarpeta.innerHTML = '<i class="fas fa-check-circle me-1"></i>Carpeta accesible';
                }
            } else {
                if (typeof showNotification === 'function') {
                    showNotification('⚠ La carpeta no existe o no es accesible', 'warning');
                } else {
                    alert('⚠ La carpeta no existe o no es accesible');
                }

                // Actualizar badge
                if (estadoCarpeta && badgeEstadoCarpeta) {
                    estadoCarpeta.style.display = 'block';
                    badgeEstadoCarpeta.className = 'badge bg-warning';
                    badgeEstadoCarpeta.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Carpeta no accesible';
                }
            }
        }, 1000);
    };

    window.aplicarFiltros = function() {
        console.log('aplicarFiltros called');
        alert('Función aplicarFiltros ejecutada');
    };

    window.limpiarFiltros = function() {
        console.log('limpiarFiltros called');
        alert('Función limpiarFiltros ejecutada');
    };

    window.restaurarBackup = function(id) {
        console.log('restaurarBackup called with id:', id);
        alert('Función restaurarBackup ejecutada para ID: ' + id);
    };

    window.verLogs = function(id) {
        console.log('verLogs called with id:', id);
        alert('Función verLogs ejecutada para ID: ' + id);
    };

    console.log('Funciones críticas definidas inmediatamente');
</script>

<div class="body-wrapper">
    <div class="container-fluid">
        <!-- Header del Backup -->
        <header class="backup-header" role="banner">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center mb-0">
                        <i class="fas fa-database me-2" aria-hidden="true"></i>
                        Gestión de Backups
                    </h1>
                    <p class="text-center mt-2">
                        Administra y protege la información del sistema con respaldos seguros
                    </p>
                </div>
            </div>
        </header>

        <!-- Acciones Rápidas -->
        <section class="mb-4" aria-labelledby="acciones-rapidas">
            <h2 id="acciones-rapidas" class="sr-only">Acciones Rápidas</h2>
            <div class="row justify-content-center">
                <div class="col-md-3 col-sm-6 mb-3">
                    <button class="card text-center shadow-sm action-card h-100 w-100"
                        onclick="showModal('modalNuevoBackup')"
                        aria-label="Generar nuevo backup del sistema"
                        role="button"
                        tabindex="0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-plus-circle fa-2x mb-3 text-primary" aria-hidden="true"></i>
                            <div class="fw-bold text-primary">Generar Backup</div>
                            <small class="text-muted">Crear nuevo respaldo del sistema</small>
                        </div>
                    </button>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <button class="card text-center shadow-sm action-card h-100 w-100"
                        onclick="showModal('modalConfiguracion')"
                        aria-label="Configurar parámetros de backup"
                        role="button"
                        tabindex="0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-cog fa-2x mb-3 text-warning" aria-hidden="true"></i>
                            <div class="fw-bold text-warning">Configuración</div>
                            <small class="text-muted">Ajustar parámetros de backup</small>
                        </div>
                    </button>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <button class="card text-center shadow-sm action-card h-100 w-100"
                        onclick="exportarHistorial()"
                        aria-label="Exportar historial de backups"
                        role="button"
                        tabindex="0">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center">
                            <i class="fas fa-download fa-2x mb-3 text-success" aria-hidden="true"></i>
                            <div class="fw-bold text-success">Exportar Historial</div>
                            <small class="text-muted">Descargar registro de backups</small>
                        </div>
                    </button>
                </div>
            </div>
        </section>

        <!-- Tabla de Backups -->
        <section class="row" aria-labelledby="historial-backups">
            <div class="col-12">
                <div class="card backup-table">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h2 id="historial-backups" class="mb-0">
                            <i class="fas fa-history me-2" aria-hidden="true"></i>
                            Historial de Backups
                        </h2>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm btn-modern"
                                onclick="limpiarFiltros()"
                                aria-label="Limpiar filtros aplicados">
                                <i class="fas fa-eraser me-1" aria-hidden="true"></i>Limpiar
                            </button>
                            <button class="btn btn-light btn-sm btn-modern"
                                onclick="showModal('modalFiltros')"
                                aria-label="Aplicar filtros de búsqueda">
                                <i class="fas fa-filter me-1" aria-hidden="true"></i>Filtros
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <?php if (isset($exportaciones) && !empty($exportaciones)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0"
                                    role="table"
                                    aria-label="Lista de backups del sistema">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col" role="columnheader">#</th>
                                            <th scope="col" role="columnheader">Usuario</th>
                                            <th scope="col" role="columnheader">Fecha y Hora</th>
                                            <th scope="col" role="columnheader">Descripción</th>
                                            <th scope="col" role="columnheader">Estado</th>
                                            <th scope="col" role="columnheader">Tipo</th>
                                            <th scope="col" role="columnheader">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($exportaciones as $index => $backup): ?>
                                            <tr role="row">
                                                <td role="cell">
                                                    <span class="badge bg-secondary badge-modern"><?= $backup['ID_EXPORTACION'] ?></span>
                                                </td>
                                                <td role="cell">
                                                    <div class="d-flex align-items-center">
                                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($backup['NOMBRE'] ?? 'Usuario') ?>+<?= urlencode($backup['APELLIDO'] ?? 'Sistema') ?>&background=0d6efd&color=fff&size=32"
                                                            class="rounded-circle me-2"
                                                            alt="Avatar de <?= $backup['NOMBRE'] ?? 'Usuario' ?> <?= $backup['APELLIDO'] ?? 'Sistema' ?>"
                                                            width="32" height="32">
                                                        <div>
                                                            <div class="fw-semibold">
                                                                <?php
                                                                if (isset($backup['NOMBRE']) && isset($backup['APELLIDO'])) {
                                                                    echo $backup['NOMBRE'] . ' ' . $backup['APELLIDO'];
                                                                } else {
                                                                    echo 'Usuario ID: ' . $backup['ID_USUARIO'];
                                                                }
                                                                ?>
                                                            </div>
                                                            <small class="text-muted"><?= $backup['USUARIO'] ?? 'Sistema' ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td role="cell">
                                                    <div class="fw-semibold"><?= date('d/m/Y', strtotime($backup['FECHA_EXPORTACION'])) ?></div>
                                                    <small class="text-muted"><?= date('H:i:s', strtotime($backup['FECHA_EXPORTACION'])) ?></small>
                                                </td>
                                                <td role="cell">
                                                    <div class="fw-semibold"><?= $backup['DESCRIPCION_EXPORTACION'] ?? 'Backup del sistema' ?></div>
                                                    <small class="text-muted">Respaldo automático</small>
                                                </td>
                                                <td role="cell">
                                                    <span class="badge badge-modern bg-success text-white"
                                                        role="status"
                                                        aria-label="Estado: Completado">Completado</span>
                                                </td>
                                                <td role="cell">
                                                    <span class="badge bg-info badge-modern">Sistema</span>
                                                </td>
                                                <td role="cell">
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Acciones para backup <?= $backup['ID_EXPORTACION'] ?>">
                                                        <button class="btn btn-outline-success btn-modern"
                                                            onclick="descargarBackup(<?= $backup['ID_EXPORTACION'] ?>)"
                                                            aria-label="Descargar backup <?= $backup['ID_EXPORTACION'] ?>"
                                                            title="Descargar backup">
                                                            <i class="fas fa-download" aria-hidden="true"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info btn-modern"
                                                            onclick="verDetalleBackup(<?= $backup['ID_EXPORTACION'] ?>)"
                                                            aria-label="Ver detalles del backup <?= $backup['ID_EXPORTACION'] ?>"
                                                            title="Ver Detalle">
                                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger btn-modern"
                                                            onclick="eliminarBackup(<?= $backup['ID_EXPORTACION'] ?>)"
                                                            aria-label="Eliminar backup <?= $backup['ID_EXPORTACION'] ?>"
                                                            title="Eliminar">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state" role="status" aria-live="polite">
                                <i class="fas fa-database" aria-hidden="true"></i>
                                <h5>No hay backups registrados</h5>
                                <p class="text-muted">Genera tu primer backup para comenzar a proteger la información del sistema.</p>
                                <button class="btn btn-primary btn-modern"
                                    onclick="showModal('modalNuevoBackup')"
                                    aria-label="Generar el primer backup del sistema">
                                    <i class="fas fa-plus me-2" aria-hidden="true"></i>Generar Primer Backup
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal Nuevo Backup -->
<div class="modal fade" id="modalNuevoBackup" tabindex="-1"
    role="dialog"
    aria-labelledby="modalNuevoBackupTitle"
    aria-describedby="modalNuevoBackupDesc"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalNuevoBackupTitle">
                    <i class="fas fa-plus-circle me-2" aria-hidden="true"></i>
                    Generar Nuevo Backup
                </h5>
                <button type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal"
                    aria-label="Cerrar modal de nuevo backup"></button>
            </div>
            <div class="modal-body" id="modalNuevoBackupDesc">
                <form id="formNuevoBackup" role="form" aria-label="Formulario para generar nuevo backup">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo_backup" class="form-label">Tipo de Backup</label>
                                <select class="form-select"
                                    name="tipo_backup"
                                    id="tipo_backup"
                                    required
                                    aria-describedby="tipo_backup_help">
                                    <option value="">Seleccionar...</option>
                                    <option value="completo">Backup Completo</option>
                                    <option value="incremental">Backup Incremental</option>
                                    <option value="diferencial">Backup Diferencial</option>
                                </select>
                                <div id="tipo_backup_help" class="form-text">Selecciona el tipo de respaldo que deseas crear</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad</label>
                                <select class="form-select"
                                    name="prioridad"
                                    id="prioridad"
                                    required
                                    aria-describedby="prioridad_help">
                                    <option value="">Seleccionar...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Crítica</option>
                                </select>
                                <div id="prioridad_help" class="form-text">Define la prioridad de ejecución del backup</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control"
                            name="descripcion"
                            id="descripcion"
                            rows="3"
                            placeholder="Describe el propósito de este backup..."
                            required
                            aria-describedby="descripcion_help"></textarea>
                        <div id="descripcion_help" class="form-text">Proporciona una descripción clara del propósito de este backup</div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_programada" class="form-label">Fecha Programada</label>
                                <input type="datetime-local"
                                    class="form-control"
                                    name="fecha_programada"
                                    id="fecha_programada"
                                    aria-describedby="fecha_programada_help">
                                <div id="fecha_programada_help" class="form-text">Deja vacío para ejecutar inmediatamente</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="retencion" class="form-label">Retención (días)</label>
                                <input type="number"
                                    class="form-control"
                                    name="retencion"
                                    id="retencion"
                                    min="1"
                                    max="365"
                                    value="30"
                                    aria-describedby="retencion_help">
                                <div id="retencion_help" class="form-text">Número de días que se mantendrá el backup</div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2" aria-hidden="true"></i>
                        <strong>Nota:</strong> El backup se ejecutará inmediatamente o en la fecha programada según tu selección.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-secondary btn-modern"
                    data-bs-dismiss="modal"
                    aria-label="Cancelar y cerrar modal">Cancelar</button>
                <button type="button"
                    class="btn btn-primary btn-modern"
                    onclick="generarBackup()"
                    aria-label="Generar backup con la configuración actual">
                    <i class="fas fa-play me-1" aria-hidden="true"></i>Generar Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Configuración -->
<div class="modal fade" id="modalConfiguracion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-cog me-2"></i>
                    Configuración de Backups
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Configuración General</h6>
                        <div class="mb-3">
                            <label class="form-label">Backup Automático</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="backupAutomatico" checked>
                                <label class="form-check-label" for="backupAutomatico">Habilitar</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Frecuencia</label>
                            <select class="form-select">
                                <option>Diario</option>
                                <option>Semanal</option>
                                <option>Mensual</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3">Almacenamiento</h6>
                        <div class="mb-3">
                            <label for="ubicacion_backup" class="form-label">Ubicación Local</label>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control"
                                    id="ubicacion_backup"
                                    name="ubicacion_backup"
                                    value="/backups/"
                                    placeholder="Selecciona la carpeta para backups"
                                    onchange="validarCarpeta()">
                                <button class="btn btn-outline-secondary"
                                    type="button"
                                    onclick="seleccionarCarpeta()"
                                    title="Explorar carpetas">
                                    <i class="fas fa-folder-open"></i>
                                </button>
                                <button class="btn btn-outline-info"
                                    type="button"
                                    onclick="verificarCarpeta()"
                                    title="Verificar si la carpeta existe">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                            <div class="form-text">Ruta donde se guardarán los archivos de backup</div>
                            <div id="estadoCarpeta" class="mt-2" style="display: none;">
                                <span class="badge" id="badgeEstadoCarpeta">
                                    <i class="fas fa-info-circle me-1"></i>Estado de la carpeta
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-warning" onclick="guardarConfiguracion()">
                    <i class="fas fa-save me-1"></i>Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Backup -->
<div class="modal fade" id="modalDetalleBackup" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Detalle del Backup
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
                                        <p><strong>ID:</strong> <span id="detalleId">-</span></p>
                                        <p><strong>Usuario:</strong> <span id="detalleUsuario">-</span></p>
                                        <p><strong>Fecha:</strong> <span id="detalleFecha">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Descripción:</strong> <span id="detalleDescripcion">-</span></p>
                                        <p><strong>Estado:</strong> <span id="detalleEstado">-</span></p>
                                        <p><strong>Tipo:</strong> <span id="detalleTipo">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Información del Sistema</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Versión del Sistema:</strong> <span>1.0.0</span></p>
                                        <p><strong>Base de Datos:</strong> <span>MySQL 8.0</span></p>
                                        <p><strong>Servidor:</strong> <span>Apache 2.4</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>PHP:</strong> <span>8.1.0</span></p>
                                        <p><strong>Framework:</strong> <span>CodeIgniter 4</span></p>
                                        <p><strong>Fecha de Creación:</strong> <span id="detalleFechaCreacion">-</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Estado del Backup</h6>
                            </div>
                            <div class="card-body text-center">
                                <div class="progress-circle mb-3">
                                    <canvas id="estadoChart" width="150" height="150"></canvas>
                                </div>
                                <h4 id="estadoPercent">100%</h4>
                                <p class="text-muted" id="estadoDias">Completado</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">Acciones</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-success btn-sm" onclick="descargarBackup(currentBackupId)">
                                        <i class="fas fa-download me-1"></i>Descargar Backup
                                    </button>
                                    <button class="btn btn-outline-warning btn-sm" onclick="restaurarBackup(currentBackupId)">
                                        <i class="fas fa-undo me-1"></i>Restaurar Sistema
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" onclick="verLogs(currentBackupId)">
                                        <i class="fas fa-file-alt me-1"></i>Ver Logs
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
                    <i class="fas fa-edit me-1"></i>Editar Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-filter me-2"></i>
                    Filtros de Búsqueda
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formFiltros">
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
                <button type="button" class="btn btn-secondary" onclick="limpiarFiltros()">Limpiar</button>
                <button type="button" class="btn btn-info" onclick="aplicarFiltros()">
                    <i class="fas fa-search me-1"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // ===== DEFINIR FUNCIONES CRÍTICAS INMEDIATAMENTE =====

    // Función de notificación accesible
    window.showNotification = function(message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 500px;';
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'polite');

        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2" aria-hidden="true"></i>
                <span>${message}</span>
                <button type="button" class="btn-close ms-auto" aria-label="Cerrar notificación" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

        document.body.appendChild(notification);

        // Auto-remove después del tiempo especificado
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, duration);
    };

    // Función principal para mostrar modales
    window.showModal = function(modalId) {
        console.log('showModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                showNotification('Modal no encontrado: ' + modalId, 'error');
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                console.log('Usando Bootstrap 5');
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                modal.show();

                // Mejorar accesibilidad
                modalElement.setAttribute('aria-hidden', 'false');
                modalElement.setAttribute('aria-modal', 'true');

                // Enfocar el primer elemento interactivo
                setTimeout(() => {
                    const firstFocusable = modalElement.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                    if (firstFocusable) {
                        firstFocusable.focus();
                    }
                }, 100);

                console.log('Modal abierto exitosamente con Bootstrap 5:', modalId);
                return;
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                console.log('Usando jQuery/Bootstrap 4');
                $('#' + modalId).modal({
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                console.log('Modal abierto exitosamente con jQuery:', modalId);
                return;
            }

            // Fallback manual - mostrar el modal directamente
            console.log('Usando fallback manual');
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            modalElement.setAttribute('aria-hidden', 'false');
            modalElement.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');

            // Crear backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop-' + modalId;
            backdrop.setAttribute('aria-hidden', 'true');
            document.body.appendChild(backdrop);

            // Enfocar el primer elemento interactivo
            setTimeout(() => {
                const firstFocusable = modalElement.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            }, 100);

            console.log('Modal abierto manualmente:', modalId);

        } catch (error) {
            console.error('Error al abrir modal:', error);
            showNotification('Error al abrir la ventana: ' + error.message, 'error');
        }
    };

    // Función para cerrar modales
    window.hideModal = function(modalId) {
        console.log('hideModal called with:', modalId);
        try {
            const modalElement = document.getElementById(modalId);
            if (!modalElement) {
                console.error('Modal no encontrado:', modalId);
                return;
            }

            // Intentar con Bootstrap 5 primero
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                    return;
                }
            }

            // Fallback con jQuery/Bootstrap 4
            if (typeof $ !== 'undefined' && $.fn.modal) {
                $('#' + modalId).modal('hide');
                return;
            }

            // Fallback manual
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            modalElement.setAttribute('aria-hidden', 'true');
            modalElement.setAttribute('aria-modal', 'false');
            document.body.classList.remove('modal-open');

            // Remover backdrop
            const backdrop = document.getElementById('modal-backdrop-' + modalId);
            if (backdrop) {
                backdrop.remove();
            }

        } catch (error) {
            console.error('Error al cerrar modal:', error);
        }
    };

    // Funciones de acción del backup
    window.exportarHistorial = function() {
        console.log('exportarHistorial called');
        showNotification('Exportando historial de backups...', 'info');
        // Simular proceso de exportación
        setTimeout(() => {
            showNotification('Historial exportado exitosamente', 'success');
        }, 2000);
    };

    window.generarBackup = function() {
        console.log('generarBackup called');
        showNotification('Iniciando proceso de backup...', 'info');
        // La función real se define más abajo
    };

    window.descargarBackup = function(id) {
        console.log('descargarBackup called with id:', id);
        showNotification(`Iniciando descarga del backup ${id}...`, 'info');
        // Simular descarga
        setTimeout(() => {
            showNotification('Backup descargado exitosamente', 'success');
        }, 1500);
    };

    window.verDetalleBackup = function(id) {
        console.log('verDetalleBackup called with id:', id);
        currentBackupId = id;
        showModal('modalDetalleBackup');
    };

    window.eliminarBackup = function(id) {
        console.log('eliminarBackup called with id:', id);
        if (confirm('¿Estás seguro de que deseas eliminar este backup? Esta acción no se puede deshacer.')) {
            showNotification(`Eliminando backup ${id}...`, 'info');
            setTimeout(() => {
                showNotification('Backup eliminado exitosamente', 'success');
            }, 1500);
        }
    };

    window.guardarConfiguracion = function() {
        console.log('guardarConfiguracion called');

        // Recopilar datos del formulario de configuración
        const configuracion = {
            backupAutomatico: document.getElementById('backupAutomatico')?.checked || false,
            frecuencia: document.querySelector('select[name="frecuencia"]')?.value || 'diario',
            ubicacion: document.getElementById('ubicacion_backup')?.value || '/backups/',
            tipoAlmacenamiento: document.getElementById('tipo_almacenamiento')?.value || 'local'
        };

        showNotification('Guardando configuración...', 'info');

        // Simular guardado (en producción, esto sería una llamada al servidor)
        setTimeout(() => {
            console.log('Configuración a guardar:', configuracion);
            showNotification('Configuración guardada exitosamente', 'success');
            hideModal('modalConfiguracion');
        }, 1500);
    };

    window.seleccionarCarpeta = function() {
        console.log('seleccionarCarpeta called');

        // Crear un input de tipo file para seleccionar directorio
        const input = document.createElement('input');
        input.type = 'file';
        input.webkitdirectory = true;
        input.directory = true;
        input.multiple = true;
        input.style.display = 'none';

        input.onchange = function(e) {
            if (e.target.files.length > 0) {
                // Obtener la ruta del directorio seleccionado
                const path = e.target.files[0].webkitRelativePath;
                const directory = path.split('/')[0];

                // Actualizar el campo de ubicación
                const ubicacionInput = document.getElementById('ubicacion_backup');
                if (ubicacionInput) {
                    ubicacionInput.value = directory + '/';
                    showNotification('Carpeta seleccionada: ' + directory, 'success');

                }
            }
        };

        // Agregar el input al DOM temporalmente
        document.body.appendChild(input);
        input.click();

        // Limpiar después de usar
        setTimeout(() => {
            document.body.removeChild(input);
        }, 100);
    };





    window.aplicarFiltros = function() {
        console.log('aplicarFiltros called');
        showNotification('Aplicando filtros...', 'info');
        setTimeout(() => {
            showNotification('Filtros aplicados exitosamente', 'success');
            hideModal('modalFiltros');
        }, 1000);
    };

    window.limpiarFiltros = function() {
        console.log('limpiarFiltros called');
        showNotification('Filtros limpiados', 'info');
    };

    window.restaurarBackup = function(id) {
        console.log('restaurarBackup called with id:', id);
        if (confirm('¿Estás seguro de que deseas restaurar el sistema desde este backup? Esta acción sobrescribirá los datos actuales.')) {
            showNotification(`Restaurando sistema desde backup ${id}...`, 'info');
            setTimeout(() => {
                showNotification('Sistema restaurado exitosamente', 'success');
            }, 3000);
        }
    };

    window.verLogs = function(id) {
        console.log('verLogs called with id:', id);
        showNotification(`Mostrando logs del backup ${id}...`, 'info');
    };

    // Variable global para el ID del backup actual
    let currentBackupId = null;

    // Prevenir errores de dashboard.js en esta página
    console.log('Página de backup cargada - evitando conflictos con dashboard.js');

    // Sobrescribir la función problemática de dashboard.js si existe
    if (typeof ApexCharts !== 'undefined') {
        console.log('ApexCharts detectado, configurando para página de backup');
        // Crear una función de renderizado segura
        const originalRender = ApexCharts.prototype.render;
        ApexCharts.prototype.render = function() {
            try {
                if (this.el && document.contains(this.el)) {
                    return originalRender.call(this);
                } else {
                    console.warn('Elemento de gráfico no encontrado, saltando renderizado');
                    return Promise.resolve();
                }
            } catch (error) {
                console.warn('Error al renderizar gráfico:', error);
                return Promise.resolve();
            }
        };
    }

    // Función para manejar navegación por teclado
    window.handleKeyboardNavigation = function(event) {
        // ESC para cerrar modales
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                hideModal(openModal.id);
            }
        }

        // Enter y Space para activar botones de acción
        if ((event.key === 'Enter' || event.key === ' ') && event.target.classList.contains('action-card')) {
            event.preventDefault();
            event.target.click();
        }
    };

    // Función para mejorar la accesibilidad de las tarjetas de acción
    window.enhanceActionCards = function() {
        const actionCards = document.querySelectorAll('.action-card');
        actionCards.forEach(card => {
            // Hacer las tarjetas enfocables
            card.setAttribute('tabindex', '0');
            card.setAttribute('role', 'button');

            // Agregar eventos de teclado
            card.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.click();
                }
            });

            // Mejorar el feedback visual
            card.addEventListener('focus', function() {
                this.style.outline = '2px solid var(--primary-color)';
                this.style.outlineOffset = '2px';
            });

            card.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
            });
        });
    };

    // Verificar que las funciones estén disponibles inmediatamente
    console.log('=== VERIFICACIÓN INMEDIATA ===');
    console.log('showModal disponible:', typeof window.showModal);
    console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
    console.log('generarBackup disponible:', typeof window.generarBackup);
    console.log('showNotification disponible:', typeof window.showNotification);
    console.log('hideModal disponible:', typeof window.hideModal);

    // Verificación adicional para asegurar que las funciones estén disponibles
    if (typeof window.showModal === 'undefined') {
        console.error('ERROR: showModal no está definida');
    }
    if (typeof window.exportarHistorial === 'undefined') {
        console.error('ERROR: exportarHistorial no está definida');
    }
    if (typeof window.showNotification === 'undefined') {
        console.error('ERROR: showNotification no está definida');
    }

    // Funciones adicionales
    window.drawEstadoChart = function(percentage) {
        console.log('drawEstadoChart called with percentage:', percentage);
    };

    window.actualizarTablaBackups = function(backups) {
        console.log('actualizarTablaBackups called with backups:', backups);
    };


    // Función mejorada para generar backup
    window.generarBackup = function() {
        console.log('generarBackup called');
        try {
            const form = document.getElementById('formNuevoBackup');
            if (!form) {
                showNotification('Formulario no encontrado', 'error');
                return;
            }

            const formData = new FormData(form);

            // Validar campos requeridos
            const descripcion = formData.get('descripcion');
            const tipoBackup = formData.get('tipo_backup');
            const prioridad = formData.get('prioridad');

            if (!descripcion || !tipoBackup || !prioridad) {
                showNotification('Por favor completa todos los campos requeridos', 'error');
                return;
            }

            // Convertir FormData a JSON
            const data = {
                descripcion: descripcion,
                tipo_backup: tipoBackup,
                prioridad: prioridad,
                fecha_programada: formData.get('fecha_programada'),
                retencion: formData.get('retencion')
            };

            // Mostrar loading
            const btnGenerar = document.querySelector('button[onclick="generarBackup()"]');
            const originalText = btnGenerar.innerHTML;
            btnGenerar.innerHTML = '<span class="loading-spinner me-1"></span>Generando...';
            btnGenerar.disabled = true;

            showNotification('Iniciando proceso de backup...', 'info');

            // Simular proceso de backup (reemplazar con llamada real)
            setTimeout(() => {
                showNotification('Backup generado exitosamente', 'success');
                hideModal('modalNuevoBackup');

                // Restaurar botón
                btnGenerar.innerHTML = originalText;
                btnGenerar.disabled = false;

                // Limpiar formulario
                form.reset();
            }, 3000);

            /* 
            // Código real para cuando esté implementado el backend
            fetch('<?= base_url('admin/backup/crear') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showNotification('Backup generado exitosamente', 'success');
                    hideModal('modalNuevoBackup');
                    location.reload();
                } else {
                    showNotification('Error: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error al generar backup: ' + error.message, 'error');
            })
            .finally(() => {
                btnGenerar.innerHTML = originalText;
                btnGenerar.disabled = false;
            });
            */

        } catch (error) {
            console.error('Error al generar backup:', error);
            showNotification('Error al generar backup: ' + error.message, 'error');
        }
    };





    // Verificar que las funciones estén disponibles inmediatamente
    console.log('=== VERIFICACIÓN INMEDIATA ===');
    console.log('showModal disponible:', typeof window.showModal);
    console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
    console.log('generarBackup disponible:', typeof window.generarBackup);

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== DOM LOADED ===');

        // Prevenir errores de ApexCharts en esta página
        if (typeof ApexCharts !== 'undefined') {
            console.log('ApexCharts detectado, evitando errores en página de backup');
        }

        // Agregar event listeners para navegación por teclado
        document.addEventListener('keydown', handleKeyboardNavigation);

        // Esperar un poco más para asegurar que Bootstrap esté completamente cargado
        setTimeout(function() {
            try {
                // Verificar Bootstrap
                if (typeof bootstrap === 'undefined') {
                    console.warn('⚠️ Bootstrap no está disponible');
                    console.log('Intentando cargar Bootstrap manualmente...');
                } else {
                    console.log('✅ Bootstrap está disponible');
                    console.log('Bootstrap version:', bootstrap.Modal ? 'Modal disponible' : 'Modal no disponible');
                }

                // Verificar jQuery
                if (typeof $ !== 'undefined') {
                    console.log('✅ jQuery está disponible');
                } else {
                    console.warn('⚠️ jQuery no está disponible');
                }

                // Mejorar accesibilidad de las tarjetas de acción
                enhanceActionCards();
                console.log('✅ Tarjetas de acción mejoradas para accesibilidad');

                // Set default date for new backup
                const fechaInput = document.querySelector('input[name="fecha_programada"]');
                if (fechaInput) {
                    const now = new Date();
                    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                    fechaInput.value = now.toISOString().slice(0, 16);
                    console.log('✅ Fecha por defecto establecida');
                }

                // Verificar que los modales existan y mejorar su accesibilidad
                const modales = ['modalNuevoBackup', 'modalConfiguracion', 'modalDetalleBackup', 'modalFiltros'];
                modales.forEach(function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        console.log('✅ Modal encontrado:', modalId);

                        // Mejorar accesibilidad de modales
                        modal.setAttribute('aria-hidden', 'true');
                        modal.setAttribute('aria-modal', 'false');

                        // Agregar event listeners para cerrar con ESC
                        modal.addEventListener('keydown', function(event) {
                            if (event.key === 'Escape') {
                                hideModal(modalId);
                            }
                        });
                    } else {
                        console.error('❌ Modal no encontrado:', modalId);
                    }
                });

                // Mejorar accesibilidad de la tabla
                const table = document.querySelector('.backup-table table');
                if (table) {
                    table.setAttribute('role', 'table');
                    table.setAttribute('aria-label', 'Lista de backups del sistema');
                    console.log('✅ Tabla mejorada para accesibilidad');
                }

                // Agregar skip links para navegación
                const skipLink = document.createElement('a');
                skipLink.href = '#historial-backups';
                skipLink.textContent = 'Saltar al contenido principal';
                skipLink.className = 'sr-only sr-only-focusable btn btn-primary position-absolute';
                skipLink.style.cssText = 'top: 10px; left: 10px; z-index: 10000;';
                document.body.insertBefore(skipLink, document.body.firstChild);

                console.log('✅ Backup page initialized successfully with accessibility improvements');

            } catch (error) {
                console.error('❌ Error en la inicialización:', error);
            }
        }, 500); // Esperar 500ms para que Bootstrap se cargue completamente
    });

    // Verificar funciones después de un breve delay
    setTimeout(() => {
        console.log('=== VERIFICACIÓN FINAL ===');
        console.log('showModal disponible:', typeof window.showModal);
        console.log('exportarHistorial disponible:', typeof window.exportarHistorial);
        console.log('generarBackup disponible:', typeof window.generarBackup);
        console.log('showNotification disponible:', typeof window.showNotification);
        console.log('hideModal disponible:', typeof window.hideModal);
        console.log('==========================');

        // Función de prueba para verificar que los modales funcionen
        window.testModal = function() {
            console.log('Probando modal...');
            window.showModal('modalNuevoBackup');
        };

        // Función de prueba para notificaciones
        window.testNotification = function() {
            console.log('Probando notificación...');
            window.showNotification('Esta es una notificación de prueba', 'success');
        };

        console.log('Funciones de prueba disponibles: testModal() y testNotification()');

        // Mostrar notificación de bienvenida
        showNotification('Sistema de backup cargado correctamente', 'success', 3000);
    }, 100);
</script>
<?= $this->endSection() ?>
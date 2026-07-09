<?php
/**
 * partials/periodo_selector.php
 *
 * Componente reutilizable para mostrar el período académico.
 * - Todos los roles ven el período actual (actualizado automáticamente).
 * - Solo el rol Coordinador (2) ve el dropdown para cambiar de período.
 *
 * Requiere que el helper 'tiempo' esté cargado (se auto-carga en Autoload).
 */

helper('tiempo');
$periodoUI   = obtener_periodo_academico_para_ui();
$esCoord     = ((int) session('rol') === 2);
$esHistorico = $periodoUI['es_historico'] ?? false;
?>

<?php if ($esCoord): ?>
    <?php $todosPeriodos = obtener_todos_los_periodos(); ?>
    <!-- ═══ Selector de período (solo coordinador) ═══ -->
    <link rel="stylesheet" href="<?= base_url('sistema/assets/css/periodo_selector.css') ?>">
    <div class="dashboard-period-box mt-2">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <i class="fas fa-calendar-check text-primary" style="font-size: 1.1rem;"></i>
            <span style="font-weight: 600; color: var(--primary, #0369a1); font-size: 0.95rem;">
                Período académico:
            </span>

            <!-- Dropdown Bootstrap -->
            <div class="periodo-selector-wrapper dropdown">
                <button class="periodo-selector-btn dropdown-toggle" type="button"
                        id="periodoSelectorBtn" data-bs-toggle="dropdown"
                        aria-expanded="false" data-bs-auto-close="outside">
                    <i class="fas fa-calendar-alt periodo-icon"></i>
                    <span id="periodoSelectorLabel">
                        <?= esc($periodoUI['nombre'] ?? 'Sin período') ?>
                    </span>
                    <?php if ($esHistorico): ?>
                        <span class="periodo-badge-actual historico">
                            <i class="fas fa-history"></i> Histórico
                        </span>
                    <?php else: ?>
                        <span class="periodo-badge-actual actual">
                            <i class="fas fa-check-circle"></i> Actual
                        </span>
                    <?php endif; ?>
                    <i class="fas fa-chevron-down periodo-chevron"></i>
                </button>

                <div class="dropdown-menu periodo-dropdown-menu" aria-labelledby="periodoSelectorBtn">
                    <div class="dropdown-header">
                        <i class="fas fa-clock me-1"></i> Seleccionar período
                    </div>
                    <div class="dropdown-divider"></div>

                    <?php if (!empty($todosPeriodos)): ?>
                        <?php foreach ($todosPeriodos as $per): ?>
                            <button type="button"
                                    class="periodo-dropdown-item <?= ((int) $periodoUI['id'] === $per['id']) ? 'active' : '' ?>"
                                    data-periodo-id="<?= $per['id'] ?>"
                                    onclick="cambiarPeriodoAcademico(<?= $per['id'] ?>, this)">
                                <span class="periodo-item-check">
                                    <?php if ((int) $periodoUI['id'] === $per['id']): ?>
                                        <i class="fas fa-check"></i>
                                    <?php endif; ?>
                                </span>
                                <span class="periodo-item-nombre"><?= esc($per['nombre']) ?></span>
                                <?php if ($per['es_actual']): ?>
                                    <span class="periodo-item-badge actual-badge">Actual</span>
                                <?php endif; ?>
                            </button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-3">
                            <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                            <small>No hay períodos registrados</small>
                        </div>
                    <?php endif; ?>

                    <?php if ($esHistorico): ?>
                        <div class="dropdown-divider"></div>
                        <button type="button" class="periodo-btn-restaurar"
                                onclick="restaurarPeriodoActual()">
                            <i class="fas fa-undo-alt"></i>
                            Volver al período actual
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    function cambiarPeriodoAcademico(idPeriodo, btnEl) {
        // Feedback visual inmediato
        const label = document.getElementById('periodoSelectorLabel');
        const originalText = label.textContent;
        label.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cambiando...';

        fetch('<?= base_url('api/periodos/cambiar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'id_periodo=' + encodeURIComponent(idPeriodo)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Recargar para reflejar datos del período seleccionado
                window.location.reload();
            } else {
                label.textContent = originalText;
                alert(data.error || 'No se pudo cambiar el período');
            }
        })
        .catch(() => {
            label.textContent = originalText;
            alert('Error de conexión al cambiar el período');
        });
    }

    function restaurarPeriodoActual() {
        const label = document.getElementById('periodoSelectorLabel');
        label.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Restaurando...';

        fetch('<?= base_url('api/periodos/restaurar') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'No se pudo restaurar el período');
                window.location.reload();
            }
        })
        .catch(() => {
            alert('Error de conexión');
            window.location.reload();
        });
    }
    </script>

<?php else: ?>
    <!-- ═══ Solo lectura (coord, docente, estudiante) ═══ -->
    <div class="dashboard-period-box mt-2">
        <h5 class="mb-0" style="color: var(--primary); font-weight: 600;">
            <i class="fas fa-calendar-check me-2 text-primary"></i>
            Período académico actual:
            <?php if (!empty($periodoUI['nombre'])): ?>
                <span class="ms-1 fw-bold" style="color: #0f172a;">
                    <?= esc($periodoUI['nombre']) ?>
                </span>
                <?php if (!empty($periodoUI['rango'])): ?>
                    <span class="text-muted fs-6 fw-normal ms-1" style="font-weight: 500;">
                        (<?= esc($periodoUI['rango']) ?>)
                    </span>
                <?php endif; ?>
            <?php else: ?>
                <span class="text-muted fw-normal ms-1" style="font-weight: 500;">
                    No hay período configurado
                </span>
            <?php endif; ?>
        </h5>
    </div>
<?php endif; ?>

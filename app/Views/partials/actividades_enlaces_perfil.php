<?php
/**
 * Listado de actividades con enlace de acceso (TAB_ACTIVIDADES_EDUCACION.ENLACE), registrado por el coordinador.
 *
 * @var array<int, array<string, mixed>> $filas
 * @var string $urlDetalleBase ej. base_url('estudiante/actividades-educacion/detalle')
 */
use App\Models\ActividadesEducacionModel;

$filas = $filas ?? [];
if ($filas === []) {
    return;
}
$urlDetalleBase = rtrim((string) ($urlDetalleBase ?? ''), '/');
?>
<div class="card border-primary border-opacity-25 shadow-sm mb-4">
    <div class="card-header bg-primary bg-opacity-10">
        <h5 class="card-title mb-0 text-primary">
            <i class="fas fa-link me-2"></i>Mis actividades educativas — enlaces de acceso
        </h5>
        <p class="small text-muted mb-0 mt-1">
            El <strong>coordinador</strong> registra el enlace (Meet, Zoom, Teams, etc.) en <em>Actividades educativas</em> al crear o editar la actividad.
            Aquí ves el mismo enlace en tu perfil.
        </p>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Actividad</th>
                        <th class="d-none d-md-table-cell">Modalidad</th>
                        <th>Enlace</th>
                        <th class="text-end d-none d-sm-table-cell">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($filas as $act): ?>
                        <?php
                        $slug = ActividadesEducacionModel::slugModalidadDesdeNombre($act['MODALIDAD'] ?? '');
                        $necesitaEnlace = in_array($slug, ['virtual', 'hibrida'], true);
                        $enlace = trim((string) ($act['ENLACE'] ?? ''));
                        $idAct = (int) ($act['ID_ACTIVIDAD_EDUCACION'] ?? 0);
                        $urlDetalle = $urlDetalleBase !== '' && $idAct > 0 ? $urlDetalleBase . '/' . $idAct : '';
                        ?>
                        <tr>
                            <td>
                                <strong><?= esc($act['NOMBRE_ACTIVIDAD'] ?? '') ?></strong>
                                <div class="d-md-none small text-muted"><?= esc($act['MODALIDAD'] ?? '—') ?></div>
                            </td>
                            <td class="d-none d-md-table-cell"><?= esc($act['MODALIDAD'] ?? '—') ?></td>
                            <td>
                                <?php if (!$necesitaEnlace): ?>
                                    <span class="text-muted">—</span>
                                <?php elseif ($enlace !== ''): ?>
                                    <?php
                                    $hrefEn = preg_match('#^https?://#i', $enlace) ? $enlace : 'https://' . $enlace;
                                    ?>
                                    <a href="<?= esc($hrefEn, 'attr') ?>" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>Abrir enlace
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-25 text-dark">Pendiente</span>
                                    <span class="small text-muted d-block">El coordinador aún no registra el enlace.</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end d-none d-sm-table-cell">
                                <?php if ($urlDetalle !== ''): ?>
                                    <a href="<?= esc($urlDetalle, 'attr') ?>" class="btn btn-sm btn-link">Ver actividad</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

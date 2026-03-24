<?php
/**
 * Registro de asistencia diaria (prácticas preprofesionales / servicio comunitario).
 *
 * @var array<int, array{tipo:string,id:int,etiqueta:string}> $asistencia_items
 * @var string $asistencia_fecha Fecha Y-m-d (día a registrar)
 * @var bool $asistencia_mostrar_tarjeta Mostrar bloque resumen (páginas de documentos)
 * @var bool $asistencia_tiene_activa Hay al menos una vinculación en progreso del tipo de la página
 * @var string $asistencia_titulo_tarjeta Título de la tarjeta
 */
$items = $asistencia_items ?? [];
$fechaRef = $asistencia_fecha ?? date('Y-m-d');
$mostrarTarjeta = !empty($asistencia_mostrar_tarjeta);
$tieneActiva = !empty($asistencia_tiene_activa);
$tituloTarjeta = $asistencia_titulo_tarjeta ?? 'Asistencia del día';
$hayPendientes = $items !== [];
?>

<?php if ($mostrarTarjeta && $tieneActiva): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 border-start border-4 <?= $hayPendientes ? 'border-warning' : 'border-success' ?>">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-user-check me-2"></i><?= esc($tituloTarjeta) ?>
                    </h5>
                    <?php if ($hayPendientes): ?>
                        <p class="text-muted mb-3">
                            Debes registrar la asistencia de hoy (<strong><?= date('d/m/Y', strtotime($fechaRef)) ?></strong>) para continuar cumpliendo con vinculación.
                            También puedes hacerlo desde <a href="<?= site_url('estudiante/practicas') ?>">Prácticas</a>.
                        </p>
                        <button type="button" class="btn btn-warning text-dark" onclick="estudianteAbrirModalAsistencia()">
                            <i class="fas fa-clock me-1"></i>Registrar asistencia ahora
                        </button>
                    <?php else: ?>
                        <p class="text-success mb-0">
                            <i class="fas fa-check-circle me-1"></i> Ya registraste la asistencia de hoy para tus vinculaciones en progreso.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($hayPendientes): ?>
    <div class="modal fade" id="modalRegistroAsistenciaEstudiante" tabindex="-1" aria-labelledby="modalRegistroAsistenciaEstudianteLabel" aria-hidden="true"
        data-fecha-default="<?= esc($fechaRef) ?>">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalRegistroAsistenciaEstudianteLabel">
                        <i class="fas fa-clock me-2"></i>Registrar asistencia
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formRegistroAsistenciaEstudiante" novalidate>
                        <?= csrf_field() ?>
                        <input type="hidden" name="practica_id" id="asist_est_practica_id" value="">
                        <input type="hidden" name="tipo_practica" id="asist_est_tipo_practica" value="">

                        <?php if (count($items) > 1): ?>
                            <div class="mb-3">
                                <label class="form-label">Vinculación <span class="text-danger">*</span></label>
                                <select class="form-select" id="asist_est_select_vinculo" required>
                                    <?php foreach ($items as $it): ?>
                                        <option value="<?= esc($it['tipo']) ?>" data-id="<?= (int) $it['id'] ?>">
                                            <?= esc($it['etiqueta']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <?php $uno = $items[0]; ?>
                            <input type="hidden" id="asist_est_select_vinculo" value="<?= esc($uno['tipo']) ?>" data-id="<?= (int) $uno['id'] ?>">
                            <p class="small text-muted mb-3"><?= esc($uno['etiqueta']) ?></p>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="fecha_asistencia" id="asist_est_fecha" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora de entrada <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="hora_entrada" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora de salida <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="hora_salida" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Actividades del día <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="actividades_dia" rows="4" minlength="10" maxlength="300" required placeholder="Describe lo realizado (mín. 10 caracteres)"></textarea>
                            <div class="form-text"><span id="asist_est_count_act">0</span>/300</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="2" maxlength="200"></textarea>
                            <div class="form-text"><span id="asist_est_count_obs">0</span>/200</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-success" onclick="estudianteGuardarAsistencia()">
                        <i class="fas fa-save me-1"></i>Guardar asistencia
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
$tipoAviso = $documentos_aviso_tipo ?? 'preprofesional';
if ($tipoAviso === 'servicio') {
    $fraseValidacion = 'Una vez que tenga todos los documentos mencionados, diríjase al coordinador para validar su servicio comunitario. Una vez validado, deberá subir toda la información relacionada a la base de datos del instituto.';
} elseif ($tipoAviso === 'perfil') {
    $fraseValidacion = 'Una vez que tenga todos los documentos mencionados, diríjase al coordinador para validar sus prácticas preprofesionales o su servicio comunitario, según corresponda. Una vez validado, deberá subir la información a la base de datos del instituto.';
} else {
    $fraseValidacion = 'Una vez que tenga todos los documentos mencionados, diríjase al coordinador para validar sus prácticas laborales. Una vez validadas, deberá subir toda la información relacionada a la base de datos del instituto.';
}

if (!empty($documentos_aviso_integrado)) : ?>
<div class="documentos-aviso-integrado documentos-resumen-seccion px-3 px-md-4 py-3 border-top border-light">
    <h6 class="documentos-resumen-seccion-title mb-2">
        <i class="fas fa-info-circle text-primary" aria-hidden="true"></i>
        Importante
    </h6>
    <p class="small text-muted mb-2 mb-md-3">Revise estos puntos antes de subir cada PDF.</p>
    <ul class="list-unstyled small mb-0 documentos-aviso-integrado-lista">
        <li class="d-flex gap-2 mb-2"><i class="fas fa-check text-success flex-shrink-0 mt-1" style="font-size:0.7rem;" aria-hidden="true"></i><span>Es importante completar todos estos datos en el orden indicado.</span></li>
        <li class="d-flex gap-2 mb-2"><i class="fas fa-check text-success flex-shrink-0 mt-1" style="font-size:0.7rem;" aria-hidden="true"></i><span>Verifique que los documentos cuenten con firma original y sello de la institución.</span></li>
        <li class="d-flex gap-2 mb-2"><i class="fas fa-check text-success flex-shrink-0 mt-1" style="font-size:0.7rem;" aria-hidden="true"></i><span><?= esc($fraseValidacion) ?></span></li>
        <li class="d-flex gap-2 mb-0"><i class="fas fa-check text-success flex-shrink-0 mt-1" style="font-size:0.7rem;" aria-hidden="true"></i><span>El archivo digital no debe superar 10 MB.</span></li>
    </ul>
</div>
<?php else:

$avisoCompacto = !empty($documentos_aviso_compact);
$claseOuter = $avisoCompacto ? 'mb-3' : 'row mb-4';
$claseWrap = 'documentos-aviso-importante-wrap card border-0' . ($avisoCompacto ? ' documentos-aviso--compact' : ' documentos-aviso--pagina');
$colAside = $avisoCompacto ? 'col-12' : 'col-12 col-lg-3';
$colMain = $avisoCompacto ? 'col-12' : 'col-12 col-lg-9';
$colStep = $avisoCompacto ? 'col-12' : 'col-md-6';
?>
<div class="<?= esc($claseOuter, 'attr') ?>">
    <?php if (!$avisoCompacto): ?><div class="col-12"><?php endif; ?>
        <div class="<?= esc($claseWrap, 'attr') ?>">
            <div class="row g-0 align-items-stretch">
                <div class="<?= esc($colAside, 'attr') ?> documentos-aviso-aside">
                    <div class="documentos-aviso-aside-inner">
                        <div class="documentos-aviso-aside-icon" aria-hidden="true">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h2 class="documentos-aviso-aside-title">Importante</h2>
                        <p class="documentos-aviso-aside-lead mb-0">Antes de subir tu documentación, cumple con lo siguiente.</p>
                    </div>
                </div>
                <div class="<?= esc($colMain, 'attr') ?> documentos-aviso-main">
                    <p class="documentos-aviso-main-kicker"><span class="documentos-aviso-kicker-dot"></span>Checklist</p>
                    <div class="row g-2 g-md-3">
                        <div class="<?= esc($colStep, 'attr') ?>">
                            <div class="documentos-aviso-step">
                                <span class="documentos-aviso-step-num" aria-hidden="true">1</span>
                                <div class="documentos-aviso-step-body">
                                    <strong class="documentos-aviso-step-label">Orden</strong>
                                    <p class="documentos-aviso-step-text mb-0">Es importante completar todos estos datos en el orden indicado.</p>
                                </div>
                            </div>
                        </div>
                        <div class="<?= esc($colStep, 'attr') ?>">
                            <div class="documentos-aviso-step">
                                <span class="documentos-aviso-step-num" aria-hidden="true">2</span>
                                <div class="documentos-aviso-step-body">
                                    <strong class="documentos-aviso-step-label">Originalidad</strong>
                                    <p class="documentos-aviso-step-text mb-0">Verifique que los documentos cuenten con firma original y sello de la institución.</p>
                                </div>
                            </div>
                        </div>
                        <div class="<?= esc($colStep, 'attr') ?>">
                            <div class="documentos-aviso-step">
                                <span class="documentos-aviso-step-num" aria-hidden="true">3</span>
                                <div class="documentos-aviso-step-body">
                                    <strong class="documentos-aviso-step-label">Coordinación</strong>
                                    <p class="documentos-aviso-step-text mb-0"><?= esc($fraseValidacion) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="<?= esc($colStep, 'attr') ?>">
                            <div class="documentos-aviso-step">
                                <span class="documentos-aviso-step-num" aria-hidden="true">4</span>
                                <div class="documentos-aviso-step-body">
                                    <strong class="documentos-aviso-step-label">Archivo PDF</strong>
                                    <p class="documentos-aviso-step-text mb-0">El archivo digital no debe superar 10 MB.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php if (!$avisoCompacto): ?></div><?php endif; ?>
</div>
<?php endif; ?>

<?php
$tipoAviso = $documentos_aviso_tipo ?? 'preprofesional';
if ($tipoAviso === 'servicio') {
    $fraseValidacion = 'Una vez que tenga todos los documentos mencionados, diríjase al coordinador para validar su servicio comunitario. Una vez validado, deberá subir toda la información relacionada a la base de datos del instituto.';
} elseif ($tipoAviso === 'perfil') {
    $fraseValidacion = 'Una vez que tenga todos los documentos mencionados, diríjase al coordinador para validar sus prácticas preprofesionales o su servicio comunitario, según corresponda. Una vez validado, deberá subir la información a la base de datos del instituto.';
} else {
    $fraseValidacion = 'Una vez que tenga todos los documentos mencionados, diríjase al coordinador para validar sus prácticas laborales. Una vez validadas, deberá subir toda la información relacionada a la base de datos del instituto.';
}
$avisoIntegrado = !empty($documentos_aviso_integrado);
$avisoCompacto = !empty($documentos_aviso_compact);
$claseContenedor = $avisoIntegrado ? 'documentos-aviso-integrado documentos-resumen-seccion px-3 px-md-4 py-3 border-top border-light' : 'documentos-aviso-importante-wrap card border-0 p-3';
$claseCols = $avisoCompacto ? 'col-12' : 'col-12 col-md-6';
?>
<div class="<?= esc($claseContenedor, 'attr') ?>">
    <h6 class="documentos-resumen-seccion-title mb-2">
        <i class="fas fa-info-circle text-primary" aria-hidden="true"></i>
        Importante
    </h6>
    <p class="small text-muted mb-2 mb-md-3">Revise estos puntos antes de subir cada PDF.</p>
    <div class="row g-2 g-md-3 documentos-aviso-cards">
        <div class="<?= esc($claseCols, 'attr') ?>">
            <div class="documentos-aviso-card h-100">
                <span class="documentos-aviso-card-num">1</span>
                <div>
                    <strong class="documentos-aviso-card-title">Orden</strong>
                    <p class="documentos-aviso-card-text mb-0">Es importante completar todos estos datos en el orden indicado.</p>
                </div>
            </div>
        </div>
        <div class="<?= esc($claseCols, 'attr') ?>">
            <div class="documentos-aviso-card h-100">
                <span class="documentos-aviso-card-num">2</span>
                <div>
                    <strong class="documentos-aviso-card-title">Originalidad</strong>
                    <p class="documentos-aviso-card-text mb-0">Verifique que los documentos cuenten con firma original y sello de la institución.</p>
                </div>
            </div>
        </div>
        <div class="<?= esc($claseCols, 'attr') ?>">
            <div class="documentos-aviso-card h-100">
                <span class="documentos-aviso-card-num">3</span>
                <div>
                    <strong class="documentos-aviso-card-title">Coordinación</strong>
                    <p class="documentos-aviso-card-text mb-0"><?= esc($fraseValidacion) ?></p>
                </div>
            </div>
        </div>
        <div class="<?= esc($claseCols, 'attr') ?>">
            <div class="documentos-aviso-card h-100">
                <span class="documentos-aviso-card-num">4</span>
                <div>
                    <strong class="documentos-aviso-card-title">Archivo PDF</strong>
                    <p class="documentos-aviso-card-text mb-0">El archivo digital no debe superar 10 MB.</p>
                </div>
            </div>
        </div>
    </div>
</div>

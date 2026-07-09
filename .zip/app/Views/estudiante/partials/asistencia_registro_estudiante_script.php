<?php
/** @var bool $asistencia_modal_automatico */
$autoModal = !empty($asistencia_modal_automatico);
$urlRegistrar = site_url('estudiante/practicas/registrar-asistencia');
?>
<script>
(function() {
    var urlRegistrar = <?= json_encode($urlRegistrar) ?>;

    function syncAsistenciaVinculoFromDom() {
        var sel = document.getElementById('asist_est_select_vinculo');
        var idIn = document.getElementById('asist_est_practica_id');
        var tipoIn = document.getElementById('asist_est_tipo_practica');
        if (!sel || !idIn || !tipoIn) return;
        var opt = sel.tagName === 'SELECT' ? sel.options[sel.selectedIndex] : sel;
        var tipo = sel.tagName === 'SELECT' ? opt.value : sel.value;
        var id = opt.getAttribute('data-id');
        idIn.value = id || '';
        tipoIn.value = tipo || '';
    }

    window.estudianteAbrirModalAsistencia = function() {
        var el = document.getElementById('modalRegistroAsistenciaEstudiante');
        if (!el) return;
        var form = document.getElementById('formRegistroAsistenciaEstudiante');
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
            var fd = el.getAttribute('data-fecha-default');
            var fi = document.getElementById('asist_est_fecha');
            if (fi && fd) fi.value = fd;
            syncAsistenciaVinculoFromDom();
        }
        var c1 = document.getElementById('asist_est_count_act');
        var c2 = document.getElementById('asist_est_count_obs');
        if (c1) c1.textContent = '0';
        if (c2) c2.textContent = '0';
        if (typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(el).show();
        }
    };

    window.estudianteGuardarAsistencia = function() {
        var form = document.getElementById('formRegistroAsistenciaEstudiante');
        if (!form) return;
        syncAsistenciaVinculoFromDom();
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        var he = form.querySelector('input[name="hora_entrada"]').value;
        var hs = form.querySelector('input[name="hora_salida"]').value;
        if (he && hs && hs <= he) {
            alert('La hora de salida debe ser posterior a la de entrada.');
            return;
        }
        var fd = new FormData(form);
        var btn = document.querySelector('#modalRegistroAsistenciaEstudiante .btn-success');
        var txt = btn ? btn.innerHTML : '';
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
        }
        fetch(urlRegistrar, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(r) {
                return r.json();
            })
            .then(function(data) {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'No se pudo registrar la asistencia');
                }
            })
            .catch(function() {
                alert('Error de conexión');
            })
            .finally(function() {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = txt;
                }
            });
    };

    document.addEventListener('DOMContentLoaded', function() {
        var modalAsist = document.getElementById('modalRegistroAsistenciaEstudiante');
        if (modalAsist && modalAsist.parentNode !== document.body) {
            document.body.appendChild(modalAsist);
        }

        var sel = document.getElementById('asist_est_select_vinculo');
        if (sel && sel.tagName === 'SELECT') {
            sel.addEventListener('change', syncAsistenciaVinculoFromDom);
        }
        syncAsistenciaVinculoFromDom();
        var act = document.querySelector('#formRegistroAsistenciaEstudiante textarea[name="actividades_dia"]');
        var obs = document.querySelector('#formRegistroAsistenciaEstudiante textarea[name="observaciones"]');
        if (act) act.addEventListener('input', function() {
            var c = document.getElementById('asist_est_count_act');
            if (c) c.textContent = String(this.value.length);
        });
        if (obs) obs.addEventListener('input', function() {
            var c = document.getElementById('asist_est_count_obs');
            if (c) c.textContent = String(this.value.length);
        });

        <?php if ($autoModal): ?>
        var el = document.getElementById('modalRegistroAsistenciaEstudiante');
        if (el) {
            var form = document.getElementById('formRegistroAsistenciaEstudiante');
            if (form) {
                var fd = el.getAttribute('data-fecha-default');
                var fi = document.getElementById('asist_est_fecha');
                if (fi && fd) fi.value = fd;
                syncAsistenciaVinculoFromDom();
            }
            if (typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(el, {
                    backdrop: 'static',
                    keyboard: false
                }).show();
            }
        }
        <?php endif; ?>
    });
})();
</script>

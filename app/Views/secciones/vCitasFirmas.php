<?php
$nombresDias = [
    '2026-08-24' => 'Lunes 24 de agosto',
    '2026-08-25' => 'Martes 25 de agosto',
    '2026-08-26' => 'Miércoles 26 de agosto',
    '2026-08-27' => 'Jueves 27 de agosto',
    '2026-08-28' => 'Viernes 28 de agosto',
];
?>

<style>
    .citas-header {
        background: linear-gradient(135deg, #1761fd 0%, #234a91 100%);
        border-radius: .65rem;
        color: #fff;
        padding: 1.5rem;
        box-shadow: 0 8px 22px rgba(23, 97, 253, .18);
    }
    .citas-day { border: 1px solid #e4e9f1; border-radius: .55rem; overflow: hidden; height: 100%; }
    .citas-day-title { background: #f3f6fb; color: #263b5e; padding: .8rem; font-weight: 700; text-align: center; }
    .citas-slots { padding: .85rem; }
    .cita-slot { width: 100%; margin-bottom: .55rem; display: flex; align-items: center; justify-content: space-between; }
    .cita-slot:last-child { margin-bottom: 0; }
    .cita-slot.btn-light { border: 1px solid #dce3ee; color: #33415c; background: #fff; }
    .cita-slot.btn-light:hover { border-color: #1761fd; color: #1761fd; background: #eef4ff; }
    .cita-slot-ocupada { cursor: not-allowed; opacity: .8; }
    .citas-legend span { display: inline-flex; align-items: center; margin-right: 1rem; margin-bottom: .35rem; }
    .citas-legend i { width: 10px; height: 10px; border-radius: 50%; margin-right: .35rem; }
    @media (max-width: 767.98px) { .citas-header { padding: 1.1rem; } }
</style>

<div class="page-content-tab">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Registro de citas para firmas</h4>
                </div>
            </div>
        </div>

        <div class="citas-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="text-white mt-0 mb-2"><i class="far fa-calendar-check mr-2"></i>Agenda tu cita</h3>
                    <p class="mb-0">Selecciona un horario disponible del lunes 24 al viernes 28 de agosto de 2026.</p>
                </div>
                <div class="col-md-4 text-md-right mt-3 mt-md-0">
                    <div class="font-weight-bold"><i class="far fa-clock mr-1"></i>10:00 a 15:00 horas</div>
                    <small>Únicamente días hábiles</small>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div>
                        <h4 class="mt-0 mb-1">Horarios disponibles</h4>
                        <p class="text-muted mb-0">Citas cada 30 minutos; cada horario admite solamente un registro.</p>
                    </div>
                    <div class="citas-legend text-muted mt-2 mt-md-0">
                        <span><i class="bg-primary"></i>Disponible</span>
                        <span><i class="bg-danger"></i>Ocupado</span>
                        <span><i class="bg-success"></i>Tu cita</span>
                    </div>
                </div>

                <div class="row">
                    <?php foreach ($fechas_permitidas as $fecha): ?>
                        <div class="col-xl col-md-6 mb-3">
                            <section class="citas-day">
                                <div class="citas-day-title"><?= esc($nombresDias[$fecha] ?? $fecha) ?></div>
                                <div class="citas-slots">
                                    <?php foreach ($horas_permitidas as $hora): ?>
                                        <?php
                                        $estado = $citas[$fecha][$hora] ?? null;
                                        $ocupada = !empty($estado['ocupada']);
                                        $propia = !empty($estado['propia']);
                                        $horaTexto = date('H:i', strtotime($hora));
                                        ?>
                                        <?php if ($ocupada): ?>
                                            <button type="button" class="btn <?= $propia ? 'btn-success' : 'btn-outline-danger' ?> cita-slot cita-slot-ocupada" disabled>
                                                <strong><?= esc($horaTexto) ?></strong>
                                                <span><?= $propia ? 'Tu cita' : 'Ocupado' ?></span>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-light cita-slot js-reservar-cita" data-fecha="<?= esc($fecha, 'attr') ?>" data-hora="<?= esc($hora, 'attr') ?>" data-etiqueta="<?= esc(($nombresDias[$fecha] ?? $fecha) . ' a las ' . $horaTexto, 'attr') ?>">
                                                <strong><?= esc($horaTexto) ?></strong>
                                                <span class="text-primary">Elegir</span>
                                            </button>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </section>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Esta plantilla carga los complementos de jQuery en el pie, pero no carga el núcleo. -->
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script>
(function () {
    'use strict';

    $('.js-reservar-cita').on('click', function () {
        const button = this;
        const label = button.dataset.etiqueta;

        Swal.fire({
            icon: 'question',
            title: 'Confirmar cita',
            text: '¿Deseas registrar tu cita el ' + label + '?',
            showCancelButton: true,
            confirmButtonText: 'Sí, registrar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function (result) {
            if (!result.isConfirmed) return;

            button.disabled = true;
            $.ajax({
                url: "<?= base_url('index.php/Agregar/guardarCitaFirmas') ?>",
                type: 'POST',
                dataType: 'json',
                data: { fecha: button.dataset.fecha, hora: button.dataset.hora },
                success: function (response) {
                    if (response.error) {
                        button.disabled = false;
                        Swal.fire('Horario no disponible', response.respuesta, 'warning').then(function () {
                            window.location.reload();
                        });
                        return;
                    }

                    Swal.fire('Cita registrada', response.respuesta, 'success').then(function () {
                        window.location.reload();
                    });
                },
                error: function () {
                    button.disabled = false;
                    Swal.fire('Error', 'No fue posible registrar la cita. Inténtalo nuevamente.', 'error');
                }
            });
        });
    });
})();
</script>

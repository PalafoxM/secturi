<?php $session = \Config\Services::session(); ?>
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">SUSI</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Pagos</a></li>
                                <li class="breadcrumb-item active">Pagos FIC</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Lista de Pagos FIC</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <a href="<?= base_url('index.php/Inicio/generarFormatoFIC') ?>" class="btn btn-primary">
                                    <i class="mdi mdi-plus"></i> Nuevo Pago FIC
                                </a>
                            </div>

                            <div class="table-responsive">
                                <table id="datatablePagosFIC" class="table table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">CONTRATO/CONVENIO</th>
                                            <th class="text-center">FOLIO</th>
                                            <th class="text-center">RESERVA</th>
                                            <th class="text-center">ESTABLECIMIENTO</th>
                                            <th class="text-center">COMPROBANTE</th>
                                            <th class="text-center">PARTIDA</th>
                                            <th class="text-center">IMPORTE</th>
                                            <?php if (in_array((int) $session->get('id_perfil'), [1, 2], true)): ?>
                                                <th class="text-center">RESPONSABLE</th>
                                            <?php endif; ?>
                                            <th class="text-center text-nowrap">ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (($pagos_fic ?? []) as $pago): ?>
                                            <tr>
                                                <td class="text-center"><?= esc($pago->no_contrato ?: 'NO APLICA') ?></td>
                                                <td class="text-center"><?= esc($pago->folio ?? '') ?></td>
                                                <td class="text-center"><?= esc($pago->reserva ?? '') ?></td>
                                                <td><?= esc($pago->nombre_establecimiento ?? '') ?></td>
                                                <td class="text-center">
                                                    <?= esc(implode(', ', array_map(static function ($concepto) {
                                                        return $concepto->comprobante;
                                                    }, $pago->conceptos_fic ?? []))) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= esc(implode(', ', array_unique(array_map(static function ($concepto) {
                                                        return $concepto->partida;
                                                    }, $pago->conceptos_fic ?? [])))) ?>
                                                </td>
                                                <td class="text-right">$<?= number_format((float) ($pago->importe_total_fic ?? 0), 2) ?></td>
                                                <?php if (in_array((int) $session->get('id_perfil'), [1, 2], true)): ?>
                                                    <td><?= esc($pago->nombre_usuario ?? '') ?></td>
                                                <?php endif; ?>
                                                <td class="text-center text-nowrap">
                                                    <a class="btn btn-outline-secondary btn-round"
                                                       href="<?= base_url('index.php/Inicio/pdfPagoFIC?id=' . $pago->id_pago_fic) ?>"
                                                       target="_blank" title="Formato de Pago FIC">
                                                        <i class="mdi mdi-file-pdf font-18"></i>
                                                    </a>
                                                    <a class="btn btn-outline-primary btn-round"
                                                       href="<?= base_url('index.php/Inicio/pdfLiberacionFIC?id=' . $pago->id_pago_fic) ?>"
                                                       target="_blank" title="Liberación FIC">
                                                        <i class="mdi mdi-file-document font-18"></i>
                                                    </a>
                                                    <a class="btn btn-outline-info btn-round"
                                                       href="<?= base_url('index.php/Inicio/pdfEncabezadoFIC?id=' . $pago->id_pago_fic) ?>"
                                                       target="_blank" title="Encabezado FIC">
                                                        <i class="mdi mdi-file-document-box-multiple font-18"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-success btn-round"
                                                       onclick="abrirPeriodosPdfFIC(<?= (int) $pago->id_pago_fic ?>)"
                                                       title="PDF unificado FIC">
                                                        <i class="mdi mdi-file-multiple font-18"></i>
                                                    </button>
                                                    <a class="btn btn-outline-warning btn-round"
                                                       href="<?= base_url('index.php/Inicio/descargarAnexo01FIC?id=' . $pago->id_pago_fic) ?>"
                                                       title="ANEXO 01">
                                                        <i class="mdi mdi-file-excel font-18"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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

<div class="modal fade" id="modalPeriodoPdfFIC" tabindex="-1" role="dialog" aria-labelledby="modalPeriodoPdfFICTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPeriodoPdfFICTitulo">
                    <i class="mdi mdi-calendar-range mr-1"></i> Seleccionar periodo del FIC
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">El periodo seleccionado aparecerá en la Liberación y en todos los Encabezados del PDF unificado.</p>
                <div class="custom-control custom-radio border rounded p-3 mb-3" style="padding-left: 2.75rem !important;">
                    <input type="radio" id="periodoFic1" name="periodo_pdf_fic" class="custom-control-input" value="1">
                    <label class="custom-control-label font-weight-bold" for="periodoFic1">
                        10 al 26 de octubre de 2026
                    </label>
                </div>
                <div class="custom-control custom-radio border rounded p-3" style="padding-left: 2.75rem !important;">
                    <input type="radio" id="periodoFic2" name="periodo_pdf_fic" class="custom-control-input" value="2">
                    <label class="custom-control-label font-weight-bold" for="periodoFic2">
                        17 de septiembre al 26 de octubre de 2026
                    </label>
                </div>
                <div id="errorPeriodoPdfFIC" class="text-danger small mt-3 d-none">
                    Selecciona uno de los dos periodos para continuar.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="generarPdfUnificadoFIC()">
                    <i class="mdi mdi-file-multiple mr-1"></i> Generar PDF unificado
                </button>
            </div>
        </div>
    </div>
</div>

<link href="<?= base_url('plugins/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
var idPagoPeriodoFIC = null;

function abrirPeriodosPdfFIC(idPagoFIC) {
    idPagoPeriodoFIC = idPagoFIC;
    $('input[name="periodo_pdf_fic"]').prop('checked', false);
    $('#errorPeriodoPdfFIC').addClass('d-none');
    $('#modalPeriodoPdfFIC').modal('show');
}

function generarPdfUnificadoFIC() {
    var periodo = $('input[name="periodo_pdf_fic"]:checked').val();

    if (!periodo || !idPagoPeriodoFIC) {
        $('#errorPeriodoPdfFIC').removeClass('d-none');
        return;
    }

    var url = <?= json_encode(base_url('index.php/Inicio/pdfExpedienteFIC'), JSON_UNESCAPED_SLASHES) ?>
        + '?id=' + encodeURIComponent(idPagoPeriodoFIC)
        + '&periodo=' + encodeURIComponent(periodo);

    $('#modalPeriodoPdfFIC').modal('hide');
    window.open(url, '_blank', 'noopener');
}

$(function () {
    $('#datatablePagosFIC').DataTable({
        language: {
            search: 'Buscar:',
            lengthMenu: 'Mostrar _MENU_ registros',
            info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
            infoEmpty: 'No hay registros disponibles',
            zeroRecords: 'No se encontraron pagos FIC',
            paginate: { previous: 'Anterior', next: 'Siguiente' }
        },
        order: [[1, 'desc']],
        paging: true,
        searching: true,
        autoWidth: false
    });
});
</script>

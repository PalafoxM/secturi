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
                                                <td class="text-center"><?= esc($pago->comprobante ?? '') ?></td>
                                                <td class="text-center"><?= esc($pago->partida ?? '') ?></td>
                                                <td class="text-right">$<?= number_format((float) str_replace(',', '', (string) ($pago->importe ?? 0)), 2) ?></td>
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

<link href="<?= base_url('plugins/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css">
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script>
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

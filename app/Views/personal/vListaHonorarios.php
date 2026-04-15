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
                                <li class="breadcrumb-item active">Honorarios</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Listado de Solicitudes de Honorarios</h4>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-12 text-right">
                    <a href="<?= base_url('index.php/Principal/SolicitudHonorarios') ?>" class="btn btn-primary">
                        Nueva Solicitud
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="datatable-honorarios" class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Prestador</th>
                                            <th>RFC</th>
                                            <th>Responsable</th>
                                            <th>Vigencia</th>
                                            <th>Monto</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($solicitudes)): ?>
                                            <?php foreach ($solicitudes as $solicitud): ?>
                                                <tr>
                                                    <td><?= $solicitud->id_solicitud_honorario ?></td>
                                                    <td><?= esc($solicitud->nombre_prestador ?? '') ?></td>
                                                    <td><?= esc($solicitud->rfc_prestador ?? '') ?></td>
                                                    <td><?= esc($solicitud->responsable_proyecto_nombre ?? '') ?></td>
                                                    <td>
                                                        <?= !empty($solicitud->vigencia_inicio) ? date('d/m/Y', strtotime($solicitud->vigencia_inicio)) : '' ?>
                                                        -
                                                        <?= !empty($solicitud->vigencia_fin) ? date('d/m/Y', strtotime($solicitud->vigencia_fin)) : '' ?>
                                                    </td>
                                                    <td>$<?= number_format((float) ($solicitud->monto_total_contrato ?? 0), 2, '.', ',') ?></td>
                                                    <td class="text-center">
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <a href="<?= base_url('index.php/Principal/editarSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" class="btn btn-sm btn-warning" title="Editar">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('index.php/Principal/pdfSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" target="_blank" class="btn btn-sm btn-outline-danger" title="PDF">
                                                            PDF
                                                        </a>
                                                        <?php if ($session->id_perfil != 7): ?>
                                                            <button class="btn btn-sm btn-secondary" title="Adjuntar Archivos" onclick="abrirModalArchivos(<?= $solicitud->id_solicitud_honorario ?>)">
                                                                <i class="fas fa-paperclip"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <a href="<?= base_url('index.php/Principal/verArchivosSolicitudHonorarios/' . $solicitud->id_solicitud_honorario) ?>" class="btn btn-sm btn-success" title="Ver Archivos">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No hay solicitudes de honorarios registradas.</td>
                                            </tr>
                                        <?php endif; ?>
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

<div class="modal fade" id="modalSeleccionArchivosHonorarios" tabindex="-1" role="dialog" aria-labelledby="modalLabelHonorarios" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelHonorarios">Seleccion de Documentos a Subir</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionArchivosHonorarios" action="<?= base_url('index.php/Principal/subirArchivosSolicitudHonorarios') ?>" method="POST">
                    <input type="hidden" name="id_solicitud" id="modal_id_solicitud_honorarios">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-light">
                            <tr><th style="width: 5%;">Num.</th><th>DOCUMENTO</th><th style="width: 10%; text-align: center;">SI</th></tr>
                        </thead>
                        <tbody>
                            <?php $documentos = [1 => 'Oficio de solicitud', 2 => 'Formato de solicitud de Contrato', 3 => 'Validacion Proceso Ingreso de SFIA', 4 => 'RFC / Cedula de Identificacion Fiscal', 5 => 'Identificacion Oficial', 6 => 'Autorizacion de Tratamiento de Datos Personales en Posesion de Sujetos Obligados', 7 => 'Comprobante de Domicilio']; ?>
                            <?php foreach ($documentos as $key => $doc): ?>
                                <tr>
                                    <td><?= $key ?></td>
                                    <td><?= $doc ?></td>
                                    <td class="text-center">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input check-si-honorarios" id="si_honorarios_<?= $key ?>" name="documentos[<?= $key ?>]" value="<?= $doc ?>">
                                            <label class="custom-control-label" for="si_honorarios_<?= $key ?>"></label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="enviarFormularioArchivosHonorarios()">Continuar</button>
            </div>
        </div>
    </div>
</div>

<link href="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>plugins/datatables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function(){
    $('#datatable-honorarios').DataTable({
        language:{ url:'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json' }
    });
});
function abrirModalArchivos(id){
    $('#modal_id_solicitud_honorarios').val(id);
    $('.check-si-honorarios').prop('checked', false);
    $('#modalSeleccionArchivosHonorarios').modal('show');
}
function enviarFormularioArchivosHonorarios(){
    $('#formSeleccionArchivosHonorarios').submit();
}
</script>

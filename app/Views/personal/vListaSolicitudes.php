<?php $session = \Config\Services::session(); ?>
<div class="page-wrapper">
    <div class="page-content-tab">
        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Susi</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Solicitudes</a></li>
                                <li class="breadcrumb-item active">Lista de Solicitudes</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Solicitudes GRC</h4>
                    </div>
                </div>
            </div>
            <!-- end page title end breadcrumb -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="mt-0 header-title">Listado de Solicitudes</h4>
                            <p class="text-muted mb-3">
                                <a href="<?= base_url('index.php/Principal/listadoGrc') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus mr-2"></i>Nueva Solicitud
                                </a>
                            </p>

                            <div class="table-responsive">
                                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Cheque a Favor</th>
                                            <th>Cantidad</th>
                                            <th>Evento</th>
                                            <th>Lugar</th>
                                            <th>Fechas</th>
                                            <th>Clave</th>
                                            <th>Responsable</th>
                                            <th>Fecha Registro</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($datos) && !empty($datos)): ?>
                                            <?php foreach ($datos as $row): ?>
                                                <tr>
                                                    <td><?= $row->id_solicitud_grc ?></td>
                                                    <td><?= $row->cheque_favor_nombre ?></td>
                                                    <td><?= number_format($row->cantidad, 2) ?></td>
                                                    <td><?= $row->nombre_evento ?></td>
                                                    <td><?= $row->lugar ?></td>
                                                    <td><?= date('d/m/Y', strtotime($row->fecha_inicio)) ?> - <?= date('d/m/Y', strtotime($row->fecha_fin)) ?></td>
                                                    <td><?= $row->clave ?></td>
                                                    <td><?= $row->nombre_completo ?></td>
                                                    <td><?= date('d/m/Y', strtotime($row->fec_reg)) ?></td>
                                                    <td>
                                                        <a href="<?= base_url('index.php/Principal/editarSolicitudGrc/' . $row->id_solicitud_grc) ?>" class="btn btn-sm btn-info" title="Editar"><i class="fas fa-edit"></i></a>
                                                        <a href="<?= base_url('index.php/Principal/ArchivoGRC/' . $row->id_solicitud_grc) ?>" class="btn btn-sm btn-secondary" title="Imprimir" target="_blank"><i class="fas fa-print"></i></a>
                                                        <a href="javascript:void(0);" class="btn btn-sm btn-danger" title="Eliminar" onclick="ini.inicio.eliminarSolicitud(<?= $row->id_solicitud_grc ?>)"><i class="fas fa-trash"></i></a>
                                                      
                                                           
                                                             <!--    <a href="javascript:void(0);" class="btn btn-sm btn-success" title="Validar" onclick="ini.inicio.validarSolicitudGrc(<?= $row->id_solicitud_grc ?>)"><i class="fas fa-check"></i></a>-->
                                                            
                                                                <a href="<?= base_url('index.php/Principal/comprobarGastos/' . $row->id_solicitud_grc) ?>" class="btn btn-sm btn-warning" title="Comprobar Gastos"><i class="fas fa-file-invoice-dollar"></i></a>
                                                            <?php if ($row->id_estatus == 3): ?>
                                                                <a href="<?= base_url('index.php/Principal/ArchivoComprobacion/' . $row->id_solicitud_grc) ?>" class="btn btn-sm btn-primary" title="Ver Comprobación" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                                            <?php endif; ?>
                                                    
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div><!-- container -->
    </div>
</div>


<!--Form Wizard-->
<link rel="stylesheet" href="<?= base_url() ?>plugins/jquery-steps/jquery.steps.css">



<!-- jQuery  -->
<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/js/metismenu.min.js"></script>
<script src="<?= base_url() ?>assets/js/waves.js"></script>
<script src="<?= base_url() ?>assets/js/feather.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.slimscroll.min.js"></script>


<script src="<?= base_url() ?>plugins/jquery-steps/jquery.steps.min.js"></script>
<script src="<?= base_url() ?>assets/pages/jquery.form-wizard.init.js"></script>



<!-- Plugins js -->
<script src="<?= base_url() ?>plugins/moment/moment.js"></script>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
</script>

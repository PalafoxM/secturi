<?php $session = \Config\Services::session(); ?>
<div class="page-content-tab">
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Inicio</a></li>
                            <li class="breadcrumb-item active">Tipo de Operación</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Relación de Operaciones</h4>
                </div>
            </div>
        </div>
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="button-items mb-3">
                            <button type="button" class="btn btn-primary" onclick="ini.inicio.tipoOperacion.nuevo()">
                                <i class="fas fa-plus"></i> Nueva Operación
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaOperaciones" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Detalles</th>
                                        <th>Importe/Periodo</th>
                                        <th>Estado/Comprobante</th>
                                        <th>Fecha Reg</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($operaciones as $op): 
                                        $tipo = '';
                                        $detalles = '';
                                        $extra = '';
                                        $estado = '';
                                        
                                        if($op->id_tipo_operacion == 1){ 
                                            $tipo = '<span class="badge badge-success">Depósito</span>';
                                            // Find nombre deposito in $cat_deposito
                                            foreach($cat_deposito as $dep){ if($dep->id_deposito == $op->id_deposito) $detalles = $dep->dsc_cuenta; }
                                            $extra = '$' . number_format($op->importe, 2);
                                            if($op->comprobante) {
                                                $estado = '<a href="'.base_url($op->comprobante).'" target="_blank" class="btn btn-xs btn-info"><i class="fas fa-file-alt"></i> Ver Comp.</a>';
                                            }
                                        } elseif($op->id_tipo_operacion == 2){
                                            $tipo = '<span class="badge badge-warning">Traspaso</span>';
                                             foreach($cat_deposito as $dep){ if($dep->id_deposito == $op->id_deposito) $detalles = "Cuenta: " . $dep->dsc_cuenta; }
                                            $extra = '$' . number_format($op->importe, 2);
                                        } elseif($op->id_tipo_operacion == 3){
                                            $tipo = '<span class="badge badge-info">Consulta Corte</span>';
                                            $detalles = $op->estado_cuenta;
                                            $extra = $op->periodo;
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $tipo ?></td>
                                        <td><?= $detalles ?></td>
                                        <td><?= $extra ?></td>
                                        <td><?= $estado ?></td>
                                        <td><?= date('d/m/Y', strtotime($op->fec_reg)) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" onclick="ini.inicio.tipoOperacion.editar(<?= $op->id_operacion ?>)"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="ini.inicio.tipoOperacion.eliminar(<?= $op->id_operacion ?>)"><i class="fas fa-trash-alt"></i></button>
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

        <!-- Modal -->
        <div class="modal fade" id="modalTipoOperacion" tabindex="-1" role="dialog" aria-labelledby="modalTipoOperacionLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTipoOperacionLabel">Operación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formTipoOperacion" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="id_operacion" id="id_operacion">

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">TIPO DE OPERACIÓN</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="id_tipo_operacion" name="id_tipo_operacion" onchange="ini.inicio.tipoOperacion.cambioTipo(this.value)" required>
                                        <option value="">Seleccione...</option>
                                        <option value="1">Depósito</option>
                                        <option value="2">Traspaso</option>
                                        <option value="3">Consulta Corte</option>
                                    </select>
                                </div>
                            </div>

                            <hr>

                            <!-- DEPOSITO -->
                            <div id="div_deposito" class="seccion-op" style="display:none;">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Abono a tarjeta:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="id_deposito" id="id_deposito">
                                            <option value="">Seleccione Cuenta/Depósito...</option>
                                            <?php foreach ($cat_deposito as $d): ?>
                                                <option value="<?= $d->id_deposito ?>"><?= $d->nombre_completo.'-'. $d->dsc_cuenta ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Importe $</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" name="importe2" id="importe_deposito">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Comprobante (PDF/Img)</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="comprobante" id="comprobante_deposito" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted" id="link_comprobante"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- TRASPASO -->
                            <div id="div_traspaso" class="seccion-op" style="display:none;">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Traspaso, Cuenta Origen:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="cuenta_traspaso" id="cuenta_traspaso">
                                            <option value="">Seleccione Cuenta...</option>
                                            <?php foreach ($cat_deposito as $d): ?>
                                                <option value="<?= $d->id_deposito ?>"><?= $d->dsc_cuenta ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Traspaso, Cuenta Destino:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="cuenta_traspaso" id="cuenta_traspaso">
                                            <option value="">Seleccione Cuenta...</option>
                                            <?php foreach ($cat_deposito as $d): ?>
                                                <option value="<?= $d->id_deposito ?>"><?= $d->dsc_cuenta ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Importe $</label>
                                    <div class="col-sm-9">
                                        <input type="number" step="0.01" class="form-control" name="importe" id="importe_traspaso">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Justificación</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="justificaciones" id="justificaciones" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                             <!-- CONSULTA CORTE -->
                             <div id="div_corte" class="seccion-op" style="display:none;">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Consulta Corte (Estado):</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="estado_cuenta" id="estado_cuenta">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Finalizado">Finalizado</option>
                                            <option value="En Revisión">En Revisión</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Periodo</label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="periodo" id="periodo">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="ini.inicio.tipoOperacion.guardar()">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- container -->
</div>

<link rel="stylesheet" href="<?= base_url() ?>plugins/jquery-steps/jquery.steps.css">

<!-- App css -->
<link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/jquery-ui.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />

<!-- Plugins css -->
<link href="<?= base_url() ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<link href="<?= base_url() ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?= base_url() ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet"
    type="text/css" />
<link href="<?= base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
<link href="<?= base_url() ?>plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />



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
<script src="<?= base_url() ?>plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>plugins/select2/select2.min.js"></script>
<script src="<?= base_url() ?>plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="<?= base_url() ?>plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script src="<?= base_url() ?>plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
<script src="<?= base_url() ?>plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>

<script src="<?= base_url() ?>assets/pages/jquery.forms-advanced.js"></script>


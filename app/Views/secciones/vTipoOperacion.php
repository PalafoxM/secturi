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
                            <button type="button" class="btn btn-primary" onclick="nuevaOperacionWrapper()">
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
                                        <th>SEGUIMIENTO</th>
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

                                        // SEGUIMIENTO LOGIC
                                        $seguimientoHtml = '';
                                        $seguimientoVal = isset($op->seguimiento) ? $op->seguimiento : '';
                                        $archivoSeg = isset($op->seguimiento_formato) ? $op->seguimiento_formato : '';
                                        
                                        if(in_array($session->get('id_perfil'), [1, 2])){
                                             $btnArchivo = '';
                                             if($archivoSeg && file_exists(FCPATH . $archivoSeg)){
                                                 $btnArchivo = '<a href="'.base_url($archivoSeg).'" target="_blank" class="btn btn-xs btn-outline-info ml-1" title="Ver Formato"><i class="fas fa-file-pdf"></i></a>';
                                             }
                                             
                                             $seguimientoHtml = '
                                             <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="seg_'.$op->id_operacion.'" value="'.$seguimientoVal.'" placeholder="Seguimiento...">
                                                <input type="file" id="file_seg_'.$op->id_operacion.'" style="display:none;" accept=".pdf,.jpg,.png,.jpeg">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="$(\'#file_seg_'.$op->id_operacion.'\').click()" title="Adjuntar Archivo"><i class="fas fa-paperclip"></i></button>
                                                    <button class="btn btn-primary" type="button" onclick="prepararGuardadoSeguimiento('.$op->id_operacion.')"><i class="fas fa-save"></i></button>
                                                </div>
                                             </div>
                                             <div id="info_file_'.$op->id_operacion.'" class="text-xs text-muted mt-1">'.$btnArchivo.'</div>';
                                        } else {
                                            $seguimientoHtml = $seguimientoVal;
                                            if($archivoSeg && file_exists(FCPATH . $archivoSeg)){
                                                 $seguimientoHtml .= ' <a href="'.base_url($archivoSeg).'" target="_blank" class="text-info"><i class="fas fa-file-pdf"></i></a>';
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $tipo ?></td>
                                        <td><?= $detalles ?></td>
                                        <td><?= $extra ?></td>
                                        <td><?= $seguimientoHtml ?></td>
                                        <td><?= $estado ?></td>
                                        <td><?= date('d/m/Y', strtotime($op->fec_reg)) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-warning" onclick="editarOperacionWrapper(<?= $op->id_operacion ?>)"><i class="fas fa-edit"></i></button>
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
                                <label class="col-sm-3 col-form-label">TIPO DE OPERACIÓN <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <select class="form-control required-field" id="id_tipo_operacion" name="id_tipo_operacion" onchange="ini.inicio.tipoOperacion.cambioTipo(this.value)" required>
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
                                    <label class="col-sm-3 col-form-label">Abono a tarjeta: <span class="text-danger">*</span></label>
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
                                    <label class="col-sm-3 col-form-label">Comprobante (PDF/Img) <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="comprobante" id="comprobante_deposito" accept=".pdf,.jpg,.jpeg,.png">
                                        <small class="text-muted" id="link_comprobante"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- TRASPASO -->
                            <div id="div_traspaso" class="seccion-op" style="display:none;">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Traspaso, Cuenta Origen: <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="cuenta_traspaso" id="cuenta_traspaso_origen">
                                            <option value="">Seleccione Cuenta...</option>
                                            <?php foreach ($cat_deposito as $d): ?>
                                                <option value="<?= $d->id_deposito ?>"><?= $d->nombre_completo.'-'. $d->dsc_cuenta ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Cantidad de Destinos Selector -->
                                <div class="form-group row" id="row_cant_destinos">
                                    <label class="col-sm-3 col-form-label">Cantidad de Destinos:</label>
                                    <div class="col-sm-9">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="cant_destinos" id="dest_1" value="1" checked onchange="cambiarDestinos(1)">
                                            <label class="form-check-label" for="dest_1">1</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="cant_destinos" id="dest_2" value="2" onchange="cambiarDestinos(2)">
                                            <label class="form-check-label" for="dest_2">2</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="cant_destinos" id="dest_3" value="3" onchange="cambiarDestinos(3)">
                                            <label class="form-check-label" for="dest_3">3</label>
                                        </div>
                                     
                                </div>

                                <!-- Destino 1 -->
                                <div id="div_destino_1" class="border p-2 mb-2 rounded border-light">
                                    <h6 class="text-muted">Destino 1</h6>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cuenta Destino: <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="cuenta_destino[]" id="cuenta_traspaso_destino">
                                                <option value="">Seleccione Cuenta...</option>
                                                <?php foreach ($cat_deposito as $d): ?>
                                                    <option value="<?= $d->id_deposito ?>"><?= $d->nombre_completo.'-'. $d->dsc_cuenta ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Importe $ <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="number" step="0.01" class="form-control" name="importe[]" id="importe_traspaso">
                                        </div>
                                    </div>
                                </div>

                                <!-- Destino 2 -->
                                <div id="div_destino_2" class="border p-2 mb-2 rounded border-light" style="display:none;">
                                    <h6 class="text-muted">Destino 2</h6>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cuenta Destino: <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="cuenta_destino[]" id="cuenta_traspaso_destino_2">
                                                <option value="">Seleccione Cuenta...</option>
                                                <?php foreach ($cat_deposito as $d): ?>
                                                    <option value="<?= $d->id_deposito ?>"><?= $d->nombre_completo.'-'. $d->dsc_cuenta ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Importe $ <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="number" step="0.01" class="form-control" name="importe[]" id="importe_traspaso_2">
                                        </div>
                                    </div>
                                </div>

                                <!-- Destino 3 -->
                                <div id="div_destino_3" class="border p-2 mb-2 rounded border-light" style="display:none;">
                                    <h6 class="text-muted">Destino 3</h6>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Cuenta Destino: <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="cuenta_destino[]" id="cuenta_traspaso_destino_3">
                                                <option value="">Seleccione Cuenta...</option>
                                                <?php foreach ($cat_deposito as $d): ?>
                                                    <option value="<?= $d->id_deposito ?>"><?= $d->nombre_completo.'-'. $d->dsc_cuenta ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Importe $ <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="number" step="0.01" class="form-control" name="importe[]" id="importe_traspaso_3">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Justificación <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" name="justificaciones" id="justificaciones" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            
                             <!-- CONSULTA CORTE -->
                             <div id="div_corte" class="seccion-op" style="display:none;">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Consulta Corte (Estado): <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="estado_cuenta" id="estado_cuenta">
                                            <option value="Pendiente">Pendiente</option>
                                            <option value="Finalizado">Finalizado</option>
                                            <option value="En Revisión">En Revisión</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Periodo <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="date" class="form-control" name="periodo" id="periodo">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" onclick="guardarOperacionValidada()">Guardar</button>
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

<script>
    // Script para Guardar Seguimiento
    // Script para Guardar Seguimiento
    function prepararGuardadoSeguimiento(idOperacion) {
        var seguimiento = $('#seg_' + idOperacion).val();
        var fileInput = document.getElementById('file_seg_' + idOperacion);
        var file = fileInput.files[0];
        
        // Validación básica
        if(seguimiento.trim() == "" && !file) {
            Swal.fire("Atención", "Ingrese un comentario o adjunte un archivo.", "warning");
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se guardará el seguimiento y se enviará un correo de notificación.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar y enviar'
        }).then((result) => {
            if (result.isConfirmed) {
                enviarSeguimiento(idOperacion, seguimiento, file);
            }
        });
    }

    function enviarSeguimiento(idOperacion, seguimiento, file) {
        var formData = new FormData();
        formData.append('id_operacion', idOperacion);
        formData.append('seguimiento', seguimiento);
        if(file) {
            formData.append('archivo', file);
        }

        Swal.fire({
            title: 'Procesando...',
            text: 'Subiendo archivo y enviando correo...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '<?= base_url() ?>index.php/Inicio/guardarSeguimiento',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (!response.error) {
                    Swal.fire("Correcto", response.respuesta, "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error", response.respuesta, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "Ocurrió un error en la solicitud", "error");
            }
        });
    }
    
    // Listener para mostrar nombre del archivo seleccionado
    $(document).on('change', 'input[type="file"][id^="file_seg_"]', function() {
        var id = this.id.replace('file_seg_', '');
        var fileName = this.files[0] ? this.files[0].name : '';
        if(fileName) {
            $('#info_file_' + id).append(' <span class="badge badge-secondary">'+fileName+'</span>');
        }
    });

    // Script para Validar y Guardar Operación (Reemplaza ini.inicio.tipoOperacion.guardar)
    function guardarOperacionValidada() {
        var tipo = $('#id_tipo_operacion').val();
        var isValid = true;
        var msg = "";

        if (tipo == "") {
            isValid = false; msg = "Seleccione un TIPO DE OPERACIÓN.";
        } else if (tipo == "1") { // Depósito
            if ($('#id_deposito').val() == "") { isValid = false; msg = "Seleccione Abono a tarjeta."; }
            else if ($('#importe_deposito').val() == "") { isValid = false; msg = "Ingrese el Importe."; }
             else if ($('#id_operacion').val() == "" && $('#comprobante_deposito').val() == "") { isValid = false; msg = "Adjunte el Comprobante."; }
        } else if (tipo == "2") { // Traspaso
            if ($('#cuenta_traspaso_origen').val() == "") { isValid = false; msg = "Seleccione Cuenta Origen."; }
            
            // Multiple Destinations
            var cant = 1;
            if($('#row_cant_destinos').is(':visible')) {
                 cant = $('input[name=cant_destinos]:checked').val();
            }

            // Destino 1 (Always required)
            if ($('#cuenta_traspaso_destino').val() == "") { isValid = false; msg = "Seleccione Cuenta Destino 1."; }
            else if ($('#importe_traspaso').val() == "") { isValid = false; msg = "Ingrese el Importe 1."; }
            
            // Destino 2
            if(cant >= 2) {
                 if ($('#cuenta_traspaso_destino_2').val() == "") { isValid = false; msg = "Seleccione Cuenta Destino 2."; }
                 else if ($('#importe_traspaso_2').val() == "") { isValid = false; msg = "Ingrese el Importe 2."; }
            }
            // Destino 3
            if(cant >= 3) {
                 if ($('#cuenta_traspaso_destino_3').val() == "") { isValid = false; msg = "Seleccione Cuenta Destino 3."; }
                 else if ($('#importe_traspaso_3').val() == "") { isValid = false; msg = "Ingrese el Importe 3."; }
            }

            if (isValid && $('#justificaciones').val() == "") { isValid = false; msg = "Ingrese Justificación."; }

        } else if (tipo == "3") { // Corte
            if ($('#estado_cuenta').val() == "") { isValid = false; msg = "Seleccione Estado."; }
            else if ($('#periodo').val() == "") { isValid = false; msg = "Seleccione Periodo."; }
        }

        if (!isValid) {
            Swal.fire("Atención", "Todos los campos son requeridos. " + msg, "warning");
            return;
        }
        
        var formData = new FormData(document.getElementById("formTipoOperacion"));
        
        $.ajax({
            url: '<?= base_url() ?>index.php/Inicio/guardarTipoOperacion',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (!response.error) {
                    Swal.fire("Correcto", response.respuesta, "success").then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire("Error", response.respuesta, "error");
                }
            },
            error: function() {
                Swal.fire("Error", "Error al guardar operación", "error");
            }
        });
    }

    function nuevaOperacionWrapper() {
        $('#row_cant_destinos').show();
        // Reset destinations
        $('#dest_1').prop('checked', true);
        cambiarDestinos(1);
        
        if(typeof ini !== 'undefined' && ini.inicio && ini.inicio.tipoOperacion) {
            ini.inicio.tipoOperacion.nuevo();
        }
    }

    function editarOperacionWrapper(id) {
        $('#row_cant_destinos').hide();
        cambiarDestinos(1);
         if(typeof ini !== 'undefined' && ini.inicio && ini.inicio.tipoOperacion) {
            ini.inicio.tipoOperacion.editar(id);
        }
    }

    function cambiarDestinos(cant) {
        if(cant == 1) {
            $('#div_destino_2').hide();
            $('#div_destino_3').hide();
        } else if(cant == 2) {
            $('#div_destino_2').show();
            $('#div_destino_3').hide();
        } else {
            $('#div_destino_2').show();
            $('#div_destino_3').show();
        }
    }
</script>

